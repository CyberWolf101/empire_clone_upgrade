<?php
include "header.php";
include "process.form.php";

$errors = [];

// --- Ensure bank_transfers has amount_paid column ---
$checkColumn = mysqli_query($con, "SHOW COLUMNS FROM bank_transfers LIKE 'amount_paid'");

if (mysqli_num_rows($checkColumn) == 0) {

    mysqli_query($con, "
        ALTER TABLE bank_transfers 
        ADD COLUMN amount_paid DECIMAL(10,2) NULL AFTER amount
    ");
}

// VALIDATE ORDER REF
if (!isset($_GET['order'])) {

    echo "<div class='alert alert-danger'>No order reference specified.</div>";

    include "footer.php";

    exit;
}

$order_ref = mysqli_real_escape_string($con, $_GET['order']);

// FETCH CREDIT SALE ORDER
$sql = "
SELECT *
FROM credit_sales
WHERE orderid = '$order_ref'
";

$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) == 0) {

    echo "<div class='alert alert-danger'>Credit sale order not found or not approved.</div>";

    include "footer.php";

    exit;
}

// FETCH ALL ITEMS
$items = [];

$totalAmount = 0;

$customerId = '';

$totalPaid = 0;

while ($row = mysqli_fetch_assoc($result)) {

    $items[] = $row;

    $totalAmount += (float)$row['totalprice'];

    $customerId = $row['customer'];

    $totalPaid += (float)$row["amount_paid"];
}

// FETCH CUSTOMER
$customerSql = "
SELECT *
FROM customers
WHERE unique_id = '$customerId'
LIMIT 1
";

$customerResult = mysqli_query($con, $customerSql);

$customer = mysqli_fetch_assoc($customerResult);

$customerName  = $customer['name'] ?? 'Customer';
$customerEmail = $customer['email'] ?? '';
$customerPhone = $customer['phone'] ?? '';


$balance = $totalAmount - $totalPaid;

// MINIMUM FIRST PAYMENT (25%)
$minRequired = 1;

// Load bank accounts for selection
$bank_accounts = [];
$bankSql = "SELECT * FROM bank_accounts ORDER BY bank_name";
$bankRes = mysqli_query($con, $bankSql);
while ($b = mysqli_fetch_assoc($bankRes)) {
    $bank_accounts[] = $b;
}

// If only one bank exists, select it by default (keep as string so '0' is preserved)
$selected_bank_id = count($bank_accounts) === 1 ? (string)$bank_accounts[0]['id'] : '';

// HANDLE SUBMISSION
if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['submit_transfer'])) {

    $enteredAmount = isset($_POST['amount_to_pay'])
        ? (float)$_POST['amount_to_pay']
        : 0;

    // VALIDATIONS

    if ($enteredAmount > $balance) {

        $errors[] = "Amount cannot exceed remaining balance of ₦" . number_format($balance);
    }

    // FILE UPLOAD

    $options = [
        'allowedTypes' => ['pdf', 'jpg', 'jpeg', 'png', 'gif'],
        'maxSize' => 5 * 1024 * 1024
    ];

    $uploadResult = uploadFile('file', 'Uploads/', $options);

    if (empty($uploadResult['errors']) && empty($errors)) {

        $fileUrl = $uploadResult['file_url'];

        // ensure credit_sales_transfers has bank and bank_account_id
        $checkBankCol = mysqli_query($con, "SHOW COLUMNS FROM credit_sales_transfers LIKE 'bank'");
        if (mysqli_num_rows($checkBankCol) == 0) {
            mysqli_query($con, "ALTER TABLE credit_sales_transfers ADD COLUMN bank VARCHAR(255) NULL AFTER method");
        }
        $checkBankIdCol = mysqli_query($con, "SHOW COLUMNS FROM credit_sales_transfers LIKE 'bank_account_id'");
        if (mysqli_num_rows($checkBankIdCol) == 0) {
            mysqli_query($con, "ALTER TABLE credit_sales_transfers ADD COLUMN bank_account_id INT NULL AFTER bank");
        }

        // Get bank inputs (only select existing bank)
        // Keep raw POST value so '0' is preserved as a valid selection
        $bank_account_id = isset($_POST['bank_account_id']) ? (string)$_POST['bank_account_id'] : '';
        // If no bank selected but only one exists, use it (allow '0')
        if ($bank_account_id === '' && $selected_bank_id !== '') {
            $bank_account_id = (string)$selected_bank_id;
        }

        // Require a bank account id to be selected (accept '0' as a valid id)
        if ($bank_account_id === '') {
            $errors[] = "Please select a bank.";
        }

        // Try to fetch bank details; construct a fallback label so $bank is not empty
        $bank = '';
        if ($bank_account_id !== '') {
            $bank_account_id_esc = mysqli_real_escape_string($con, $bank_account_id);
            $bq = mysqli_query($con, "SELECT bank_name, account_number FROM bank_accounts WHERE id = '$bank_account_id_esc' LIMIT 1");
            if ($br = mysqli_fetch_assoc($bq)) {
                $bank = $br['bank_name'] ?? '';
                if (empty($bank)) {
                    $bank = $br['account_number'] ?? '';
                }
                if (empty($bank)) {
                    $bank = 'Bank #' . $bank_account_id_esc;
                }
            } else {
                // fallback to id label
                $bank = 'Bank #' . $bank_account_id_esc;
            }
        }

        if (empty($errors)) {
            $insertSql = "INSERT INTO credit_sales_transfers (orderid, fileUrl, amount_paid, method, bank, bank_account_id) VALUES ('" . mysqli_real_escape_string($con, $order_ref) . "', '" . mysqli_real_escape_string($con, $fileUrl) . "', '" . mysqli_real_escape_string($con, $enteredAmount) . "', 'Bank Transfer', '" . mysqli_real_escape_string($con, $bank) . "', '" . mysqli_real_escape_string($con, $bank_account_id) . "')";

            if (!mysqli_query($con, $insertSql)) {
                $errors[] = "Database insertion failed: " . mysqli_error($con);
            } else {
                echo "<div class='alert alert-success'>Payment submitted successfully! Redirecting...</div><script>setTimeout(function(){window.location.href='index.php';},3000);</script>";
                exit;
            }
        }
    }
} else {

    if (!empty($uploadResult['errors'])) {

        $errors = array_merge($errors, $uploadResult['errors']);
    }
}

?>

<div class="container mt-4 text-white">

    <h4>Credit Sale Payment - #<?php echo htmlspecialchars($order_ref); ?></h4>

    <div class="grid2 mt-4">

        <p>
            <strong>Name:</strong>
            <?php echo htmlspecialchars($customerName); ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?php echo htmlspecialchars($customerEmail); ?>
        </p>

        <p>
            <strong>Phone:</strong>
            <?php echo htmlspecialchars($customerPhone); ?>
        </p>

        <p>
            <strong>Total Amount:</strong>
            ₦<?php echo number_format($totalAmount); ?>
        </p>

        <p>
            <strong>Amount Paid:</strong>
            ₦<?php echo number_format($totalPaid); ?>
        </p>

        <p>
            <strong>Balance:</strong>
            ₦<?php echo number_format($balance); ?>
        </p>

    </div>

    <h4 class="mt-4">Order Items</h4>

    <table class="table table-bordered text-white">

        <thead>

            <tr>

                <th>Item</th>

                <th>Quantity</th>

                <th>Unit Price</th>

                <th>Total</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach ($items as $item): ?>

                <tr>

                    <td><?php echo htmlspecialchars($item['item']); ?></td>

                    <td><?php echo $item['quantity']; ?></td>

                    <td>₦<?php echo $item['unitprice']; ?></td>

                    <td>₦<?php echo number_format($item['totalprice']); ?></td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

    <?php if (!empty($errors)): ?>

        <div class="alert alert-danger">

            <ul class="mb-0">

                <?php foreach ($errors as $error): ?>

                    <li><?php echo htmlspecialchars($error); ?></li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>


    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">

            <label>Amount To Pay</label>

            <input
                type="number"
                name="amount_to_pay"
                class="form-control"
                min="<?php echo $minRequired; ?>"
                max="<?php echo $balance; ?>"
                required>

            <?php if ($totalPaid <= 0): ?>

                <small class="text-warning">
                    Minimum payment: ₦<?php echo number_format($minRequired); ?>
                </small>

            <?php endif; ?>

        </div>



        <div class="mb-3">
            <label>Select Bank</label>
            <select name="bank_account_id" class="form-control" required>
                <option value="">-- Select bank --</option>
                <?php foreach ($bank_accounts as $acct): ?>
                    <option value="<?= htmlspecialchars($acct['id']); ?>" <?= ($acct['id'] == $selected_bank_id) ? 'selected' : '' ?>><?= htmlspecialchars($acct['bank_name']); ?> - <?= htmlspecialchars($acct['account_number']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">

            <label>Upload Proof of Payment</label>

            <input type="file" name="file" class="form-control" required accept=".pdf,.jpg,.jpeg,.png,.gif">

            <small class="text-muted">Accepted formats: PDF, JPG, PNG, GIF (Max 5MB)</small>

        </div>

        <button type="submit" name="submit_transfer" class="btn btn-primary">Submit Payment</button>

    </form>



</div>

<?php include "footer.php"; ?>