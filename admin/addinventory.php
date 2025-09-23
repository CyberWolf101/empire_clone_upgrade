<?php

if (isset($_POST['register'])) {
    $pname = mysqli_real_escape_string($con, $_POST['name']);
    $productvalue = mysqli_real_escape_string($con, $_POST['product-value']);
    $packQ = (int) $_POST['pack-quantity'];
    $packs = (int) $_POST['packs'];
    $pieces = (int) $_POST['pieces'];
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $date = date("Y-m-d H:i:s");
    $quantity = $packs * $packQ + $pieces;

    $sql = "SELECT MAX(product) as max_product FROM chb_inventory";
    $sql2 = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($sql2);
    $n = $row["max_product"] ?: 100;
    $ran = $n + 1;

    // Insert into chb_inventory
    $submit = mysqli_query($con, "INSERT INTO chb_inventory(product, productname, product_value, pack_quantity, packs, pieces, inventory, department, staff, date) 
        VALUES ('$ran', '$pname', '$productvalue', '$packQ', '$packs', '$pieces', '$quantity', '$department', '$username', '$date')")
        or die('Could not connect: ' . mysqli_error($con));

    // Insert into chb_inventory_history
    // $submit = mysqli_query($con, "INSERT INTO chb_inventory_history(`product`, `productname`, `quantity`, `quantity_left`, `deducted_by`, `collected_by`, `action`, `date`) 
    //     VALUES ('$ran', '$pname', '$quantity', '$quantity', '$username', '$username', 'added', '$date')")
    //     or die('Could not connect: ' . mysqli_error($con));

    // Insert into chb_inventory_history
    $submit = mysqli_query($con, "INSERT INTO chb_inventory_history(
    `product`, `productname`, `quantity`, `quantity_left`, `total_left`,
    `deducted_by`, `collected_by`, `action`, `date`
) VALUES (
    '$ran', '$pname', '$quantity', '$quantity', '$quantity',
    '$username', '$username', 'added', '$date'
)") or die('Could not connect: ' . mysqli_error($con));


    // Insert into inventory_log
    $submit = mysqli_query($con, "INSERT INTO inventory_log(
        product_id, product_name, 
        new_productname, 
        new_pack_quantity, 
        new_packs, 
        new_pieces, 
        new_inventory, 
        new_department, 
        action, changed_by, change_date
    ) VALUES (
        '$ran', '$pname',
        '$pname',
        '$packQ',
        '$packs',
        '$pieces',
        '$quantity',
        '$department',
        'add', '$username', '$date'
    )") or die('Could not connect: ' . mysqli_error($con));

    $_SESSION['success_message'] = "Item '$pname' added to inventory successfully!";
    header("Location: inventory.php");
    exit();
}

// Delete
if (isset($_GET['categoryid'])) {
    $service_delete = mysqli_real_escape_string($con, $_GET['categoryid']);

    $del = mysqli_query($con, "DELETE FROM chb_inventory WHERE s='$service_delete'") or die('Could not connect: ' . mysqli_error($con));
    $_SESSION['success_message'] = "Inventory item deleted successfully!";
    header("Location: inventory.php");
    exit();
}

// Update Store

// Update Store
if (isset($_POST['update_store'])) {
    $pname = mysqli_real_escape_string($con, $_POST['name']);
    $packQ = (int) $_POST['pack-quantity'];
    $packsInput = $_POST['packs'] === '' ? null : (int) $_POST['packs'];
    $piecesInput = $_POST['pieces'] === '' ? null : (int) $_POST['pieces'];
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $date = date("Y-m-d H:i:s");

    // Fetch old values
    $sql = "SELECT productname, pack_quantity, packs, pieces, inventory, department 
            FROM chb_inventory WHERE product='$id'";
    $sql2 = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($sql2);
    $old_packs = $row['packs'];
    $old_pieces = $row['pieces'];
    $old_inventory = $row['inventory'];

    // Apply add/subtract modes
    $packs = $old_packs;
    if ($packsInput !== null) {
        $packs = ($_POST['packs_mode'] ?? 'add') === 'subtract'
            ? max(0, $old_packs - $packsInput)
            : $old_packs + $packsInput;
    }

    $pieces = $old_pieces;
    if ($piecesInput !== null) {
        $pieces = ($_POST['pieces_mode'] ?? 'add') === 'subtract'
            ? max(0, $old_pieces - $piecesInput)
            : $old_pieces + $piecesInput;
    }

    $total_quantity = $packs * $packQ + $pieces;

    // Update DB
    mysqli_query($con, "UPDATE chb_inventory SET 
        productname='$pname',
        pack_quantity='$packQ',
        packs='$packs',
        pieces='$pieces',
        inventory='$total_quantity',
        department='$department'
        WHERE product='$id'") or die('Update failed: ' . mysqli_error($con));

    // Record history
    $quantity_diff = $total_quantity - $old_inventory;
    if ($quantity_diff != 0) {
        $action = $quantity_diff > 0 ? 'added' : 'deducted';
        mysqli_query($con, "INSERT INTO chb_inventory_history(
            product, productname, quantity, quantity_left, total_left,
            deducted_by, collected_by, action, date
        ) VALUES (
            '$id', '$pname', '" . abs($quantity_diff) . "', '$total_quantity', '$total_quantity',
            '$username', '$username', '$action', '$date'
        )") or die('History insert failed: ' . mysqli_error($con));
    }

    $_SESSION['success_message'] = "Inventory for '$pname' updated successfully!";
    header("Location: inventory.php");
    exit();
}

