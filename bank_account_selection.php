<?php
if (isset($_COOKIE["currentService"])) {
?>
    <?php
    $service_type = $_COOKIE["currentService"];
    $sql = "SELECT * FROM bank_accounts WHERE service_type='$service_type' ORDER BY bank_name";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $bank_accounts = [];
        $bank_accounts[] = $row;
    }
    ?>
<?php
} else {
    $bank_accounts = [];
    $sql = "SELECT * FROM bank_accounts ORDER BY bank_name";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $bank_accounts[] = $row;
    }
}
?>
<?php
if (!isset($showAmountInput)) {
    $showAmountInput = false; // default
}
?>
<style>
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



<!-- ======= Bank Transfer Section ======= -->
<section id="bank-transfer" class=" m-0 p-0">
    <div class="" style="width:100%;">
        <div class="" style="color:#FFFFFF;">
            <div class="container-fluid ">
                <div class="d-flex justify-content-center">
                    <div class="col-md-8">
                        <?php if (!empty($errors)) { ?>
                            <div style="color: #FF0000;">
                                <?php foreach ($errors as $error) { ?>
                                    <p><?php echo htmlspecialchars($error); ?></p>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if (empty($bank_accounts)) { ?>
                            <p style="color: #FFC700;">No bank accounts available. Please contact support.</p>
                            <p style="color: #FFC700;">
                                <a href="cart.php">Back to Cart</a>
                            </p>
                        <?php } else { ?>
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <?php if (!empty($showAmountInput)): ?>
                                        <div class="mb-3">
                                            <label for="amount_to_pay" class="form-label" style="color: #FFC700;">Enter Amount
                                                to Pay</label>
                                            <input type="number"
                                                class="form-control"
                                                id="amount_to_pay"
                                                name="amount_to_pay"
                                                min="<?php echo ($totalPaid > 0) ? 1 : $minAmount; ?>"
                                                max="<?php echo $balance; ?>"
                                                value="<?php echo isset($_POST['amount_to_pay']) ? htmlspecialchars($_POST['amount_to_pay']) : ''; ?>">

                                            <small class="form-text text-warning">
                                                <?php if ($totalPaid > 0): ?>
                                                    You have already paid ₦<?php echo number_format($totalPaid); ?>.
                                                    Your remaining balance is ₦<?php echo number_format($balance); ?>.
                                                <?php else: ?>
                                                    You must pay at least 70% (₦<?php echo number_format($minRequired); ?>)
                                                    of the total (₦<?php echo number_format($expectedAmount); ?>).
                                                <?php endif; ?>
                                            </small>

                                        </div>
                                    <?php endif; ?>

                                    <label for="bank_account" class="form-label" style="color: #FFC700;">Select Bank</label>
                                    <select id="bank_account" name="bank_account_id" class="form-control"
                                        onchange="showBankDetails()" required>
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

                                <div class="mb-3">
                                    <label for="file" class="form-label" style="color: #FFC700;">Upload Proof of
                                        Payment</label>
                                    <input type="file" name="file" id="file" class="form-control" required>
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

                                        </tbody>
                                    </table>
                                </div>

                                <input type="hidden" name="submit_transfer" value="1">
                                <button type="submit" class="btn btn-submit">I Have Made the Transfer</button>
                            </form>

                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function showBankDetails() {
        var select = document.getElementById('bank_account');
        var bankDetails = document.getElementById('bank_details');
        var bankNameDisplay = document.getElementById('bank_name_display');
        var accountNameDisplay = document.getElementById('account_name_display');
        var accountNumberDisplay = document.getElementById('account_number_display');

        if (select.value) {
            var selectedOption = select.options[select.selectedIndex];
            bankNameDisplay.textContent = selectedOption.getAttribute('data-bank-name');
            accountNameDisplay.textContent = selectedOption.getAttribute('data-account-name');
            accountNumberDisplay.textContent = selectedOption.getAttribute('data-account-number');
            bankDetails.style.display = 'block';
        } else {
            bankDetails.style.display = 'none';
        }
    }
</script>