<?php
include "../connect.php";

$itemId = mysqli_real_escape_string($con, $_POST["id"] ?? "");

if (isset($_POST["make-item-special"])) {

    $sql = "UPDATE food_menu SET special_item='true' WHERE s='$itemId'";

    if (!mysqli_query($con, $sql)) {
        die(mysqli_error($con));
    }

    $newSQL = "UPDATE special_items SET status='active' WHERE item_id='$itemId'";

    if (!mysqli_query($con, $newSQL)) {
        die(mysqli_error($con));
    }
}

if (isset($_POST["make-as-unspecial-item"])) {

    $sql = "UPDATE food_menu SET special_item='false' WHERE s='$itemId'";

    if (!mysqli_query($con, $sql)) {
        die(mysqli_error($con));
    }

    $newSQL = "UPDATE special_items SET status='inactive' WHERE item_id='$itemId'";

    if (!mysqli_query($con, $newSQL)) {
        die(mysqli_error($con));
    }
}

header("Location: editfood.php?category=$itemId");
exit;