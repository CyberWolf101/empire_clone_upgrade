<?php

include "../connect.php";
$trainingId = json_decode(file_get_contents("php://input"), true)["training_id"];
$query = "SELECT * FROM training_items WHERE training_id = '$trainingId'";
$res = mysqli_query($con, $query);
$trainingItems = [];
while ($row = mysqli_fetch_array($res)) {
    $trainingItems[] = $row;
}
echo json_encode($trainingItems);
