<?php
include "../connect.php";
$data = json_decode(file_get_contents("php://input"), true);
$customerKey = isset($data["customer_id"]) ? $data["customer_id"] : (isset($data["name"]) ? $data["name"] : '');
$query = "SELECT credit_sales_eligibility FROM customers WHERE unique_id = '" . mysqli_real_escape_string($con, $customerKey) . "' OR name = '" . mysqli_real_escape_string($con, $customerKey) . "' LIMIT 1";
$stmt = mysqli_query($con, $query);
$response = [
    "status" => false,
    "eligible" => false
];
if ($stmt && $row = mysqli_fetch_assoc($stmt)) {
    $response["status"] = true;
    $eligibility = $row["credit_sales_eligibility"];
    $response["eligible"] = in_array(strtolower(trim((string)$eligibility)), ['1', 'yes', 'true', 'eligible'], true);
}
echo json_encode($response);