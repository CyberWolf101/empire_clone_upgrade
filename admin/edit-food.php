<?php
include "../connect.php";

$itemId = mysqli_real_escape_string($con, $_POST["id"] ?? "");

/*
|--------------------------------------------------------------------------
| ADD INGREDIENT
|--------------------------------------------------------------------------
*/
if (isset($_POST["add-ingredient"])) {

    $quantity = trim($_POST["quantity"] ?? "");
    $ingredientId = trim($_POST["item_id"] ?? "");

    // Validation
    if (empty($itemId) || empty($ingredientId) || empty($quantity)) {
        echo "<script>
                alert('Please fill in all required fields.');
                history.back();
              </script>";
        exit;
    }

    if (!is_numeric($quantity) || $quantity <= 0) {
        echo "<script>
                alert('Quantity must be greater than zero.');
                history.back();
              </script>";
        exit;
    }

    // Fetch selected menu item
    $itemQuery = mysqli_query($con, "SELECT item, type FROM food_menu WHERE s='$itemId'");

    if (mysqli_num_rows($itemQuery) == 0) {
        die("Food item not found.");
    }

    $item = mysqli_fetch_assoc($itemQuery);

    // Fetch ingredient
    $ingredientQuery = mysqli_query($con, "SELECT item FROM food_menu WHERE s='$ingredientId'");

    if (mysqli_num_rows($ingredientQuery) == 0) {
        die("Ingredient not found.");
    }

    $ingredient = mysqli_fetch_assoc($ingredientQuery);

    $itemName = mysqli_real_escape_string($con, $item["item"]);
    $category = mysqli_real_escape_string($con, $item["type"]);
    $ingredientName = mysqli_real_escape_string($con, $ingredient["item"]);

    // Prevent duplicate ingredient for the same item
    $check = mysqli_query(
        $con,
        "SELECT 1
         FROM special_items
         WHERE item_id='$itemId'
         AND ingredient_id='$ingredientId'
         LIMIT 1"
    );

    if (mysqli_num_rows($check) > 0) {
        echo "<script>
                alert('This ingredient has already been added.');
                history.back();
              </script>";
        exit;
    }

    // Insert
    $insert = "
        INSERT INTO special_items
        (
            item,
            item_id,
            category,
            ingredient_id,
            ingredient_name,
            ingredient_quantity
        )
        VALUES
        (
            '$itemName',
            '$itemId',
            '$category',
            '$ingredientId',
            '$ingredientName',
            '$quantity'
        )
    ";

    if (!mysqli_query($con, $insert)) {
        die(mysqli_error($con));
    }

    header("Location: editfood.php?category=$itemId");
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE INGREDIENT
|--------------------------------------------------------------------------
*/
if (isset($_POST["delete-ingredient"])) {

    $ingredientId = mysqli_real_escape_string($con, $_POST["ingredient-id"] ?? "");

    if (empty($ingredientId) || empty($itemId)) {
        echo "<script>
                alert('Invalid request.');
                history.back();
              </script>";
        exit;
    }

    // Delete only the ingredient belonging to this item
    $delete = "
        DELETE FROM special_items
        WHERE ingredient_id='$ingredientId'
        AND item_id='$itemId'
    ";

    if (!mysqli_query($con, $delete)) {
        die(mysqli_error($con));
    }

    header("Location: editfood.php?category=$itemId");
    exit;
}