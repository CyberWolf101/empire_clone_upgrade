<?php
session_start();
include "../connect.php"; // Adjust file mapping path to match your layout environment

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guide_id = $_POST['guide_id'];
    $item_id = mysqli_real_escape_string($con, $_POST['item_id']);
    $request_qty = $_POST['quick_request_qty'];
    $requested_by = $_SESSION['adminname'] ?? 'System User'; // Adjust session key to your logged profile variable

    if ($request_qty <= 0) {
        $_SESSION['error'] = "Please enter a valid positive quantity request entry.";
        header("Location: viewbakersguide.php?id=" . $guide_id);
        exit;
    }

    // 1. Generate unique alphanumeric request identifier token strings
    $request_code = "REQ-" . strtoupper(uniqid());

    // 2. Insert into the parent tracking table bakers_requests
    $parent_query = "INSERT INTO bakers_requests
(
    request_code,
    guide_id,
    requested_by,
    status,
    approved_status
)
VALUES
(
    '$request_code',
    '$guide_id',
    '$requested_by',
    'pending',
    'pending'
)";
    
    if (mysqli_query($con, $parent_query)) {
        $new_request_id = mysqli_insert_id($con);
        
        // 3. Insert specific active breakdown items requested into subtable 
        $item_query = "INSERT INTO bakers_request_items (request_id, item_id, quantity, collected_quantity) 
                       VALUES ('$new_request_id', '$item_id', '$request_qty', 0)";
        
        if (mysqli_query($con, $item_query)) {
            $_SESSION['success'] = "Inventory Request standard tracking token pipeline logged successfully!";
        } else {
            $_SESSION['error'] = "Failed to log individual breakdown item target keys.";
        }
    } else {
        $_SESSION['error'] = "Database Parent Write Pipeline Interrupted.";
    }
    
    header("Location: viewbakersguide.php?id=" . $guide_id);
    exit;
}