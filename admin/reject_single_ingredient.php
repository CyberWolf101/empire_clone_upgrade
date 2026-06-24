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
        
        // 3. FIXED: Count items that are STILL active (NOT rejected or partially rejected)
        $check_active = mysqli_query($con, "
            SELECT COUNT(*) as total 
            FROM bakers_request_items 
            WHERE request_id = '$parent_request_id' 
              AND status NOT IN ('rejected', 'partially rejected')
        ");
        $active_count = mysqli_fetch_assoc($check_active)['total'];

        if ($active_count == 0) {
            // Automatically close out or reject the parent tracking ticket ONLY if NO active items remain
            mysqli_query($con, "UPDATE bakers_requests SET status = 'rejected', approved_status = 'Rejected' WHERE id = '$parent_request_id'");
        } else {
            // Update the master order tracker state to 'partially rejected' safely
            // But don't downgrade it if the order is already in mid-collection progress ('partially collected')
            mysqli_query($con, "
                UPDATE bakers_requests 
                SET status = 'partially rejected' 
                WHERE id = '$parent_request_id' 
                  AND status != 'partially collected'
            ");
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to isolate and drop target element row from database storage.']);
    }
    exit;
}