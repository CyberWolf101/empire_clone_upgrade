<?php
include "header.php";
include 'process.form.php';

$subtotal = floatval($_COOKIE['cart_total'] ?? 0);
$shipping_fee = floatval($_COOKIE['shipping_fee'] ?? 0);
$shipping_type = $_COOKIE['shipping_type'] ?? 'pickup';
$selected_place = $_COOKIE['selected_place'] ?? '';
$c_email = mysqli_real_escape_string($con, $_COOKIE['customer_email'] ?? '');
$c_phone = mysqli_real_escape_string($con, $_COOKIE['customer_phone'] ?? '');
$username = mysqli_real_escape_string($con, $_COOKIE['username'] ?? '');

echo $c_phone;
echo $c_email;
// Decode selected_place JSON string for display
$selected_place_data = $selected_place ? json_decode($selected_place, true) : [];
$selected_place_description = isset($selected_place_data['description']) ? htmlspecialchars($selected_place_data['description']) : 'Not specified';

$amount = $subtotal + $shipping_fee; // Include shipping fee

// Fallback to saloon_orders if session data is missing
if ($shipping_fee === 0 && !empty($saloon)) {
    $sql_shipping = "SELECT shipping_fee FROM saloon_orders WHERE id='$saloon'";
    $res_shipping = mysqli_query($con, $sql_shipping);
    if ($res_shipping && $row_shipping = mysqli_fetch_assoc($res_shipping)) {
        $shipping_fee = (int) $row_shipping['shipping_fee'];
        $_SESSION['shipping_fee'] = $shipping_fee; // Update session
        $amount = $subtotal + $shipping_fee; // Recalculate amount
        error_log("Banktransfer.php: Retrieved shipping fee $shipping_fee from saloon_orders for order $saloon");
    } else {
        error_log("Banktransfer.php: Failed to fetch shipping fee from saloon_orders: " . mysqli_error($con));
    }
}

if (empty($saloon)) {
    echo "Invalid order id $saloon $amount";
    exit;
}

// Check if payment_confirmed column exists
$checkColumnSql = "SHOW COLUMNS FROM saloon_orders LIKE 'payment_confirmed'";
$result = mysqli_query($con, $checkColumnSql);
if (mysqli_num_rows($result) == 0) {
    mysqli_query($con, "ALTER TABLE saloon_orders ADD payment_confirmed TINYINT(1) NOT NULL DEFAULT 0") or die('Could not add payment_confirmed column: ' . mysqli_error($con));
    mysqli_query($con, "UPDATE saloon_orders SET payment_confirmed = 1 WHERE method != 'Bank Transfer'") or die('Could not update payment_confirmed: ' . mysqli_error($con));
}

// Check if place_details column exists
$checkPlaceDetailsSql = "SHOW COLUMNS FROM saloon_orders LIKE 'place_details'";
$result = mysqli_query($con, $checkPlaceDetailsSql);
if (mysqli_num_rows($result) == 0) {
    mysqli_query($con, "ALTER TABLE saloon_orders ADD place_details TEXT DEFAULT NULL") or die('Could not add place_details column: ' . mysqli_error($con));
    error_log("Banktransfer.php: Added place_details column to saloon_orders");
}

// Update order with customer details, shipping fee, shipping type, and place details
$selected_place_escaped = mysqli_real_escape_string($con, $selected_place);
// $query = "UPDATE saloon_orders SET pay_status='pending', status='pending', method='Bank Transfer', payment_confirmed=0, name='$username', email='$c_email', phone='$c_phone', shipping_fee='$shipping_fee', shipping_type='$shipping_type', place_details='$selected_place_escaped' WHERE id='$saloon'";
$query = "UPDATE saloon_orders SET pay_status='pending', status='pending', method='Bank Transfer', payment_confirmed=0, name='$username', shipping_fee='$shipping_fee', shipping_type='$shipping_type', place_details='$selected_place_escaped' WHERE id='$saloon'";
if (!mysqli_query($con, $query)) {
    error_log("Banktransfer.php: Failed to update saloon_orders: " . mysqli_error($con) . " | Query: $query");
} else {
    error_log("Banktransfer.php: Successfully updated saloon_orders with place_details: $selected_place_escaped");
}

// Fetch bank accounts
// $bank_accounts = [];
// $sql = "SELECT * FROM bank_accounts ORDER BY bank_name";
// $result = mysqli_query($con, $sql);
// while ($row = mysqli_fetch_array($result)) {
//     $bank_accounts[] = $row;
// }

if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['submit_transfer'])) {
    $errors = [];

    $options = [
        'allowedTypes' => ['pdf', 'jpg', 'jpeg', 'png', 'gif'],
        'maxSize' => 5 * 1024 * 1024 // 5MB
    ];
    $result = uploadFile('file', 'Uploads/', $options);

    if (empty($result['errors'])) {
        $filename = $result['filename'];
        $fileUrl = $result['file_url'];
        $paymentFor = 'cart_items';
        $itemId = $saloon;
        $bankId = isset($_POST['bank_account_id']) ? (int) $_POST['bank_account_id'] : 0;
        $bank = '';
        if ($bankId > 0) {
            $sql = "SELECT bank_name FROM bank_accounts WHERE id = $bankId";
            $result = mysqli_query($con, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                $bank = $row['bank_name'];
            }
        } else {
            $bank = 'unknown';
        }

        if (!empty($bank)) {
            $paymentFor = mysqli_real_escape_string($con, $paymentFor);
            $sql = "INSERT INTO bank_transfers(fileUrl, payment_for, item_id, amount, bank) 
                    VALUES ('$fileUrl', '$paymentFor', '$itemId', $amount, '$bank')";
            if (!mysqli_query($con, $sql)) {
                $errors[] = "Database insertion failed: " . mysqli_error($con);
            }
        } else {
            $errors[] = "Please select a valid bank account.";
        }

        if (empty($errors)) {
            if (isset($_COOKIE['foodID'])) {

                setcookie("foodID", "", time() - 3600, "/", "", true, true); // Original
                setcookie("foodID", "", time() - 3600, "/", "", false, true); // Non-secure
                setcookie("foodID", "", time() - 3600, "", "", true, true); // No path
                unset($_COOKIE['foodID']);
            }

            // unset($_SESSION['username']);
            // unset($_SESSION['email']);
            // unset($_SESSION['phone']);
            
            echo "<div class='alert alert-success'>Payment details submitted successfully! Redirecting to homepage...</div>";
            echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 3000);</script>";
            exit;
        }
    }
}
?>

<style>
    .section-title h2 {
        font-size: 32px;
        font-weight: bold;
        text-transform: capitalize;
        margin-bottom: 20px;
        padding-bottom: 20px;
        position: relative;
        letter-spacing: 0px;
        color: #FFFFFF;
    }

    .section-title h2::after {
        background: none;
    }

    .form-control {
        background: transparent;
        border: 1px solid #FFC700;
        color: white;
    }

    .btn-submit {
        background: #FFC700;
        color: #000;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .btn-submit:hover {
        background: #000;
        color: #FFC700;
    }

    .txt {
        color: #FFC700;
    }
</style>

<div class="section-title p-3" style="color:#FFFFFF;">
    <h4>BANK TRANSFER PAYMENT</h4>
    <p>Please select a bank account to make your payment.</p>
    <div class="text-white">
        Due amount: <b class="txt"> ₦ <?php echo number_format($amount, 2); ?></b>
    </div>
    <?php if ($shipping_type === 'delivery'): ?>
        <div class="text-white">
            Delivery Location: <b class="txt"><?php echo $selected_place_description; ?></b>
        </div>
    <?php endif; ?>
</div>

<?php include 'bank_account_selection.php'; ?>

<?php include "footer.php"; ?>