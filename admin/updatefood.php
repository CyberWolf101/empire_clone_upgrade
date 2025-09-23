<?php
if (isset($_POST['add'])) {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $quantity_change = $_POST['quantity_change'] ?? null; // Allow null/empty
    $type = $_POST['type'] ?? '';
    $action = $_POST['action'] ?? 'add';
    $statusMsg = '';

    // Validate inputs (quantity_change can be empty)
    if (empty($id) || empty($name) || $price <= 0 || empty($type)) {
        header("Location: editfood.php?category=" . urlencode($id) . "&error=" . urlencode("Name, price, and type are required"));
        exit;
    }

    // Get previous quantity from food_menu
    $prevQuantityQuery = "SELECT quantity FROM food_menu WHERE s = ?";
    $prevQuantityStmt = mysqli_prepare($con, $prevQuantityQuery);
    mysqli_stmt_bind_param($prevQuantityStmt, "s", $id);
    mysqli_stmt_execute($prevQuantityStmt);
    $prevQuantityResult = mysqli_stmt_get_result($prevQuantityStmt);
    $prevQuantity = mysqli_fetch_assoc($prevQuantityResult)['quantity'] ?? 0;
    mysqli_stmt_close($prevQuantityStmt);

    // Calculate new quantity if quantity_change is provided
    $newQuantity = $prevQuantity;
    if ($quantity_change !== null && $quantity_change !== '') {
        if ($quantity_change < 0) {
            header("Location: editfood.php?category=" . urlencode($id) . "&error=" . urlencode("Quantity change cannot be negative"));
            exit;
        }
        if (!in_array($action, ['add', 'subtract'])) {
            header("Location: editfood.php?category=" . urlencode($id) . "&error=" . urlencode("Invalid action"));
            exit;
        }
        $newQuantity = ($action == 'add') ? $prevQuantity + $quantity_change : $prevQuantity - $quantity_change;
        if ($newQuantity < 0) {
            header("Location: editfood.php?category=" . urlencode($id) . "&error=" . urlencode("Cannot subtract: insufficient stock"));
            exit;
        }
    }

    // File upload handling
    $fileName = null; // Default to null if no file is uploaded
    $targetDir = "../orishirishi/";
    if (!empty($_FILES["file"]["name"])) {
        // File is uploaded, process it
        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            error_log("Target directory $targetDir is not writable or does not exist");
            header("Location: editfood.php?category=" . urlencode($id) . "&error=" . urlencode("Server error: Image upload directory is not accessible"));
            exit;
        }

        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowTypes = ['jpg', 'png', 'jpeg', 'gif', 'pdf'];

        if (!in_array($fileType, $allowTypes)) {
            header("Location: editfood.php?category=" . urlencode($id) . "&error=" . urlencode("Only JPG, JPEG, PNG, GIF, & PDF files are allowed"));
            exit;
        }

        // Upload file to server
        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            error_log("Failed to upload file $fileName to $targetFilePath");
            header("Location: editfood.php?category=" . urlencode($id) . "&error=" . urlencode("Error uploading file. Please try again"));
            exit;
        }

        // Insert into images table
        $insertImage = mysqli_prepare($con, "INSERT INTO images (file_name, input, uploaded_on) VALUES (?, ?, NOW())");
        $input = 'staff';
        mysqli_stmt_bind_param($insertImage, "ss", $fileName, $input);
        if (!mysqli_stmt_execute($insertImage)) {
            error_log("Failed to insert into images table: " . mysqli_error($con));
            header("Location: editfood.php?category=" . urlencode($id) . "&error=" . urlencode("Database error: Failed to save image details"));
            exit;
        }
        mysqli_stmt_close($insertImage);
    }

    // Update food_menu table
    $updateFoodQuery = "UPDATE food_menu SET item = ?, price = ?, type = ?, quantity = ?, file_name = COALESCE(?, file_name) WHERE s = ?";
    $updateFoodStmt = mysqli_prepare($con, $updateFoodQuery);
    mysqli_stmt_bind_param($updateFoodStmt, "sdsssi", $name, $price, $type, $newQuantity, $fileName, $id);
    if (!mysqli_stmt_execute($updateFoodStmt)) {
        error_log("Failed to update food_menu table: " . mysqli_error($con));
        header("Location: editfood.php?category=" . urlencode($id) . "&error=" . urlencode("Database error: Failed to update item"));
        exit;
    }
    mysqli_stmt_close($updateFoodStmt);

    // Handle stock change in stock_transactions if quantity changed
    if ($quantity_change !== null && $quantity_change !== '' && $quantity_change > 0) {
        // Set total_left to the new quantity
        $totalLeft = $newQuantity;

        // Insert into stock_transactions table
        $datetime = date('Y-m-d H:i:s');
        $insertStock = mysqli_prepare($con, "INSERT INTO stock_transactions (item, quantity, action, date, total_left) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insertStock, "sissi", $name, $quantity_change, $action, $datetime, $totalLeft);
        if (!mysqli_stmt_execute($insertStock)) {
            error_log("Failed to insert into stock_transactions table: " . mysqli_error($con));
            header("Location: editfood.php?category=" . urlencode($id) . "&error=" . urlencode("Database error: Failed to log stock"));
            exit;
        }
        mysqli_stmt_close($insertStock);
    }

    // Success: Redirect with success message
    header("Location: foodmenu.php?success=" . urlencode("Item '$name' updated successfully!"));
    exit;
}
?>