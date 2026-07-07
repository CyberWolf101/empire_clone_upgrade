<?php
include "../connect.php";
if(isset($_POST["delete-ingredient"])){
    $id = $_POST["ingredient-id"];
    $query = "DELETE FROM special_items WHERE ingredient_id = '$id'";
    mysqli_query($con, $query);
}
header("Location: editfood.php?category={$_POST['category']}");