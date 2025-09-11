<?php
header('Content-Type: application/json');
include 'connect.php'; // Ensure database connection ($con) is included

// Test database connection
if (!isset($con) || !mysqli_ping($con)) {
    error_log("Search API: Database connection failed");
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$search = isset($_POST['search']) ? mysqli_real_escape_string($con, trim($_POST['search'])) : '';
$response = [];

if (!empty($search)) {
    $sql = "SELECT item FROM food_menu WHERE item LIKE '%$search%' ORDER BY item LIMIT 5";
    $result = mysqli_query($con, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $response[] = ['item' => $row['item']];
        }
    } else {
        error_log("Search API query failed: " . mysqli_error($con) . " | Query: $sql");
        echo json_encode(['error' => 'Query failed']);
        exit;
    }
} else {
    error_log("Search API: Empty search query");
}

echo json_encode($response);
mysqli_close($con);
?>