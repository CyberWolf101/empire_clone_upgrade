<?php
session_start();
include "../connect.php"; // Keeps your exact layout connection setup

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guide_id = $_POST['guide_id'];
    $batch_count = (float)$_POST['batch_count'];
    $items = isset($_POST['items']) ? $_POST['items'] : [];
    $requested_by = $_SESSION['adminname'] ?? 'System User'; // Matches your session profile key

    if (empty($items) || $batch_count <= 0) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid positive quantity request entry.']);
        exit;
    }

    // 1. Generate unique alphanumeric request identifier token strings (matching your REQ- format)
    $request_code = "REQ-" . strtoupper(uniqid());

    // 2. Insert into the parent tracking table bakers_requests using your precise column scheme
    $parent_query = "INSERT INTO bakers_requests (
        request_code,
        guide_id,
        quantity,
        requested_by,
        status,
        approved_status
    ) VALUES (
        '$request_code',
        '$guide_id',
        '$batch_count',
        '$requested_by',
        'pending',
        'pending'
    )";
    
    if (mysqli_query($con, $parent_query)) {
        $new_request_id = mysqli_insert_id($con);
        $errors = 0;

        // 3. Loop through individual calculation rows to insert into the child subtable
        foreach ($items as $item) {
            $item_id = mysqli_real_escape_string($con, $item['item_id']);
            $request_qty = (float)$item['quantity'];

            if ($request_qty > 0) {
                $item_query = "INSERT INTO bakers_request_items (
                    request_id, 
                    item_id, 
                    quantity, 
                    collected_quantity
                ) VALUES (
                    '$new_request_id', 
                    '$item_id', 
                    '$request_qty', 
                    0
                )";
                
                if (!mysqli_query($con, $item_query)) {
                    $errors++;
                }
            }
        }

        if ($errors === 0) {
            // Set the success message into the session just like your single request flow
            $_SESSION['success'] = "Inventory Bulk Request standard tracking token pipeline logged successfully!";
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Parent request initialized, but some item breakdown entries failed.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database Parent Write Pipeline Interrupted.']);
    }
    exit;
}