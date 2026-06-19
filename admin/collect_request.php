<?php
include "header.php";

if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = "Missing request id.";
    header("Location: bakersrequests.php");
    exit();
}

$id = (int) $_GET['id'];

// Fetch current status
$r = mysqli_query($con, "SELECT status FROM bakers_requests WHERE id='$id' LIMIT 1");
$row = mysqli_fetch_assoc($r);
if (!$row) {
    $_SESSION['error_message'] = "Request not found.";
    header("Location: bakersrequests.php");
    exit();
}

$current = strtolower($row['status']);
$isAdmin = isset($status) && ($status === "superadmin" || $status === "admin");

// Require approval before non-admin can collect
if ($current !== 'approved' && !$isAdmin) {
    $_SESSION['error_message'] = "Request must be approved by admin before marking as collected.";
    header("Location: viewrequest.php?id={$id}");
    exit();
}

// Mark as completed
$update_sql = "UPDATE bakers_requests SET status='completed' WHERE id='$id'";
mysqli_query($con, $update_sql);

// Optionally set collected_by/collected_on if those columns exist
// (safe to attempt if columns present in schema)
$actor = mysqli_real_escape_string($con, $username ?? '');
$now = date("Y-m-d H:i:s");
if (function_exists('columnExists') && columnExists($con, 'bakers_requests', 'collected_by')) {
    mysqli_query($con, "UPDATE bakers_requests SET collected_by='$actor', collected_on='$now' WHERE id='$id'");
}

$_SESSION['success_message'] = "Request marked as collected.";
header("Location: viewrequest.php?id={$id}");
exit();
?>