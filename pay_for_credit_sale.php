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

    echo "<div class='alert alert-danger'>Credit sale order not found.</div>";

    include "footer.php";

    exit;
}

// FETCH ALL ITEMS
$items = [];

$totalAmount = 0;

$customerId = '';

while ($row = mysqli_fetch_assoc($result)) {

    $items[] = $row;

    $totalAmount += (float)$row['totalprice'];

    $customerId = $row['customer'];
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

// TOTAL ALREADY PAID
$paidSql = "
SELECT SUM(amount_paid) AS total_paid
FROM bank_transfers
WHERE payment_for='credit_sale'
AND item_id='$order_ref'
";

$paidResult = mysqli_query($con, $paidSql);

$paidRow = mysqli_fetch_assoc($paidResult);

$totalPaid = (float)($paidRow['total_paid'] ?? 0);

$balance = $totalAmount - $totalPaid;

// MINIMUM FIRST PAYMENT (25%)
$minRequired = 1;

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

        $bankId = isset($_POST['bank_account_id'])
            ? (int)$_POST['bank_account_id']
            : 0;

        $bank = '';

        $bankSql = "
            SELECT bank_name
            FROM bank_accounts
            WHERE id = '$bankId'
            ";

        $bankResult = mysqli_query($con, $bankSql);

        if ($bankRow = mysqli_fetch_assoc($bankResult)) {

            $bank = $bankRow['bank_name'];
        }

        if (empty($bank)) {

            $errors[] = "Please select a valid bank account.";
        }

        // INSERT PAYMENT

        if (empty($errors)) {

            $paymentFor = 'credit_sale';

            $insertSql = "
            INSERT INTO bank_transfers (
            id,
                fileUrl,
                payment_for,
                item_id,
                amount,
                amount_paid,
                bank
            ) VALUES (
             '$order_ref',
                '$fileUrl',
                '$paymentFor',
                '$order_ref',
                '$totalAmount',
                '$enteredAmount',
                '$bank'
            )
            ";

            if (!mysqli_query($con, $insertSql)) {

                $errors[] = "Database insertion failed: " . mysqli_error($con);
            } else {

                // CALCULATE NEW TOTAL PAID

                $newAmountPaid = $totalPaid + $enteredAmount;

                // DETERMINE STATUS

                if ($newAmountPaid >= $totalAmount) {

                    $paymentStatus = 'paid';
                } else {

                    $paymentStatus = 'partly paid';
                }

                // UPDATE CREDIT SALES

                $updateSql = "
                UPDATE credit_sales
                SET status = '$paymentStatus'
                WHERE orderid = '$order_ref'
                ";

                mysqli_query($con, $updateSql);

                echo "
                <div class='alert alert-success'>
                    Payment submitted successfully! Redirecting...
                </div>

                <script>
                    setTimeout(function(){

                        window.location.href='index.php';

                    },3000);
                </script>
                ";

                exit;
            }
        }
    } else {

        if (!empty($uploadResult['errors'])) {

            $errors = array_merge($errors, $uploadResult['errors']);
        }
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

                    <td>₦<?php echo number_format($item['unitprice']); ?></td>

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
                    Minimum first payment:
                    ₦<?php echo number_format($minRequired); ?>
                </small>

            <?php endif; ?>

        </div>

        <?php
        $showAmountInput = false;
        include "bank_account_selection.php";
        ?>

    </form>



</div>

<?php include "footer.php"; ?>