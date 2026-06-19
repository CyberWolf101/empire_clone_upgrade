<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "../connect.php";

if (!isset($_POST['request_id'])) {
    die("Invalid Request");
}

$username = 'system';
$code = $_SESSION['adminid'] ?? ($_COOKIE['adminID'] ?? null);

if ($code) {
    // Escape the session/cookie string to prevent SQL injection
    $code_escaped = mysqli_real_escape_string($con, $code);
    
    $sql2 = mysqli_query($con, "
        SELECT name
        FROM admin
        WHERE email = '$code_escaped'
        LIMIT 1
    ");

    if ($sql2 && $row = mysqli_fetch_assoc($sql2)) {
        $username = $row['name'];
    }
}

$request_id = (int)$_POST['request_id'];

if (empty($_POST['collect_qty']) || !is_array($_POST['collect_qty'])) {
    header("Location: viewrequest.php?id=" . $request_id);
    exit;
}

mysqli_begin_transaction($con);

try {
    foreach ($_POST['collect_qty'] as $request_item_id => $collect_qty) {

        $request_item_id = (int)$request_item_id;
        $collect_qty = (float)$collect_qty;

        if ($collect_qty <= 0) {
            continue;
        }

        /*
        ---------------------------------------------------
        GET REQUEST ITEM & CURRENT INVENTORY
        ---------------------------------------------------
        */
        $itemSql = mysqli_query($con, "
            SELECT 
                bri.*,
                ci.productname,
                ci.packs,
                ci.pack_quantity,
                ci.pieces,
                ci.countable
            FROM bakers_request_items bri
            INNER JOIN chb_inventory ci
                ON ci.product = bri.item_id
            WHERE bri.id = '$request_item_id'
        ");

        if (!$itemSql || mysqli_num_rows($itemSql) == 0) {
            throw new Exception("Request item ID {$request_item_id} not found");
        }

        $item = mysqli_fetch_assoc($itemSql);

        $requestedQty = (float)$item['quantity'];
        $collectedQty = (float)$item['collected_quantity'];

        $remainingQty = $requestedQty - $collectedQty;

        /*
        ---------------------------------------------------
        STOCK VALIDATIONS
        ---------------------------------------------------
        */
        $packs = (int)($item['packs'] ?? 0);
        $pack_quantity = (int)($item['pack_quantity'] ?? 1);
        $pieces = (float)($item['pieces'] ?? 0); // Kept float in case of weight fragments

        $total_stock = ($packs * $pack_quantity) + $pieces;

        if ($collect_qty > $remainingQty) {
            throw new Exception($item['productname'] . " - Exceeds request balance");
        }

        if ($collect_qty > $total_stock) {
            throw new Exception($item['productname'] . " - Not enough total stock");
        }

        /*
        ---------------------------------------------------
        UNIFIED INVENTORY DEDUCTION ENGINE
        ---------------------------------------------------
        */
        $remaining_to_deduct = $collect_qty;

        // Step 1: Drain loose pieces first
        if ($pieces >= $remaining_to_deduct) {
            $pieces -= $remaining_to_deduct;
            $remaining_to_deduct = 0;
        } else {
            $remaining_to_deduct -= $pieces;
            $pieces = 0;
        }

        // Step 2: Break down whole packs if more quantities are required
        if ($remaining_to_deduct > 0) {
            $pack_units_available = $packs * $pack_quantity;

            if ($remaining_to_deduct > $pack_units_available) {
                throw new Exception($item['productname'] . " - Insufficient packs in stock");
            }

            // Calculate exact number of full packs to rip open
            $packs_needed = ceil($remaining_to_deduct / $pack_quantity);
            $packs -= $packs_needed;

            $total_pieces_opened = $packs_needed * $pack_quantity;
            $waste_leftover = $total_pieces_opened - $remaining_to_deduct;

            // Rest goes back into loose pieces
            $pieces += $waste_leftover;
        }

        // Recompute standard math calculation values
        $new_inventory = ($packs * $pack_quantity) + $pieces;

        /*
        ---------------------------------------------------
        EXECUTE DATABASE UPDATES
        ---------------------------------------------------
        */
        
        // Update chb_inventory
        /*
        ---------------------------------------------------
        EXECUTE DATABASE UPDATES
        ---------------------------------------------------
        */
        
        // Update chb_inventory (including inventory_deducted accumulation)
        mysqli_query($con, "
            UPDATE chb_inventory
            SET packs = $packs,
                pieces = $pieces,
                inventory = $new_inventory,
                inventory_deducted = inventory_deducted + $collect_qty
            WHERE product = '{$item['item_id']}'
        ");

        if (mysqli_error($con)) {
            throw new Exception("Inventory update failed: " . mysqli_error($con));
        }


        // Update bakers_request_items
        mysqli_query($con, "
            UPDATE bakers_request_items
            SET collected_quantity = collected_quantity + $collect_qty
            WHERE id = $request_item_id
        ");

        if (mysqli_error($con)) {
            throw new Exception("Request balance update failed: " . mysqli_error($con));
        }

        // Write to History Log
        $productName = mysqli_real_escape_string($con, $item['productname']);
        $username_escaped = mysqli_real_escape_string($con, $username);

        mysqli_query($con, "
            INSERT INTO chb_inventory_history
            (product, productname, quantity, quantity_left, deducted_by, collected_by, action, date, total_left)
            VALUES
            (
                '{$item['item_id']}',
                '$productName',
                '$collect_qty',
                '$total_stock',
                '$username_escaped',
                '$username_escaped',
                'Bakers Request Collection',
                NOW(),
                '$new_inventory'
            )
        ");

        if (mysqli_error($con)) {
            throw new Exception("History insert failed: " . mysqli_error($con));
        }
    }

    /*
    ---------------------------------------------------
    DETERMINE FINAL REQUEST STATUS
    ---------------------------------------------------
    */
    $statusSql = mysqli_query($con, "
        SELECT quantity, collected_quantity
        FROM bakers_request_items
        WHERE request_id = '$request_id'
    ");

    $allCollected = true;
    $anyCollected = false;

    while ($row = mysqli_fetch_assoc($statusSql)) {
        $qty = (float)$row['quantity'];
        $collected = (float)$row['collected_quantity'];

        if ($collected > 0) {
            $anyCollected = true;
        }

        if ($collected < $qty) {
            $allCollected = false;
        }
    }

    if ($allCollected) {
        $status = "Collected";
    } elseif ($anyCollected) {
        $status = "Partially Collected";
    } else {
        $status = "Pending";
    }

    mysqli_query($con, "
        UPDATE bakers_requests
        SET status = '$status'
        WHERE id = '$request_id'
    ");

    mysqli_commit($con);
    $_SESSION['success'] = "Collection processed successfully.";

} catch (Exception $e) {
    mysqli_rollback($con);
    $_SESSION['error'] = $e->getMessage();
}

header("Location: viewrequest.php?id=" . $request_id);
exit;