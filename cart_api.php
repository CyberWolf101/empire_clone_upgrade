<?php
include "connect.php"; // make sure this connects $con
session_start();

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$orderid = mysqli_real_escape_string($con, $_POST['orderid'] ?? '');
$itemid = (int)($_POST['itemid'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($action === 'add' || isset($_POST['addtocart'])) {
    if (empty($orderid) || $itemid === 0) {
        $response['message'] = 'Invalid order ID or item ID';
        echo json_encode($response);
        exit;
    }
    $res = mysqli_query($con, "SELECT item, price, type FROM food_menu WHERE s='$itemid'");
    if ($row = mysqli_fetch_assoc($res)) {
        $itemName = $row['item'];
        $itemPrice = (float)$row['price'];
        $itemCategory = $row['type'];
        $check = mysqli_query($con, "SELECT quantity FROM refreshments WHERE orderid='$orderid' AND itemid='$itemid' AND status='processing'");
        if ($exist = mysqli_fetch_assoc($check)) {
            $newQty = $exist['quantity'] + $qty;
            $totalValue = $newQty * $itemPrice;
            $query = "UPDATE refreshments SET quantity='$newQty', unitprice='$itemPrice', totalprice='$totalValue' WHERE orderid='$orderid' AND itemid='$itemid' AND status='processing'";
            if (!mysqli_query($con, $query)) {
                $response['message'] = 'Update failed: ' . mysqli_error($con);
                error_log("Cart API update failed: " . mysqli_error($con) . " | Query: $query");
            } else {
                $_SESSION['cartItems'][$orderid][$itemid]['quantity'] = $newQty;
                $response['status'] = 'ok';
                $response['message'] = 'Item updated in cart';
            }
        } else {
            $totalValue = $qty * $itemPrice;
            $query = "INSERT INTO refreshments(orderid,itemid,item,unitprice,quantity,totalprice,status,item_category) VALUES ('$orderid','$itemid','$itemName','$itemPrice','$qty','$totalValue','processing','$itemCategory')";
            if (!mysqli_query($con, $query)) {
                $response['message'] = 'Insert failed: ' . mysqli_error($con);
                error_log("Cart API insert failed: " . mysqli_error($con) . " | Query: $query");
            } else {
                $_SESSION['cartItems'][$orderid][$itemid] = ['quantity' => $qty, 'preorder' => 0];
                $response['status'] = 'ok';
                $response['message'] = 'Item added to cart';
            }
        }
    } else {
        $response['message'] = 'Item not found';
        error_log("Cart API error: Item not found for itemid=$itemid");
    }
}

if ($action === 'delete' && $orderid && $itemid) {
    $query = "DELETE FROM refreshments WHERE orderid='$orderid' AND itemid='$itemid' AND status='processing'";
    if (!mysqli_query($con, $query)) {
        $response['message'] = 'Delete failed: ' . mysqli_error($con);
        error_log("Cart API delete failed: " . mysqli_error($con) . " | Query: $query");
    } else {
        unset($_SESSION['cartItems'][$orderid][$itemid]);
        $response['status'] = 'ok';
        $response['message'] = 'Item removed from cart';
    }
}

if ($action === 'fetch' && $orderid) {
    $cart = [];
    $res = mysqli_query($con, "SELECT itemid, item, unitprice as price, quantity FROM refreshments WHERE orderid='$orderid' AND status='processing'");
    while ($row = mysqli_fetch_assoc($res)) {
        $cart[] = $row;
    }
    $response['status'] = 'ok';
    $response['cart'] = $cart;
}

echo json_encode($response);
?>