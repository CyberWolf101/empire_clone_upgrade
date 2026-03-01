<?php 
if (isset($_POST['register'])) {

    $staff_deduct = mysqli_real_escape_string($con, $_POST['deducted_by']);
    $product = mysqli_real_escape_string($con, $_POST['product']);
    $staff_given = mysqli_real_escape_string($con, $_POST['given_to']);
    $quantity = (int)$_POST['quantity'];
    $date = date("Y-m-d H:i:s");

    // Ensure required columns exist
    function ensureColumnExists($con, $table, $column, $definition) {
        $check = mysqli_query($con, "SHOW COLUMNS FROM `$table` LIKE '$column'");
        if (mysqli_num_rows($check) == 0) {
            mysqli_query($con, "ALTER TABLE `$table` ADD `$column` $definition");
        }
    }

    ensureColumnExists($con, 'chb_inventory_history', 'deducted_by', 'VARCHAR(255) NOT NULL');
    ensureColumnExists($con, 'chb_inventory_history', 'collected_by', 'VARCHAR(255) NOT NULL');
    ensureColumnExists($con, 'chb_inventory_history', 'total_left', 'INT NOT NULL DEFAULT 0');

    // Fetch product details
    $sql = "SELECT * FROM chb_inventory WHERE product='$product'";
    $sql2 = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($sql2);

    $productname = $row["productname"];
    $inventory = $row["inventory"];
    $packQ = $row["pack_quantity"];

    if ($quantity <= 0) {
        die("Invalid quantity.");
    }

    if ($quantity > $inventory) {
        die("Not enough stock available.");
    }

    // Calculate new inventory
    $inventory_left = $inventory - $quantity;
    $packs = intdiv($inventory_left, $packQ);
    $pieces = $inventory_left % $packQ;

    // Calculate total_left from history
    $history_sql = "SELECT 
                        SUM(CASE WHEN action = 'added' THEN quantity ELSE 0 END) AS total_inventory,
                        SUM(CASE WHEN action = 'deducted' THEN quantity ELSE 0 END) AS total_deducted
                    FROM chb_inventory_history 
                    WHERE product = '$product'";
    $history_result = mysqli_query($con, $history_sql);
    $history_row = mysqli_fetch_assoc($history_result);

    $total_inventory = $history_row['total_inventory'] ?: 0;
    $total_deducted = $history_row['total_deducted'] ?: 0;

    $total_left = $total_inventory - ($total_deducted + $quantity);

    // Insert into history
    mysqli_query($con, "INSERT INTO chb_inventory_history
        (`product`, `productname`, `quantity`, `quantity_left`, 
         `deducted_by`, `collected_by`, `action`, `date`, `total_left`)
        VALUES
        ('$product', '$productname', '$quantity', '$inventory_left',
         '$staff_deduct', '$staff_given', 'deducted', '$date', '$total_left')")
        or die ('Could not connect: ' . mysqli_error($con));

    // Update inventory
    mysqli_query($con, "UPDATE chb_inventory 
        SET packs='$packs', pieces='$pieces', inventory='$inventory_left' 
        WHERE product='$product'")
        or die ('Could not connect: ' . mysqli_error($con));

    echo "<script>alert('Inventory logged successfully!'); window.location.href = 'inventory_system.php';</script>";
}
?>