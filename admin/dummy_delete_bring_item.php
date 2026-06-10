<?php

include "../connect.php";

// Read the incoming JSON request payload
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["item_id"]) && isset($data["training_id"])) {
    // Escape inputs to protect against SQL Injection
    $itemId = mysqli_real_escape_string($con, $data["item_id"]);
    $trainingId = mysqli_real_escape_string($con, $data["training_id"]);
    
    // NOTE: Change 'id' to whatever your primary key column name is if it isn't 'id'
    $sql = "DELETE FROM training_items_to_bring WHERE item_name = '$itemId' AND training_id = '$trainingId'";
    
    if (mysqli_query($con, $sql)) {
        echo json_encode([
            "status" => true,
            "message" => "Item deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Database error: " . mysqli_error($con)
        ]);
    }
} else {
    echo json_encode([
        "status" => false,
        "message" => "Invalid parameters provided"
    ]);
}