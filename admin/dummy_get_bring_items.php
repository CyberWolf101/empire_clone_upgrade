<?php

include "../connect.php";

// Read incoming JSON payload
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["training_id"])) {
    $trainingId = mysqli_real_escape_string($con, $data["training_id"]);
    
    // Select all items associated with this training ID
    $sql = "SELECT * FROM training_items_to_bring WHERE training_id = '$trainingId'";
    $res = mysqli_query($con, $sql);
    
    $resultToDisplay = [];
    
    // Correct loop syntax to fetch rows sequentially until empty
    while ($row = mysqli_fetch_assoc($res)) {
        $resultToDisplay[] = [
            // Map table keys to exactly what your JavaScript template expects
            "item_id" => $row["id"], // Ensure 'id' matches your table's primary key name
            "name"    => $row["item_name"]
        ];
    }
    
    // Output the data back to your JavaScript fetch call
    header('Content-Type: application/json');
    echo json_encode($resultToDisplay);

} else {
    echo json_encode([]);
}