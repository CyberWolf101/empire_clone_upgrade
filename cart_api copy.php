
<?php
include "connect.php"; // make sure this connects $con

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$orderid = mysqli_real_escape_string($con, $_POST['orderid'] ?? '');
$itemid = (int) ($_POST['itemid'] ?? 0);
$qty = (int) ($_POST['qty'] ?? 1);

$response = ['status' => 'error'];

// if ($action === 'add' && $orderid && $itemid) {
if ((!$action && isset($_POST['addtocart'])) || $action === 'add') {
    // get item details
    $res = mysqli_query($con, "SELECT * FROM food_menu WHERE s='$itemid'");
    if ($row = mysqli_fetch_assoc($res)) {
        $itemName = $row['item'];
        $itemPrice = (float) $row['price'];
$itemCategory = $row["type"];
        // check if already in cart
        $check = mysqli_query($con, "SELECT s, quantity FROM refreshments WHERE orderid='$orderid' AND itemid='$itemid' AND status='processing'");
        if ($exist = mysqli_fetch_assoc($check)) {
            $newQty = $exist['quantity'] + $qty;
            $totalValue = $newQty * $itemPrice;
            mysqli_query($con, "UPDATE refreshments 
                                SET quantity='$newQty', unitprice='$itemPrice', totalprice='$totalValue'
                                WHERE s='{$exist['s']}'");
        } else {
            $totalValue = $qty * $itemPrice;
            mysqli_query($con, "INSERT INTO refreshments(orderid,itemid,item,unitprice,quantity,totalprice,status, item_category) 
                        VALUES ('$orderid','$itemid','$itemName','$itemPrice','$qty','$totalValue','processing','$itemCategory')");
        }
        $response['status'] = 'ok';
    }
}





if ($action === 'delete' && $orderid && $itemid) {
    mysqli_query($con, "DELETE FROM refreshments WHERE orderid='$orderid' AND itemid='$itemid' AND status='processing'");
    $response['status'] = 'ok';
}

if ($action === 'fetch' && $orderid) {
    $cart = [];
    $res = mysqli_query($con, "SELECT itemid,item,unitprice as price,quantity FROM refreshments WHERE orderid='$orderid' AND status='processing'");
    while ($row = mysqli_fetch_assoc($res)) {
        $cart[] = $row;
    }
    $response['status'] = 'ok';
    $response['cart'] = $cart;
}

echo json_encode($response);
