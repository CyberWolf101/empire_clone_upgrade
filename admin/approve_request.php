<?php
include "header.php";

// 1. Ensure the target master identifier is present
if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = "Missing request id.";
    header("Location: bakersrequests.php");
    exit();
}

$id = mysqli_real_escape_string($con, $_GET['id']);

// 2. Validate actor boundary credentials matching your configuration definitions
// Note: Changed from $status to handle explicit session array keys securely if needed
if (!isset($status) || ($status !== "superadmin" && $status !== "admin")) {
    $_SESSION['error_message'] = "Only admin can approve or reject requests.";
    header("Location: viewrequest.php?id={$id}");
    exit();
}

// 3. Determine action intention paths
$reject = isset($_GET['reject']) && $_GET['reject'] == '1';

// Synchronize status matching rules for parent vs child subtables
$action_status   = $reject ? 'rejected' : 'approved'; 
$approved_status = $reject ? 'rejected' : 'approved'; 

$actor = mysqli_real_escape_string($con, $username ?? 'Admin');
$now   = date("Y-m-d H:i:s");

/*
|--------------------------------------------------------------------------
| UPDATE PARENT ORDER TICKET TRACKING METRICS
|--------------------------------------------------------------------------
*/
$update_sql = "UPDATE bakers_requests 
               SET status = '$action_status', 
                   approved_by = '$actor', 
                   approved_on = '$now', 
                   approved_status = '$approved_status' 
               WHERE id = '$id'";

if (mysqli_query($con, $update_sql)) {
    
    /*
    |--------------------------------------------------------------------------
    | CRITICAL FIX: MAP AND FLIP INDIVIDUAL CHILD BREAKDOWN ROWS CORREKLY
    |--------------------------------------------------------------------------
    | We need to target the request_id column mapping directly to your schema.
    | If your bakers_request_items table doesn't have a status column, this protects
    | data synchronization consistency on clean full page loads.
    */
    $items_sql = "UPDATE bakers_request_items 
                  SET status = '$action_status' 
                  WHERE request_id = '$id'";
                  
    mysqli_query($con, $items_sql);

    $_SESSION['success_message'] = $reject ? "Request rejected successfully." : "Request approved successfully.";
} else {
    $_SESSION['error_message'] = "Database Transaction Error: Master state update interrupted.";
}

header("Location: viewrequest.php?id={$id}");
exit();
?>