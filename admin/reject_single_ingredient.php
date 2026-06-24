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
    $info_query = mysqli_query($con, "SELECT request_id FROM bakers_request_items WHERE id = '$bakers_item_id'");
    $item_info = mysqli_fetch_assoc($info_query);

    if (!$item_info) {
        echo json_encode(['success' => false, 'message' => 'Target item records could not be found.']);
        exit;
    }

    $parent_request_id = $item_info['request_id'];

    // 2. Clear or delete the single item entry
    // NOTE: If you prefer keeping the record and typing it as 'Rejected', swap to an UPDATE status query
    $delete_query = "UPDATE bakers_request_items SET status = 'rejected' WHERE id = '$bakers_item_id'";

    if (mysqli_query($con, $delete_query)) {
        
        // 3. Optional: Check if the master parent layout order has remaining sub-items
        $check_remaining = mysqli_query($con, "SELECT COUNT(*) as total FROM bakers_request_items WHERE request_id = '$parent_request_id' AND status = 'rejected'");
        $remaining_count = mysqli_fetch_assoc($check_remaining)['total'];

        if ($remaining_count == 0) {
            // Automatically close out or reject the parent tracking ticket if zero items remain
            mysqli_query($con, "UPDATE bakers_requests SET status = 'rejected', approved_status = 'Rejected' WHERE id = '$parent_request_id'");
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to isolate and drop target element row from database storage.']);
    }
    exit;
}