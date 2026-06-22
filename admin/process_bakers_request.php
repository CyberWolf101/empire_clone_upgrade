<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); 
include "../connect.php";

echo "<h1>🔍 Debugging Mode Activated</h1>";

if (!isset($_POST['request_id'])) {
    die("❌ Error: No request_id found in the POST request.");
}

$request_id = (int)$_POST['request_id'];
echo "Target Request ID: " . $request_id . "<br>";

echo "<h3>📦 Raw Form Data Received ($_POST):</h3>";
echo "<pre>"; print_r($_POST); echo "</pre>";

if (empty($_POST['collect_qty']) || !is_array($_POST['collect_qty'])) {
    die("❌ Error: 'collect_qty' is missing, empty, or not an array. Your frontend is not passing the fields properly.");
}

$username = 'system';
$code = $_SESSION['adminid'] ?? ($_COOKIE['adminID'] ?? null);
echo "Logged Admin Code/Email: " . ($code ?? 'None found') . "<br>";

if ($code) {
    $code_escaped = mysqli_real_escape_string($con, $code);
    $sql2 = mysqli_query($con, "SELECT name FROM admin WHERE email = '$code_escaped' LIMIT 1");
    if ($sql2 && $row = mysqli_fetch_assoc($sql2)) {
        $username = $row['name'];
    }
}
echo "Resolved Action Username: " . $username . "<br><br>";

mysqli_begin_transaction($con);

try {
    echo "<h3>🔄 Entering Processing Loop...</h3>";
    $loop_count = 0;

    foreach ($_POST['collect_qty'] as $request_item_id => $collect_qty) {
        $request_item_id = (int)$request_item_id;
        $collect_qty = (float)$collect_qty;

        echo "Checking Item Link ID: $request_item_id | Submitted Qty: $collect_qty <br>";

        if ($collect_qty <= 0) {
            echo "⏭️ Skipped (Quantity is 0 or negative)<br>";
            continue;
        }

        $loop_count++;

        // Fetch verification
        $itemSql = mysqli_query($con, "
            SELECT 
                bri.*, ci.productname, ci.packs, ci.pack_quantity, ci.pieces, ci.countable
            FROM bakers_request_items bri
            INNER JOIN chb_inventory ci ON ci.product = bri.item_id
            WHERE bri.id = '$request_item_id'
        ");

        if (!$itemSql || mysqli_num_rows($itemSql) == 0) {
            throw new Exception("Database Row Lookup Failed: Request item link ID {$request_item_id} matched no active inventory products. Verify your table mapping keys.");
        }

        $item = mysqli_fetch_assoc($itemSql);
        echo "Found Match: " . $item['productname'] . "<br>";

        $requestedQty = (float)$item['quantity'];
        $collectedQty = (float)$item['collected_quantity'];
        $remainingQty = $requestedQty - $collectedQty;

        $packs = (int)($item['packs'] ?? 0);
        $pack_quantity = (int)($item['pack_quantity'] ?? 1);
        $pieces = (float)($item['pieces'] ?? 0); 
        $total_stock = ($packs * $pack_quantity) + $pieces;

        if ($collect_qty > $remainingQty) {
            throw new Exception($item['productname'] . " - Exceeds request balance (Remaining: $remainingQty)");
        }

        if ($collect_qty > $total_stock) {
            throw new Exception($item['productname'] . " - Not enough total stock (In Stock: $total_stock)");
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
                throw new Exception($item['productname'] . " - Insufficient packs in stock");
            }
            $packs_needed = ceil($remaining_to_deduct / $pack_quantity);
            $packs -= $packs_needed;
            $total_pieces_opened = $packs_needed * $pack_quantity;
            $waste_leftover = $total_pieces_opened - $remaining_to_deduct;
            $pieces += $waste_leftover;
        }

        $new_inventory = ($packs * $pack_quantity) + $pieces;

        // DB Operations
        echo "💾 Writing updates to database...<br>";
        
        mysqli_query($con, "
            UPDATE chb_inventory
            SET packs = $packs, pieces = $pieces, inventory = $new_inventory, inventory_deducted = inventory_deducted + $collect_qty
            WHERE product = '{$item['item_id']}'
        ");
        if (mysqli_error($con)) throw new Exception("chb_inventory UPDATE SQL Error: " . mysqli_error($con));

        mysqli_query($con, "
            UPDATE bakers_request_items
            SET collected_quantity = collected_quantity + $collect_qty
            WHERE id = $request_item_id
        ");
        if (mysqli_error($con)) throw new Exception("bakers_request_items UPDATE SQL Error: " . mysqli_error($con));

        $productName = mysqli_real_escape_string($con, $item['productname']);
        $username_escaped = mysqli_real_escape_string($con, $username);

        mysqli_query($con, "
            INSERT INTO chb_inventory_history
            (product, productname, quantity, quantity_left, deducted_by, collected_by, action, date, total_left)
            VALUES
            ('{$item['item_id']}', '$productName', '$collect_qty', '$total_stock', '$username_escaped', '$username_escaped', 'Bakers Request Collection', NOW(), '$new_inventory')
        ");
        if (mysqli_error($con)) throw new Exception("History INSERT SQL Error: " . mysqli_error($con));
        
        echo "✅ Item step complete.<br><br>";
    }

    if ($loop_count === 0) {
        echo "⚠️ Note: The loop ran but no item fields had positive collection quantities entered.<br>";
    }

    // Status Engine Check
    $statusSql = mysqli_query($con, "SELECT quantity, collected_quantity FROM bakers_request_items WHERE request_id = '$request_id'");
    $allCollected = true; $anyCollected = false;

    while ($row = mysqli_fetch_assoc($statusSql)) {
        $qty = (float)$row['quantity'];
        $collected = (float)$row['collected_quantity'];
        if ($collected > 0) $anyCollected = true;
        if ($collected < $qty) $allCollected = false;
    }

    $status = $allCollected ? "Collected" : ($anyCollected ? "Partially Collected" : "Pending");
    
    mysqli_query($con, "UPDATE bakers_requests SET status = '$status' WHERE id = '$request_id'");
    if (mysqli_error($con)) throw new Exception("Main Request Status UPDATE SQL Error: " . mysqli_error($con));

    mysqli_commit($con);
    echo "<h2>🎉 SUCCESS! Database Transaction Committed Permanently.</h2>";

} catch (Exception $e) {
    mysqli_rollback($con);
    echo "<h2 style='color:red;'>❌ Transaction Failed & Rolled Back</h2>";
    echo "<strong>Error Message:</strong> " . $e->getMessage();
}

echo "<br><br><a href='viewrequest.php?id=$request_id'>&larr; Click here to return to View Request</a>";
header("Location: viewrequest.php?id=" . $request_id); 
exit;