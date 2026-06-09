<?php

include "../connect.php";

$data = json_decode(file_get_contents("php://input"),true);
$itemId = $data["item_id"];
$trainingId = $data["training_id"];
$query = "DELETE FROM training_items WHERE item_id = '$itemId' AND training_id = '$trainingId'";
mysqli_query($con, $query);
echo json_encode([
    "status"=>"success"
]);