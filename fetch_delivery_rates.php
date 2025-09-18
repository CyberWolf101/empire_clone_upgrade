<?php
// fetch_delivery_rates.php - Fetch delivery rates as JSON
ob_start(); // Start output buffering
include "header.php"; // Assumes $con is defined

// Default rates
$rates = [
    'Below 5km' => 0,
    '5 - 10km' => 0,
    '10 - 20km' => 0,
    '20 - 50km' => 0,
    'Above 50km' => 0
];

// Check database connection
if (!isset($con) || !$con) {
    error_log("Database connection not defined or invalid in fetch_delivery_rates.php");
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection error']);
    exit;
}

$result = mysqli_query($con, "SELECT km_range, rate FROM delivery_rates");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rates[$row['km_range']] = (int) $row['rate'];
    }
    mysqli_free_result($result);
} else {
    error_log("Failed to fetch delivery rates: " . mysqli_error($con));
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to fetch delivery rates: Database error']);
    exit;
}

if (empty(array_filter($rates, fn($rate) => $rate > 0))) {
    error_log("No valid delivery rates found in database");
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No valid delivery rates configured']);
    exit;
}

ob_end_clean();
header('Content-Type: application/json');
echo json_encode($rates);
exit;
?>