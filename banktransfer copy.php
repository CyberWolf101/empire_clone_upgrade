<?php
include "header.php";
include 'process.form.php';

$amount = floatval($_POST['amount'] ?? 0);
$c_email = mysqli_real_escape_string($con, $_POST['customer']['email'] ?? '');
$c_phone = mysqli_real_escape_string($con, $_POST['customer']['phone_number'] ?? '');
$username = mysqli_real_escape_string($con, $_POST['customer']['name'] ?? '');

if (empty($saloon) || $amount <= 0) {
    error_log("Banktransfer.php: Invalid saloon=$saloon or amount=$amount, redirecting to cart.php");
    header("Location: cart.php");
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
$query = "UPDATE saloon_orders SET pay_status='pending', method='Bank Transfer', payment_confirmed=0, name='$username', email='$c_email', phone='$c_phone' WHERE id='$saloon'";
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
            VALUES ('$fileUrl', '$paymentFor', $itemId, $amount, '$bank')";
            if (!mysqli_query($con, $sql)) {
                $errors[] = "Database insertion failed: " . mysqli_error($con);
            }
        } else {
            $errors[] = "Please select a valid bank account.";
        }

        if (empty($errors)) {
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
</style>

    <div class="section-title p-3" style="color:#FFFFFF;">
        <h4>BANK TRANSFER PAYMENT</h4>
                  <p>Please select a bank account to make your payment.</p>

                  <?php echo $saloon ?>
    </div>
<!-- ======= Pricing Section ======= -->
<!-- <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none; border:none;">
    <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#FFFFFF;">
            <h2>BANK TRANSFER PAYMENT</h2>
            <p>Please select a bank account to make your payment.</p>

            <div class="container-fluid mt-5">
                <div class="d-flex justify-content-center">
                    <div class="col-md-8">
                        <?php if (empty($bank_accounts)) { ?>
                            <p style="color: #FFC700;">No bank accounts available. Please contact support.</p>
                            <p style="color: #FFC700;">
                                <a href="cart.php">Back to Cart</a>
                            </p>
                        <?php } else { ?>
                            <div class="mb-3">
                                <label for="bank_account" class="form-label" style="color: #FFC700;">Select Bank</label>
                                <select id="bank_account" class="form-control" onchange="showBankDetails()">
                                    <option value="">-- Select a Bank --</option>
                                    <?php foreach ($bank_accounts as $account) { ?>
                                        <option value="<?php echo $account['id']; ?>" 
                                                data-bank-name="<?php echo htmlspecialchars($account['bank_name'] ?? 'Unknown Bank'); ?>" 
                                                data-account-name="<?php echo htmlspecialchars($account['account_name'] ?? 'Unknown Account'); ?>" 
                                                data-account-number="<?php echo htmlspecialchars($account['account_number'] ?? '0000000000'); ?>">
                                            <?php echo htmlspecialchars($account['bank_name'] ?? 'Unknown Bank'); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div id="bank_details" style="display: none;">
                                <table class="table table-bordered" style="color: white;">
                                    <tbody>
                                        <tr>
                                            <td><strong>Bank Name:</strong></td>
                                            <td id="bank_name_display"></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Account Name:</strong></td>
                                            <td id="account_name_display"></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Account Number:</strong></td>
                                            <td id="account_number_display"></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Amount:</strong></td>
                                            <td>&#8358;<?php echo number_format($amount, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Order ID:</strong></td>
                                            <td><?php echo $saloon; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <form method="get" action="success.php">
                                    <input type="hidden" name="orderid" value="<?php echo $saloon; ?>" />
                                    <input type="hidden" name="status" value="pending" />
                                    <input type="hidden" name="method" value="Bank Transfer" />
                                    <input type="hidden" name="bank_account_id" id="bank_account_id" value="" />
                                    <button type="submit" class="btn btn-submit">I Have Made the Transfer</button>
                                </form>
                            </div>
                            <p style="color: #FFC700; margin-top: 10px;">
                                <a href="cart.php">Back to Cart</a>
                            </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

<!-- <?php
include 'bank_account_selection.php';
?> -->
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