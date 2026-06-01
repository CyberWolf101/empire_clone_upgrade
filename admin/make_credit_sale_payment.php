<?php
include "../connect.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["make_payment"])) {
    $orderid = $_POST["orderid"];
    $fileUrl = $_POST["fileUrl"];
    $amount = $_POST["amount"];
    $method = $_POST["method"];
    $amountPaid = 0;
    $selectPaid = "SELECT SUM(amount_paid) as paid_amount FROM credit_sales WHERE orderid = '$orderid'";
    $res = mysqli_query($con, $selectPaid);
    while ($ress = mysqli_fetch_assoc($res)) {
        $amountPaid += $ress["paid_amount"];
    }
    $newAmountToPay = $amountPaid + $amount;
    $totalAmount = 0;

    $getTotalSql = "SELECT SUM(totalprice) AS total_amount FROM credit_sales WHERE orderid = '$orderid'";

    $getTotalResult = mysqli_query($con, $getTotalSql);

    $getTotalRow = mysqli_fetch_assoc($getTotalResult);

    $totalAmount = (float)($getTotalRow['total_amount'] ?? 0);
    if ($newAmountToPay <= $totalAmount) {
        $makePayment = "INSERT INTO credit_sales_transfers(orderid,fileUrl,amount_paid,method) VALUES ('$orderid','$fileUrl','$amount','$method')";
        mysqli_query($con, $makePayment) ? $_SESSION["success"] = "Payment successful" : $_SESSION["error"] = "Error occured while making payment";
    }
}
header("Location: credit_sales.php");
