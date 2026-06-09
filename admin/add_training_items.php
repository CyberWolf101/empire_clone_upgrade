<?php

include "../connect.php";

$decode = json_decode(file_get_contents("php://input"), true);
$data = json_decode($decode["data"], true);

$result = [
    "status" => "",
    "message" => ""
];
if (!empty($data)) {
    foreach ($data as $field) {
        $name = $field["name"];
        $price = $field["price"];
        $trainingId = $decode["training_id"];
        $newQuery = "SELECT * FROM training_items WHERE name = '$name' AND training_id = '$trainingId'";
        $res = mysqli_query($con, $newQuery);
        if (!(mysqli_fetch_array($res))) {
            $sqlToQuery = "INSERT INTO training_items(name,price,item_id,training_id) VALUES ('$name','$price','$name','$trainingId')";
            if (mysqli_query($con, $sqlToQuery)) {
                $result["status"] = true;
                $result["message"] = "Training item(s) added successfully";
            }
        } else {
            $result["status"] = false;
            $result["message"] = "Training item(s) already exists";
        }
    }
}
echo json_encode($result);
