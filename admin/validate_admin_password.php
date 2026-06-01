<?php
header('Content-Type: application/json');
include "../connect.php";

$input = json_decode(file_get_contents('php://input'), true);
$password = trim($input['password'] ?? '');

if ($password === '') {
    echo json_encode(['status' => false, 'valid' => false, 'message' => 'Admin password is required.']);
    exit();
}

$stmt = $con->prepare("SELECT COUNT(*) AS valid_admin FROM admin WHERE password = ? AND status IN ('superadmin', 'subadmin')");
$stmt->bind_param('s', $password);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

$valid = isset($row['valid_admin']) && (int)$row['valid_admin'] > 0;

echo json_encode([
    'status' => true,
    'valid' => $valid,
    'message' => $valid ? 'Valid admin password.' : 'Invalid admin password. Credit override denied.'
]);
