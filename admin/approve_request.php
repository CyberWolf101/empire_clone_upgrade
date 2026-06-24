<?php
include "header.php";

// Require id
if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = "Missing request id.";
    header("Location: bakersrequests.php");
    exit();
}

$id = $_GET['id'];

// Only admin/superadmin can approve/reject
if (!isset($status) || ($status !== "superadmin" && $status !== "admin")) {
    $_SESSION['error_message'] = "Only admin can approve or reject requests.";
    header("Location: viewrequest.php?id={$id}");
    exit();
}

$reject = isset($_GET['reject']) && $_GET['reject'] == '1';
$action_status = $reject ? 'rejected' : 'approved';
$actor = mysqli_real_escape_string($con, $username ?? '');
$now = date("Y-m-d H:i:s");

// Update request: set status and approval metadata (if columns exist)
$update_sql = "UPDATE bakers_requests SET status='".mysqli_real_escape_string($con,$action_status)."', approved_by='$actor', approved_on='$now', approved_status='approved' WHERE id='$id'";
mysqli_query($con, $update_sql);

// Success message and redirect
$_SESSION['success_message'] = $reject ? "Request rejected." : "Request approved.";
header("Location: viewrequest.php?id={$id}");
exit();
?>