<?php
include "../connect.php"; 
$order = $_POST['orderno'];
$date = date("Y-m-d H:i:s");

// Select order
$sql = "SELECT * from voucher_orders where orderid='$order'";
$sql2 = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($sql2)) {
    $order_amount = $row["total_amount"];
}


$giftcard_amount = 0;

// Check for any pending transaction
$sqb = "SELECT * FROM giftcard_history WHERE orderid='$order' AND status='processing'";
$results = mysqli_query($con, $sqb);
if (mysqli_num_rows($results) > 0) {
    while ($rows = mysqli_fetch_array($results)) {
        $giftcard_amount = $rows['amount_deducted'];
    }
}

$theamount = $order_amount - $giftcard_amount;
echo $theamount;
?>
