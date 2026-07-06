<?php
include "../connect.php";

// 1. Ensure the request is a POST and check for our toggle flag
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle-special-status'])) {
    
    // Sanitize the item ID
    $itemId = mysqli_real_escape_string($con, $_POST["id"] ?? "");

    // 2. Check if the checkbox was ticked. 
    // If it is checked, $_POST['is_special'] exists. If unchecked, it is completely absent from $_POST.
    if (isset($_POST['is_special']) && $_POST['is_special'] === 'true') {
        $menuStatus = 'true';
        $specialStatus = 'active';
    } else {
        $menuStatus = 'false';
        $specialStatus = 'inactive';
    }

    // 3. Update the food_menu table
    $sql = "UPDATE food_menu SET special_item='$menuStatus' WHERE s='$itemId'";
    if (!mysqli_query($con, $sql)) {
        die(mysqli_error($con));
    }

    // 4. Update the special_items table
    $newSQL = "UPDATE special_items SET status='$specialStatus' WHERE item_id='$itemId'";
    if (!mysqli_query($con, $newSQL)) {
        die(mysqli_error($con));
    }
}

// 5. Redirect back to the edit page with the category ID
$redirectId = urlencode($_POST["id"] ?? "");
header("Location: editfood.php?category=$redirectId");
exit;