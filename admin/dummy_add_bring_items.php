<?php

include "../connect.php";

// 1. Read input data safely
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $trainingId = mysqli_real_escape_string($con, $data["training_id"]);
    
    // 2. Decode the 'data' array passed from JavaScript
    $items = json_decode($data["data"], true);

    // 3. Loop through items since the JavaScript passes an array of objects
    foreach ($items as $item) {
        $itemName = mysqli_real_escape_string($con, $item["name"]);
        
        // 4. Correct SQL syntax for INSERT INTO
        $sql = "INSERT INTO training_items_to_bring (item_name, training_id) VALUES ('$itemName', '$trainingId')";
        mysqli_query($con, $sql);
    }

    // 5. Send back success response
    echo json_encode([
        "status" => true
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Invalid data received"
    ]);
}