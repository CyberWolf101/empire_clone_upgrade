<?php
session_start();
include "../connect.php";

header('Content-Type: application/json');

// Security check matching your dashboard layout settings
$isAdmin = isset($_SESSION['is_admin']) || isset($_SESSION['adminname']); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bakers_item_id = (int)$_POST['bakers_item_id'];

    if ($bakers_item_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing target record tracking identifier.']);
        exit;
    }

    // 1. Fetch parent request details before execution to maintain traceability
    $info_query = mysqli_query($con, "SELECT * FROM bakers_request_items WHERE id = '$bakers_item_id'");
    $item_info = mysqli_fetch_assoc($info_query);

    if (!$item_info) {
        echo json_encode(['success' => false, 'message' => 'Target item records could not be found.']);
        exit;
    }

    $parent_request_id = $item_info['request_id'];

    // 2. Safely isolate row modification rules
    $status = 'rejected';
    if ($item_info['collected_quantity'] > 0) {
        $status = 'partially rejected';
    }
    $delete_query = "UPDATE bakers_request_items SET status = '$status' WHERE id = '$bakers_item_id'";

    if (mysqli_query($con, $delete_query)) {
        
        // 3. FETCH THE STATUS MATRICES FOR ALL SUB-ITEMS UNDER THIS REQUEST
        $all_items_query = mysqli_query($con, "
            SELECT 
                quantity, 
                collected_quantity, 
                status 
            FROM bakers_request_items 
            WHERE request_id = '$parent_request_id'
        ");

        $total_items = 0;
        $rejected_items_count = 0;
        $fully_collected_items_count = 0;

        while ($row = mysqli_fetch_assoc($all_items_query)) {
            $total_items++;
            $item_status_l = strtolower(trim((string)$row['status']));
            
            if (in_array($item_status_l, ['rejected', 'partially rejected'])) {
                $rejected_items_count++;
            } elseif ((float)$row['collected_quantity'] >= (float)$row['quantity']) {
                $fully_collected_items_count++;
            }
        }

        // 4. MASTER BOUNDARY UPDATE LOGIC
        if ($rejected_items_count === $total_items) {
            // Case A: Every single item in this order was completely rejected
            mysqli_query($con, "UPDATE bakers_requests SET status = 'rejected', approved_status = 'Rejected' WHERE id = '$parent_request_id'");
        } 
        elseif (($rejected_items_count + $fully_collected_items_count) === $total_items) {
            // Case B: All items are "touched"—either rejected, partially rejected, or fully collected. No active items remain.
            mysqli_query($con, "UPDATE bakers_requests SET status = 'collected', approved_status = 'Collected' WHERE id = '$parent_request_id'");
        } 
        else {
            // Case C: There are still pending/approved items waiting for action in the table
            // Set the master ticket to 'partially rejected', unless parts of it have already been collected
            mysqli_query($con, "
                UPDATE bakers_requests 
                SET status = 'partially rejected' 
                WHERE id = '$parent_request_id' 
                  AND status != 'partially collected' 
                  AND status != 'collected'
            ");
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to isolate and drop target element row from database storage.']);
    }
    exit;
}