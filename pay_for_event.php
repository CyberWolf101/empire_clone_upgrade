<?php
include "header.php";
include 'process.form.php';

// --- Ensure bank_transfers has amount_paid column ---
$checkColumn = mysqli_query($con, "SHOW COLUMNS FROM bank_transfers LIKE 'amount_paid'");
if (mysqli_num_rows($checkColumn) == 0) {
    mysqli_query($con, "ALTER TABLE bank_transfers ADD COLUMN amount_paid DECIMAL(10,2) NULL AFTER amount");
}


if (!isset($_GET['order'])) {
    echo "<div class='alert alert-danger'>No order reference specified.</div>";
    include "footer.php";
    exit;
}

$order_ref = mysqli_real_escape_string($con, $_GET['order']);

// Fetch order
$sql = "SELECT * FROM event_orders WHERE order_ref='$order_ref'";
$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>Order not found.</div>";
    include "footer.php";
    exit;
}

$order = mysqli_fetch_assoc($result);
// --- Calculate total already paid for this order ---
$totalPaid = (float) $order['amount_paid'];

// Expected amount: edited_price takes priority
$expectedAmount = ($order['edited_price'] > 0) ? $order['edited_price'] : $order['total_amount'];
$balance = $expectedAmount - $totalPaid;
// echo $balance;

// Min required: 25% only if nothing paid yet
$minRequired = ($totalPaid > 0) ? 1 : ceil($expectedAmount * 0.7);
// Fetch cart items
$items_sql = "SELECT * FROM event_order_items WHERE orderid='" . $order['id'] . "'";
$items_result = mysqli_query($con, $items_sql);


if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['submit_transfer'])) {
    $errors = []; // <--- ADD THIS

    $expectedAmount = ($order['edited_price'] > 0) ? $order['edited_price'] : $order['total_amount'];
    $minAmount = ceil($expectedAmount * 0.7);
    $enteredAmount = isset($_POST['amount_to_pay']) ? (float) $_POST['amount_to_pay'] : 0;



    // if ($enteredAmount < $minAmount || $enteredAmount > $expectedAmount) {
    //     $errors[] = "Amount must be between ₦" . number_format($minAmount) . " and ₦" . number_format($expectedAmount) . ".";
    // }
    if ($enteredAmount < $minRequired || $enteredAmount > $balance) {
        if ($totalPaid > 0) {
            $errors[] = "Amount must be atleast ₦" . number_format($balance) . " (your remaining balance).";
        } else {
            $errors[] = "You must pay at least 25% (₦" . number_format($minRequired) . ") of the total (₦" . number_format($expectedAmount) . ").";
        }
    }


    $options = [
        'allowedTypes' => ['pdf', 'jpg', 'jpeg', 'png', 'gif'], // Restrict to common receipt formats
        'maxSize' => 5 * 1024 * 1024 // 5MB
    ];
    $result = uploadFile('file', 'Uploads/', $options);

    if (empty($result['errors']) && empty($errors)) {
        // Get filename and URL
        $filename = $result['filename']; // e.g., 'receipt_123.pdf'
        $fileUrl = $result['file_url']; // e.g., 'http://localhost/Uploads/receipt_123.pdf'
        // echo "<pre> fileurl";
        // print_r($result);
        // echo "</pre>";
        // exit;
        $paymentFor = 'event_order';
        $itemId = $order['id'];
        $amount = ($order['edited_price'] > 0) ? $order['edited_price'] : $order['total_amount'];
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
            $sql = "INSERT INTO bank_transfers (fileUrl, payment_for, item_id, amount, amount_paid, bank) 
        VALUES ('$fileUrl', '$paymentFor', $itemId, $amount, $enteredAmount, '$bank')";

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

<?php
$discountPercent = 0;
if ($expectedAmount > 0) {
    $discountPercent = (( $order['total_amount']- $expectedAmount) / $order['total_amount']) * 100;
}
?>
<div class="container mt-4 text-white">
    <h5>Event Order #<?php echo htmlspecialchars($order['order_ref']); ?></h5>
    <div class='grid2 mt-4'>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone_number']); ?></p>
        <p><strong>Actual price:</strong> ₦<?php echo number_format($order['total_amount']); ?></p>
        <p><strong>Discounted price:</strong> ₦<?php echo number_format($expectedAmount); ?></p>
        <?php if ($discountPercent > 0): ?>
            <p><strong>Discount %:</strong> <?php echo number_format($discountPercent); ?>%</p>
        <?php endif; ?>
        <p><strong>Amount Paid:</strong> ₦<?php echo number_format($totalPaid); ?></p>
        <p><strong>Due Amount:</strong> ₦<?php echo number_format($balance); ?></p>

        <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
        <p><strong>Payment:</strong>
            <?php
            if ($order['pay_status'] === 'paid') {
                echo "<span class='badge badge-success'>Paid</span>";
            } elseif ($order['pay_status'] === 'partly paid') {
                echo "<span class='badge badge-warning'>Partly Paid</span>";
            } else {
                echo "<span class='badge badge-danger'>Unpaid</span>";
            }
            ?>
        </p>

    </div>

    <h4 class="mt-4">Cart Items</h4>
    <table class="table table-bordered text-white">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <!-- <th>Price</th> -->
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($items_result) > 0): ?>
                <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['item']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <!-- <td>₦<?php echo number_format($item['unitprice']); ?></td> -->
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No items found for this order.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php
    // Expected amount: edited_price takes priority
    $expectedAmount = ($order['edited_price'] > 0) ? $order['edited_price'] : $order['total_amount'];
    $minAmount = ceil($expectedAmount * 0.70); // round up
    ?>


    <?php
    $showAmountInput = true;
    include 'bank_account_selection.php';
    ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mt-2">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

</div>



<?php include "footer.php"; ?>