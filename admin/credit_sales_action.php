<?php
include "../connect.php";
include "../mailer.php";
$action = $_GET["action"] ? $_GET["action"] : "";
$orderid = $_GET["orderid"] ? $_GET["orderid"] : "";
if ($action == "approve_order") {
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
        $message .= "<p>Your payment link is <a href='../pay_for_credit_sale.php?order=$orderid'></a></p>";
        $message .= "<p>Thank you for choosing Empire Clone.</p>";

        if (sendEmail($email, $subject, $message)) {
            $_SESSION["success"] = "Order approved successfully and payment link sent";
        } else {
            $_SESSION["error"] = "Order approved, but we could not send the email notification.";
        }
    }
}
if($action == 'delete_order'){
    $deleteSQL = "DELETE FROM credit_sales WHERE orderid = '$orderid'";
    mysqli_query($con, $deleteSQL);
    $deleteSQL2 = "DELETE FROM refreshments WHERE orderid = '$orderid'";
    mysqli_query($con, $deleteSQL2);
    $deleteSQL3 = "DELETE FROM saloon_orders WHERE id = '$orderid'";
    mysqli_query($con, $deleteSQL3);
}

// Mark a transfer as paid (processed) and update credit_sales allocations
if ($action == 'mark_transfer_paid') {
    $transfer_id = isset($_GET['transfer_id']) ? (int)$_GET['transfer_id'] : 0;
    if ($transfer_id) {
        // mark transfer as processed
        mysqli_query($con, "UPDATE credit_sales_transfers SET status = 'processed' WHERE id = '$transfer_id'");

        // get the order id for this transfer
        $t = mysqli_fetch_assoc(mysqli_query($con, "SELECT orderid FROM credit_sales_transfers WHERE id = '$transfer_id' LIMIT 1"));
        $orderid_t = $t['orderid'] ?? '';
        if ($orderid_t) {
            // calculate total processed amount for this order
            $r = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(amount_paid) AS total_paid FROM credit_sales_transfers WHERE orderid = '".mysqli_real_escape_string($con,$orderid_t)."' AND status = 'processed'"));
            $totalPaid = (float)($r['total_paid'] ?? 0);

            // fetch all credit_sales items for this order
            $items = [];
            $res = mysqli_query($con, "SELECT * FROM credit_sales WHERE orderid = '".mysqli_real_escape_string($con,$orderid_t)."'");
            while ($row = mysqli_fetch_assoc($res)) $items[] = $row;

            // compute total order price
            $totalOrderPrice = 0;
            foreach ($items as $it) $totalOrderPrice += (float)$it['totalprice'];

            if ($totalOrderPrice > 0) {
                foreach ($items as $it) {
                    $proportional = round($totalPaid * ((float)$it['totalprice'] / $totalOrderPrice), 2);
                    $status = $proportional >= (float)$it['totalprice'] ? 'paid' : 'partly paid';
                    mysqli_query($con, "UPDATE credit_sales SET amount_paid = '".mysqli_real_escape_string($con,$proportional)."', status = '".mysqli_real_escape_string($con,$status)."' WHERE id = '".mysqli_real_escape_string($con,$it['id'])."'");
                }
            } else {
                // no payments: reset
                foreach ($items as $it) {
                    mysqli_query($con, "UPDATE credit_sales SET amount_paid = '0', status = 'pending' WHERE id = '".mysqli_real_escape_string($con,$it['id'])."'");
                }
            }
        }
        $_SESSION['success'] = 'Transfer marked processed and balances updated';
    }
}

// Delete a transfer and recalculate allocations
if ($action == 'delete_transfer') {
    $transfer_id = isset($_GET['transfer_id']) ? (int)$_GET['transfer_id'] : 0;
    if ($transfer_id) {
        // get orderid before delete
        $t = mysqli_fetch_assoc(mysqli_query($con, "SELECT orderid FROM credit_sales_transfers WHERE id = '$transfer_id' LIMIT 1"));
        $orderid_t = $t['orderid'] ?? '';
        mysqli_query($con, "DELETE FROM credit_sales_transfers WHERE id = '$transfer_id'");

        if ($orderid_t) {
            // recalc based only on processed transfers
            $r = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(amount_paid) AS total_paid FROM credit_sales_transfers WHERE orderid = '".mysqli_real_escape_string($con,$orderid_t)."' AND status = 'processed'"));
            $totalPaid = (float)($r['total_paid'] ?? 0);

            $items = [];
            $res = mysqli_query($con, "SELECT * FROM credit_sales WHERE orderid = '".mysqli_real_escape_string($con,$orderid_t)."'");
            while ($row = mysqli_fetch_assoc($res)) $items[] = $row;

            $totalOrderPrice = 0;
            foreach ($items as $it) $totalOrderPrice += (float)$it['totalprice'];

            if ($totalOrderPrice > 0) {
                foreach ($items as $it) {
                    $proportional = round($totalPaid * ((float)$it['totalprice'] / $totalOrderPrice), 2);
                    $status = $proportional >= (float)$it['totalprice'] ? 'paid' : ($proportional > 0 ? 'partly paid' : 'pending');
                    mysqli_query($con, "UPDATE credit_sales SET amount_paid = '".mysqli_real_escape_string($con,$proportional)."', status = '".mysqli_real_escape_string($con,$status)."' WHERE id = '".mysqli_real_escape_string($con,$it['id'])."'");
                }
            }
        }
        $_SESSION['success'] = 'Transfer deleted and balances updated';
    }
}
if (
    isset($_GET['action']) &&
    $_GET['action'] == 'mark_transfer_paid' &&
    isset($_GET['transfer_id'])
) {

    $transfer_id = mysqli_real_escape_string($con, $_GET['transfer_id']);

    // GET TRANSFER
    $transferSql = "
    SELECT *
    FROM credit_sales_transfers
    WHERE id = '$transfer_id'
    LIMIT 1
    ";

    $transferResult = mysqli_query($con, $transferSql);

    if (!$transferResult || mysqli_num_rows($transferResult) == 0) {

        $_SESSION['error'] = "Transfer not found";

        header("Location: credit_sales.php");

        exit;
    }

    $transfer = mysqli_fetch_assoc($transferResult);

    // PREVENT DOUBLE APPROVAL
    if (($transfer['status'] ?? '') == 'paid') {

        $_SESSION['error'] = "Transfer already approved";

        header("Location: credit_sales.php");

        exit;
    }

    $orderid = $transfer['orderid'];

    // MARK TRANSFER AS PAID
    $updateTransfer = "
    UPDATE credit_sales_transfers
    SET status = 'paid'
    WHERE id = '$transfer_id'
    ";

    mysqli_query($con, $updateTransfer);

    // CALCULATE TOTAL APPROVED PAYMENTS
    $paymentSql = "
    SELECT SUM(amount_paid) AS total_paid
    FROM credit_sales_transfers
    WHERE orderid = '$orderid'
    AND status = 'paid'
    ";

    $paymentResult = mysqli_query($con, $paymentSql);

    $paymentRow = mysqli_fetch_assoc($paymentResult);

    $totalApprovedPaid = (float)($paymentRow['total_paid'] ?? 0);

    // GET CREDIT SALE ITEMS
    $items = [];

    $itemsSql = "
    SELECT *
    FROM credit_sales
    WHERE orderid = '$orderid'
    ";

    $itemsResult = mysqli_query($con, $itemsSql);

    while ($row = mysqli_fetch_assoc($itemsResult)) {

        $items[] = $row;
    }

    // CALCULATE TOTAL ORDER PRICE
    $totalOrderPrice = 0;

    foreach ($items as $item) {

        $totalOrderPrice += (float)$item['totalprice'];
    }

    // DISTRIBUTE PAYMENT
    if ($totalOrderPrice > 0) {

        foreach ($items as $item) {

            $itemId = $item['id'];

            $itemPrice = (float)$item['totalprice'];

            $ratio = $itemPrice / $totalOrderPrice;

            $proportionalPayment = min(
                round($totalApprovedPaid * $ratio, 2),
                $itemPrice
            );

            // DETERMINE STATUS
            if ($proportionalPayment <= 0) {

                $status = 'approved';
            } elseif ($proportionalPayment >= $itemPrice) {

                $status = 'paid';
            } else {

                $status = 'partly paid';
            }

            $updateItem = "
            UPDATE credit_sales
            SET
                amount_paid = '$proportionalPayment',
                status = '$status'
            WHERE id = '$itemId'
            ";

            mysqli_query($con, $updateItem);
        }
    }

    $_SESSION['success'] = "Transfer marked as paid successfully";

    header("Location: credit_sales.php");

    exit;
}
header("Location: credit_sales.php");
