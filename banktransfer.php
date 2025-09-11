<?php
include "header.php";
include 'process.form.php';

// $amount = floatval($_POST['amount'] ?? 0);
// $c_email = mysqli_real_escape_string($con, $_POST['customer']['email'] ?? '');
// $c_phone = mysqli_real_escape_string($con, $_POST['customer']['phone_number'] ?? '');
// $username = mysqli_real_escape_string($con, $_POST['customer']['name'] ?? '');
// var_dump($_SESSION);
$amount = floatval($_SESSION['cart_total'] ?? 0);
$c_email = mysqli_real_escape_string($con, $_SESSION['email'] ?? '');
$c_phone = mysqli_real_escape_string($con, $_SESSION['phone'] ?? '');
$username = mysqli_real_escape_string($con, $_SESSION['username'] ?? '');

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


// Update order with customer details and pending status
// $query = "UPDATE saloon_orders SET pay_status='pending', method='Bank Transfer', payment_confirmed=0, name='$username', email='$c_email', phone='$c_phone' WHERE id='$saloon'";
$query = "UPDATE saloon_orders SET pay_status='pending',status='pending', method='Bank Transfer', payment_confirmed=0, name='$username' WHERE id='$saloon'";
if (!mysqli_query($con, $query)) {
    error_log("Banktransfer.php: Failed to update saloon_orders: " . mysqli_error($con) . " | Query: $query");
}

// Fetch bank accounts
$bank_accounts = [];
$sql = "SELECT * FROM bank_accounts ORDER BY bank_name";
$result = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($result)) {
    $bank_accounts[] = $row;
}


if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['submit_transfer'])) {
    $errors = []; // <--- ADD THIS

    $options = [
        'allowedTypes' => ['pdf', 'jpg', 'jpeg', 'png', 'gif'], // Restrict to common receipt formats
        'maxSize' => 5 * 1024 * 1024 // 5MB
    ];
    $result = uploadFile('file', 'Uploads/', $options);

    if (empty($result['errors'])) {
        // Get filename and URL
        $filename = $result['filename']; // e.g., 'receipt_123.pdf'
        $fileUrl = $result['file_url']; // e.g., 'http://localhost/Uploads/receipt_123.pdf'
        // echo "<pre> fileurl";
        // print_r($result);
        // echo "</pre>";
        // exit;
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
            $sql = "INSERT INTO bank_transfers (fileUrl, payment_for, item_id, amount, bank) 
            VALUES ('$fileUrl', '$paymentFor', '$itemId', $amount, '$bank')";
            if (!mysqli_query($con, $sql)) {
                $errors[] = "Database insertion failed: " . mysqli_error($con);
            }
        } else {
            $errors[] = "Please select a valid bank account.";
        }

        if (empty($errors)) {
            // if (isset($_COOKIE['foodID'])) {
            //     setcookie("foodID", "", time() - 3600, "/", "", true, true);
            //     unset($_COOKIE['foodID']);
            // }

            error_log("Cookie unset code executed at " . date('Y-m-d H:i:s'), 3, "debug.log");

            if (isset($_COOKIE['foodID'])) {
                // error_log("foodID cookie found: " . $_COOKIE['foodID'], 3, "debug.log");
                // Try multiple variations to cover possible mismatches
                setcookie("foodID", "", time() - 3600, "/", "", true, true); // Original
                setcookie("foodID", "", time() - 3600, "/", "", false, true); // Non-secure
                setcookie("foodID", "", time() - 3600, "", "", true, true); // No path
                unset($_COOKIE['foodID']);
                // error_log("Attempted to unset foodID cookie", 3, "debug.log");
                // Redirect to confirm cookie is gone
            } else {
                // error_log("foodID cookie not set", 3, "debug.log");
            }

            unset($_SESSION['username']);
            unset($_SESSION['email']);
            unset($_SESSION['phone']);
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
    <p>Please select a bank account to make your payment. <?php echo $amount ?></p>

    <div class="text-white">
        Due amount: <b class="txt"> ₦ <?php echo number_format($amount, 2); ?>
        </b>
    </div>
</div>

<?php
include 'bank_account_selection.php';
?>
<!-- <script>
function showBankDetails() {
    var select = document.getElementById('bank_account');
    var bankDetails = document.getElementById('bank_details');
    var bankNameDisplay = document.getElementById('bank_name_display');
    var accountNameDisplay = document.getElementById('account_name_display');
    var accountNumberDisplay = document.getElementById('account_number_display');
    var bankAccountId = document.getElementById('bank_account_id');

    if (select.value) {
        var selectedOption = select.options[select.selectedIndex];
        bankNameDisplay.textContent = selectedOption.getAttribute('data-bank-name');
        accountNameDisplay.textContent = selectedOption.getAttribute('data-account-name');
        accountNumberDisplay.textContent = selectedOption.getAttribute('data-account-number');
        bankAccountId.value = select.value;
        bankDetails.style.display = 'block';
    } else {
        bankDetails.style.display = 'none';
    }
}
</script> -->

<?php include "footer.php"; ?>