<?php
include "header.php";

// Get order ID and status from URL
$ref = $_GET['tx_ref'] ?? $_GET['orderid'] ?? '';
$reed = $_GET['status'] ?? 'pending';
$saloon = trim($ref);

// Validate order ID
if (empty($saloon)) {
    error_log("Success.php: Invalid order ID, redirecting to cart.php");
    header("Location: cart.php");
    exit;
}

// Clear foodID cookie and session data if not cancelled
if ($reed != "cancelled") {
    setcookie("foodID", "", time() - 3600, "/", "", true, true);
    // Clear session data
    unset($_SESSION['username']);
    unset($_SESSION['customer_email']);
    unset($_SESSION['customer_phone']);
}

// Fetch order details from database
$sql = "SELECT name, email, card_amount, method, payment_confirmed FROM saloon_orders WHERE id='$saloon'";
$result = mysqli_query($con, $sql);
if (!$result || !($row = mysqli_fetch_array($result))) {
    error_log("Success.php: Order not found for id=$saloon");
    header("Location: cart.php");
    exit;
}
$username = $row['name'] ?? $_SESSION['username'] ?? '';
$c_email = $row['email'] ?? $_SESSION['customer_email'] ?? '';
$total_all = $row['card_amount'] ?? 0;
$method = $row['method'] ?? 'Bank Transfer';
$payment_confirmed = $row['payment_confirmed'];

// Fetch site details
$sitemail = defined('SITE_EMAIL') ? SITE_EMAIL : 'info@chbluxuryempire.com';
$siteimg = defined('SITE_LOGO') ? SITE_LOGO : 'http://chbluxuryempire.com/assets/img/luxury/logo_luxury.png';
$sitename = defined('SITE_NAME') ? SITE_NAME : 'CHB Luxury Empire';
$date = date('Y-m-d H:i:s');

// Process payment
include "process_payment.php";
$processResult = processPayment($saloon, $reed, $total_all, $c_email, $username, $sitemail, $siteimg, $sitename, $date, $method, $con);
$html = $processResult['html'];
$error = $processResult['error'];
?>

<style>
.section-title h2::after {
    content: "";
    position: absolute;
    display: block;
    width: 80px;
    height: 5px;
    background: none;
}
.pricing h3 {
    font-weight: 500;
    margin: -20px -20px 0 -20px;
    padding: 0px 15px;
    font-size: 20px;
    font-weight: 600;
    color: #fff;
    background: none;
}
</style>

<!-- ======= Pricing Section ======= -->
<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none; border:none;">
    <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#fff; background:none; padding:none;" align="center">
            <?php if ($reed == "cancelled") { ?>
                <h3>Payment Cancelled. Please try Again</h3>
                <p><a href="../index.php" style="color:#FFC700;"><u>Go To Home</u></a></p>
            <?php } else { ?>
                <?php if ($method == "Bank Transfer" && $payment_confirmed == 0) { ?>
                    <h2>PAYMENT PENDING VERIFICATION</h2>
                    <p>Here is your Order ID - <span style="font-size:large; text-transform:none; color:#FFC700;"><?php echo htmlspecialchars($saloon); ?></span></p>
                    <p style="font-size:17px; font-weight:700;">
                        Your bank transfer order is pending admin verification. You will receive an email confirmation once verified.<br>
                        Kindly check your email for bank transfer details.<br>
                        <a href="https://chbluxuryempire.com/" style="color:#FFC700;">Click here to Home</a>
                    </p>
                <?php } else { ?>
                    <h2>PAYMENT SUCCESSFUL</h2>
                    <p>Here is your Order ID - <span style="font-size:large; text-transform:none; color:#FFC700;"><?php echo htmlspecialchars($saloon); ?></span></p>
                    <p style="font-size:17px; font-weight:700;">
                        Your order has been processed successfully. Provide this Order ID to the receptionist upon arrival.<br>
                        Kindly check your email for order details.<br>
                        <a href="https://chbluxuryempire.com/" style="color:#FFC700;">Click here to Home</a>
                    </p>
                <?php } ?>
                <?php if (!empty($error)) { ?>
                    <p style="color:red;"><?php echo $error; ?></p>
                <?php } ?>
                <?php echo $html; ?>
            <?php } ?>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>