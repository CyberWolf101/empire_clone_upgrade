<?php
include "header.php";
include "../process.form.php";

$errors = [];
$success = false;
$amount = (float)$total_all;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_transfer'])) {
    $bank_account_id = intval($_POST['bank_account_id'] ?? 0);

    // if ($bank_account_id <= 0) {
    //     $errors[] = 'Please select a bank account.';
    // }

    $uploadResult = uploadFile('file', 'Uploads/', [
        'allowedTypes' => ['pdf', 'jpg', 'jpeg', 'png', 'gif'],
        'maxSize' => 5 * 1024 * 1024,
    ]);

    if (!empty($uploadResult['errors'])) {
        $errors = array_merge($errors, $uploadResult['errors']);
    }

    if (empty($errors)) {
        $fileUrl = mysqli_real_escape_string($con, $uploadResult['file_url']);
        $bank = 'Unknown';
        $bankAccountResult = mysqli_query($con, "SELECT bank_name FROM bank_accounts WHERE id = $bank_account_id LIMIT 1");
        if ($bankAccountResult && $row = mysqli_fetch_assoc($bankAccountResult)) {
            $bank = mysqli_real_escape_string($con, $row['bank_name']);
        }

        $paymentFor = 'cart_items';
        $itemId = mysqli_real_escape_string($con, $saloon);
        $amountSafe = mysqli_real_escape_string($con, number_format($amount, 2, '.', ''));

        $insertSql = "INSERT INTO bank_transfers (fileUrl, payment_for, item_id, amount, bank) VALUES ('$fileUrl', '$paymentFor', '$itemId', '$amountSafe', '$bank')";
        if (!mysqli_query($con, $insertSql)) {
            $errors[] = 'Failed to save bank transfer details: ' . mysqli_error($con);
        } else {
            mysqli_query($con, "UPDATE saloon_orders SET pay_status='pending', status='pending', method='Bank Transfer', payment_confirmed=0, transfer_amount='$amountSafe' WHERE id='$saloon'");
            mysqli_query($con, "UPDATE appointments SET status='pending' WHERE id='$saloon'");
            mysqli_query($con, "UPDATE refreshments SET status='pending' WHERE orderid='$saloon'");
            $success = true;
        }
    }
}

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

<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none; border:none;">
    <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#FFFFFF;" align="center">
            <h2>Bank Transfer Payment</h2>
            <p>Upload proof of transfer to submit your order.</p>
        </div>

        <?php if ($success) : ?>
            <div class="alert alert-success" style="background:#0b3d0b; color:#fff; border:1px solid #28a745;">
                <strong>✓ Success!</strong><br>
                Your bank transfer details have been submitted successfully. Your order is now pending verification.<br>
                <small>Redirecting to home page in 4 seconds...</small>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = '../index.php';
                }, 4000);
            </script>
        <?php endif; ?>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger" style="background:#3d0b0b; color:#fff; border:1px solid #dc3545;">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4" style="background:#111; border:1px solid #FFC700;">
                    <div class="mb-4" style="color:#FFFFFF;">
                        <p>Order ID: <strong class="txt"><?php echo htmlspecialchars($saloon); ?></strong></p>
                        <p>Amount due: <strong class="txt">₦ <?php echo number_format($amount, 2); ?></strong></p>
                    </div>

                    <?php include '../bank_account_selection.php'; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>