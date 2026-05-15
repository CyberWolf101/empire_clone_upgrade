<?php

include "../connect.php";
include "../mailer.php";
if (isset($_GET["orderid"])) {
    $orderid = mysqli_real_escape_string($con, $_GET["orderid"]);
    $email = isset($_GET["customer_email"]) ? mysqli_real_escape_string($con, $_GET["customer_email"]) : '';

    $saleSql = "SELECT * FROM credit_sales WHERE orderid = '$orderid' LIMIT 1";
    $saleResult = mysqli_query($con, $saleSql);
    $sale = $saleResult ? mysqli_fetch_assoc($saleResult) : null;

    $sql = "UPDATE credit_sales SET status = 'approved' WHERE orderid = '$orderid'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $subject = "Credit Sale Approved: Order #$orderid";
        $item = $sale["item"] ?? 'your product';
        $quantity = $sale["quantity"] ?? '1';
        $totalprice = $sale["totalprice"] ?? 'N/A';
        $amountPaid = $sale["amount_paid"] ?? '0.00';

        $message = "<p>Hello,</p>";
        $message .= "<p>Your credit sale order <strong>#$orderid</strong> has been approved.</p>";
        $message .= "<p>Order details:<br>";
        $message .= "Item: <strong>$item</strong><br>";
        $message .= "Quantity: <strong>$quantity</strong><br>";
        $message .= "Total Price: <strong>$totalprice</strong><br>";
        $message .= "Amount Paid: <strong>$amountPaid</strong></p>";
        $message .= "<p>Your payment link has been sent separately, and our team will follow up if any additional information is required.</p>";
        $message .= "<p>Thank you for choosing Empire Clone.</p>";

        if (sendEmail($email, $subject, $message)) {
            $_SESSION["success"] = "Order approved successfully and payment link sent";
        } else {
            $_SESSION["error"] = "Order approved, but we could not send the email notification.";
        }
    }
    header("Location: credit_sales.php");
}