<?php
include "../connect.php";
$id = $_GET["id"];
$searchQuery = "SELECT * FROM bakers_requests WHERE id = '$id'";
$query = mysqli_query($con, $searchQuery);
if(mysqli_num_rows($query) > 0) {
    $deleteQuery = "DELETE FROM bakers_requests WHERE id = '$id'";
    $itemsDelete = "DELETE FROM bakers_request_items WHERE request_id = '$id'";
    mysqli_query($con, $deleteQuery);
    mysqli_query($con, $itemsDelete);
}
header("Location: bakersrequests.php");