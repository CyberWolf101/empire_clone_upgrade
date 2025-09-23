<?php

if (isset($_COOKIE['orderID'])) {
    $saloon = $_COOKIE['orderID'];
    $sql = "SELECT * FROM saloon_orders WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $saloon);
    $stmt->execute();
    $sql2 = $stmt->get_result();
    while ($row = $sql2->fetch_assoc()) {
        $type = $row["bookingtype"];
        $kit = $row["saloonkit"];
        $username = $row["name"];
        $transaction_name = $row["transaction_name"]; // ✅ add this
    }
    $stmt->close();
} else {
    header("location:startorder.php");
    exit();
}

if (isset($_POST['save_for_later'])) {
    $transactionName = trim($_POST['transaction_name']);

    if (!empty($transactionName) && isset($saloon)) {
        $stmt = $con->prepare("UPDATE saloon_orders 
                               SET transaction_name = ?, is_awaiting = 1 
                               WHERE id = ?");
        $stmt->bind_param("ss", $transactionName, $saloon);
        $stmt->execute();
        $stmt->close();

        // Remove the cookie
        setcookie("orderID", "", time() - 3600, "", "", true, true); // No path
        $_SESSION['success_message'] = "Order was saved";

        // Redirect back to orderfood.php
        header("Location: orderfood.php");
        exit();
    }
}


// Count awaiting orders
$awaiting_count = 0;
$stmt = $con->prepare("SELECT COUNT(*) as total FROM saloon_orders WHERE is_awaiting = 1 AND status != 'completed'");
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $awaiting_count = (int) $row['total'];
}
$stmt->close();

// get awaiting
$awaiting_orders = [];
$stmt = $con->prepare("SELECT id, transaction_name FROM saloon_orders WHERE is_awaiting = 1 AND status != 'completed'");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $awaiting_orders[] = $row;
}
$stmt->close();


// Handle download (resume order)
if (isset($_POST['download_orderid'])) {
    $downloadId = $_POST['download_orderid'];

    // Set the cookie to new order id
    setcookie("orderID", $downloadId, time() + (86400 * 7), "", "", true, true);

    // Redirect to refresh page so user continues order
    header("Location: orderfood.php");
    exit();
}

// Handle delete
if (isset($_POST['delete_orderid'])) {
    $deleteId = $_POST['delete_orderid'];

    // Delete order
    $stmt = $con->prepare("DELETE FROM saloon_orders WHERE id = ?");
    $stmt->bind_param("s", $deleteId);
    $stmt->execute();
    $stmt->close();

    // Delete related cart items
    $stmt = $con->prepare("DELETE FROM refreshments WHERE orderid = ?");
    $stmt->bind_param("s", $deleteId);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success_message'] = "Order and its items deleted successfully.";

    header("Location: orderfood.php");
    exit();
}


// Delete item from cart
if (isset($_POST['categoryid']) && isset($_POST['delete'])) {
    $service_delete = $_POST['categoryid'];

    // Use prepared statement
    $stmt = $con->prepare("DELETE FROM refreshments WHERE s = ?");
    $stmt->bind_param("s", $service_delete);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Item deleted successfully.');</script>";
            header("Location: orderfood.php");

        } else {
            echo "<script>alert('No item found with the provided ID.');</script>";
            header("Location: orderfood.php");

        }
    } else {
        echo "<script>alert('Error deleting item: " . addslashes($stmt->error) . "');</script>";
        header("Location: orderfood.php");

    }

    $stmt->close();
    exit();
}