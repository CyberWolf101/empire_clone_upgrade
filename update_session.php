<?php
include "header.php";
header('Content-Type: application/json');

if (isset($_POST['shipping_type'])) {
    $_SESSION['shipping_type'] = $_POST['shipping_type'];
    $_SESSION['shipping_fee'] = isset($_POST['shipping_fee']) ? (int) $_POST['shipping_fee'] : 0;
    $_SESSION['selected_place'] = isset($_POST['selected_place']) ? $_POST['selected_place'] : '';
    error_log("update_session.php: Updated session - shipping_type: {$_POST['shipping_type']}, shipping_fee: {$_SESSION['shipping_fee']}, selected_place: {$_SESSION['selected_place']}");
    echo json_encode(['status' => 'success']);
} else {
    error_log("update_session.php: Invalid request, no shipping_type provided");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
exit;
?>