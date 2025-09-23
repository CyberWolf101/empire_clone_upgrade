<?php
if (!isset($con)) {
    error_log("Database connection not established in add_food.php");
    header("Location: addfood.php?error=" . urlencode("Database connection failed"));
    exit;
}

// Create images table if it doesn't exist
$createImagesQuery = "
    CREATE TABLE IF NOT EXISTS images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        file_name VARCHAR(255) NOT NULL,
        input VARCHAR(50) NOT NULL,
        uploaded_on DATETIME NOT NULL
    ) ENGINE=InnoDB";
if (!mysqli_query($con, $createImagesQuery)) {
    error_log("Failed to create images table: " . mysqli_error($con));
    header("Location: addfood.php?error=" . urlencode("Error setting up images table: " . mysqli_error($con)));
    exit;
}


if (isset($_POST['add'])) {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;
    $type = $_POST['type'] ?? '';
    $statusMsg = '';

    // Validate inputs
    if (empty($name) || $price <= 0 || $quantity < 0 || empty($type)) {
        header("Location: addfood.php?error=" . urlencode("All fields are required and must be valid"));
        exit;
    }

    // File upload handling
    $fileName = null; // Default to null if no file is uploaded
    $targetDir = "../orishirishi/";
    if (!empty($_FILES["file"]["name"])) {
        // File is uploaded, process it
        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            error_log("Target directory $targetDir is not writable or does not exist");
            header("Location: addfood.php?error=" . urlencode("Server error: Image upload directory is not accessible"));
            exit;
        }

        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowTypes = ['jpg', 'png', 'jpeg', 'gif', 'pdf'];

        if (!in_array($fileType, $allowTypes)) {
            header("Location: addfood.php?error=" . urlencode("Only JPG, JPEG, PNG, GIF, & PDF files are allowed"));
            exit;
        }

        // Upload file to server
        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            error_log("Failed to upload file $fileName to $targetFilePath");
            header("Location: addfood.php?error=" . urlencode("Error uploading file. Please try again"));
            exit;
        }

        // Insert into images table
        $insertImage = mysqli_prepare($con, "INSERT INTO images (file_name, input, uploaded_on) VALUES (?, ?, NOW())");
        $input = 'staff';
        mysqli_stmt_bind_param($insertImage, "ss", $fileName, $input);
        if (!mysqli_stmt_execute($insertImage)) {
            error_log("Failed to insert into images table: " . mysqli_error($con));
            header("Location: addfood.php?error=" . urlencode("Database error: Failed to save image details"));
            exit;
        }
        mysqli_stmt_close($insertImage);
    }

    // Insert into food_menu table
    $insertFood = mysqli_prepare($con, "INSERT INTO food_menu (item, price, type, file_name, quantity) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($insertFood, "sdssi", $name, $price, $type, $fileName, $quantity);
    if (!mysqli_stmt_execute($insertFood)) {
        error_log("Failed to insert into food_menu table: " . mysqli_error($con));
        header("Location: addfood.php?error=" . urlencode("Database error: Failed to add item"));
        exit;
    }
    mysqli_stmt_close($insertFood);

    // Calculate total_left for stock_transactions
    $totalLeftQuery = "SELECT SUM(CASE WHEN action = 'add' THEN quantity ELSE -quantity END) AS total_left 
                       FROM stock_transactions 
                       WHERE item = ?";
    $totalLeftStmt = mysqli_prepare($con, $totalLeftQuery);
    mysqli_stmt_bind_param($totalLeftStmt, "s", $name);
    mysqli_stmt_execute($totalLeftStmt);
    $totalLeftResult = mysqli_stmt_get_result($totalLeftStmt);
    $totalLeft = mysqli_fetch_assoc($totalLeftResult)['total_left'] ?? 0;
    mysqli_stmt_close($totalLeftStmt);

    // Add the current quantity to total_left
    $totalLeft = $totalLeft + $quantity;

    
    // Insert into stock_transactions table
    $datetime = date('Y-m-d H:i:s');
    $insertStock = mysqli_prepare($con, "INSERT INTO stock_transactions (item, quantity, action, date, total_left) VALUES (?, ?, ?, ?, ?)");
    $action = 'add';
    mysqli_stmt_bind_param($insertStock, "sissi", $name, $quantity, $action, $datetime, $totalLeft);
    if (!mysqli_stmt_execute($insertStock)) {
        error_log("Failed to insert into stock_transactions table: " . mysqli_error($con));
        header("Location: addfood.php?error=" . urlencode("Database error: Failed to log stock"));
        exit;
    }
    mysqli_stmt_close($insertStock);

    // Success: Redirect with success message
    header("Location: addfood.php?success=" . urlencode("Item '$name' added successfully!"));
    exit;
}
?>