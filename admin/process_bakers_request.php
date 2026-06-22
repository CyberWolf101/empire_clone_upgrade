<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); 
include "../connect.php";

// 1. REINSTATE CRITICAL SAFEGUARDS (Prevents processing ID 0)
if (!isset($_POST['request_id']) || empty($_POST['request_id'])) {
    $_SESSION['error'] = "Invalid or missing Request ID.";
    header("Location: bakersrequests.php");
    exit;
}

$request_id = (int)$_POST['request_id'];

if (empty($_POST['collect_qty']) || !is_array($_POST['collect_qty'])) {
    $_SESSION['error'] = "No collection quantities provided.";
    header("Location: viewrequest.php?id=" . $request_id);
    exit;
}

$username = 'system';
$code = $_SESSION['adminid'] ?? ($_COOKIE['adminID'] ?? null);

if ($code) {
    $code_escaped = mysqli_real_escape_string($con, $code);
    $sql2 = mysqli_query($con, "SELECT name FROM admin WHERE email = '$code_escaped' LIMIT 1");
    if ($sql2 && $row = mysqli_fetch_assoc($sql2)) {
        $username = $row['name'];
    }
}

mysqli_begin_transaction($con);

try {
    $processed_items = 0;

    foreach ($_POST['collect_qty'] as $request_item_id => $collect_qty) {
        $request_item_id = (int)$request_item_id;
        $collect_qty = (float)$collect_qty;

        if ($collect_qty <= 0) {
            continue;
        }

        $processed_items++;

        // Fetch verification
        $itemSql = mysqli_query($con, "
            SELECT 
                bri.*, ci.productname, ci.packs, ci.pack_quantity, ci.pieces, ci.countable
            FROM bakers_request_items bri
            INNER JOIN chb_inventory ci ON ci.product = bri.item_id
            WHERE bri.id = '$request_item_id'
        ");

        if (!$itemSql || mysqli_num_rows($itemSql) == 0) {
            throw new Exception("Inventory lookup failed for item link ID {$request_item_id}.");
        }

        $item = mysqli_fetch_assoc($itemSql);

        $requestedQty = (float)$item['quantity'];
        $collectedQty = (float)$item['collected_quantity'];
        $remainingQty = $requestedQty - $collectedQty;

        $packs = (int)($item['packs'] ?? 0);
        $pack_quantity = (int)($item['pack_quantity'] ?? 1);
        $pieces = (float)($item['pieces'] ?? 0); 
        $total_stock = ($packs * $pack_quantity) + $pieces;

        if ($collect_qty > $remainingQty) {
            throw new Exception($item['productname'] . " - Exceeds request balance.");
        }

        if ($collect_qty > $total_stock) {
            throw new Exception($item['productname'] . " - Insufficient stock.");
        }

        // Engine calculations
        $remaining_to_deduct = $collect_qty;
        if ($pieces >= $remaining_to_deduct) {
            $pieces -= $remaining_to_deduct;
            $remaining_to_deduct = 0;
        } else {
            $remaining_to_deduct -= $pieces;
            $pieces = 0;
        }

        if ($remaining_to_deduct > 0) {
            $pack_units_available = $packs * $pack_quantity;
            if ($remaining_to_deduct > $pack_units_available) {
                throw new Exception($item['productname'] . " - Insufficient packs in stock.");
            }
            $packs_needed = ceil($remaining_to_deduct / $pack_quantity);
            $packs -= $packs_needed;
            $total_pieces_opened = $packs_needed * $pack_quantity;
            $waste_leftover = $total_pieces_opened - $remaining_to_deduct;
            $pieces += $waste_leftover;
        }

        $new_inventory = ($packs * $pack_quantity) + $pieces;

        // DB Operations
        mysqli_query($con, "
            UPDATE chb_inventory
            SET packs = $packs, pieces = $pieces, inventory = $new_inventory, inventory_deducted = inventory_deducted + $collect_qty
            WHERE product = '{$item['item_id']}'
        ");
        if (mysqli_error($con)) throw new Exception("Inventory Update Error: " . mysqli_error($con));

        mysqli_query($con, "
            UPDATE bakers_request_items
            SET collected_quantity = collected_quantity + $collect_qty
            WHERE id = $request_item_id
        ");
        if (mysqli_error($con)) throw new Exception("Item Update Error: " . mysqli_error($con));

        $productName = mysqli_real_escape_string($con, $item['productname']);
        $username_escaped = mysqli_real_escape_string($con, $username);

        mysqli_query($con, "
            INSERT INTO chb_inventory_history
            (product, productname, quantity, quantity_left, deducted_by, collected_by, action, date, total_left)
            VALUES
            ('{$item['item_id']}', '$productName', '$collect_qty', '$total_stock', '$username_escaped', '$username_escaped', 'Bakers Request Collection', NOW(), '$new_inventory')
        ");
        if (mysqli_error($con)) throw new Exception("History Log Error: " . mysqli_error($con));
    }

    /*
    ---------------------------------------------------
    DETERMINE FINAL REQUEST STATUS Safely
    ---------------------------------------------------
    */
    $statusSql = mysqli_query($con, "SELECT quantity, collected_quantity FROM bakers_request_items WHERE request_id = '$request_id'");
    
    $allCollected = true; 
    $anyCollected = false;
    $hasRows = false;

    while ($row = mysqli_fetch_assoc($statusSql)) {
        $hasRows = true;
        $qty = (float)$row['quantity'];
        $collected = (float)$row['collected_quantity'];
        
        if ($collected > 0) {
            $anyCollected = true;
        }
        if ($collected < $qty) {
            $allCollected = false;
        }
    }

    // Determine target string context value explicitly
    if (!$hasRows) {
    $status = "approved";
} elseif ($allCollected) {
    $status = "collected";
} elseif ($anyCollected) {
    $status = "partially collected";
} else {
    $status = "approved";
}

    // Double safeguard: If the user didn't enter anything new to save, read the database's existing value to avoid risk of resets
// ...existing code...
    // Double safeguard: If the user didn't enter anything new to save, read the database's existing value to avoid risk of resets
    if ($processed_items === 0) {
         $checkCurrent = mysqli_query($con, "SELECT status FROM bakers_requests WHERE id = '$request_id'");
         if ($currentRes = mysqli_fetch_assoc($checkCurrent)) {
             $status = $currentRes['status'];
         }
    }
    
    // Ensure $status is never empty - fall back to a sensible default if needed
    $status = isset($status) ? trim((string)$status) : '';
    if ($status === '') {
        // try to read current DB value again; if that's empty, use default
        $checkCurrent = mysqli_query($con, "SELECT status FROM bakers_requests WHERE id = '$request_id' LIMIT 1");
        if ($checkCurrent) {
    $row = mysqli_fetch_assoc($checkCurrent);

    if ($row && !empty($row['status'])) {
        $status = $row['status'];
    }
}
    }
    
    // Explicit clean string treatment to prevent execution dropouts
    $status_clean = mysqli_real_escape_string($con, $status);
    
    // Only update when we have a non-empty, sanitized status
    if ($status_clean !== '') {
        mysqli_query($con, "UPDATE bakers_requests SET status = '$status_clean' WHERE id = '$request_id'");
        if (mysqli_error($con)) {
            throw new Exception("Main Request Status Update Failed: " . mysqli_error($con));
        }
    }
    
    mysqli_commit($con);
    $_SESSION['success'] = "Collection processed successfully! Status set to: " . $status_clean;
// ...existing code...
} catch (Exception $e) {
    mysqli_rollback($con);
    $_SESSION['error'] = "Transaction Failed: " . $e->getMessage();
}

// 2. CLEAN REDIRECTS 
header("Location: viewrequest.php?id=" . $request_id); 
exit;