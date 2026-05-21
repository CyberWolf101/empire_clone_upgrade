<?php
include "header.php";
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['make_payment'])) {

    $orderid = mysqli_real_escape_string($con, $_POST['orderid']);
    $paymentAmount = (float) $_POST['payment_amount'];

    // GET ORDER DETAILS
    $orderSql = "
    SELECT 
        SUM(totalprice) AS total_price,
        MAX(amount_paid) AS amount_paid
    FROM credit_sales
    WHERE orderid = '$orderid'
    ";

    $orderResult = mysqli_query($con, $orderSql);
    $orderData = mysqli_fetch_assoc($orderResult);

    $totalPrice = (float) $orderData['total_price'];
    $alreadyPaid = (float) $orderData['amount_paid'];

    $newAmountPaid = $alreadyPaid + $paymentAmount;

    // VALIDATE
    if ($paymentAmount <= 0) {

        $_SESSION['error'] = "Invalid payment amount.";
    } elseif ($newAmountPaid > $totalPrice) {

        $_SESSION['error'] = "Payment exceeds remaining balance.";
    } else {

        // DETERMINE STATUS
        if ($newAmountPaid >= $totalPrice) {

            $paymentStatus = "paid";
        } else {

            $paymentStatus = "partly paid";
        }

        // UPDATE CREDIT SALES
$updateCredit = "
UPDATE credit_sales
SET 
    amount_paid = '$newAmountPaid',
    status = '$paymentStatus'
WHERE orderid = '$orderid'
";

// UPDATE REFRESHMENTS
$updateRefreshments = "
UPDATE refreshments
SET 
    amount_paid = '$newAmountPaid',
    pay_status = '$paymentStatus'
WHERE orderid = '$orderid'
";

// UPDATE SALOON ORDERS
$updateSaloon = "
UPDATE saloon_orders
SET 
    status = '$paymentStatus',
    amount_paid = '$newAmountPaid'
WHERE id = '$orderid'
";

// EXECUTE
$creditUpdated = mysqli_query($con, $updateCredit);

$refreshmentUpdated = mysqli_query($con, $updateRefreshments);

$saloonUpdated = mysqli_query($con, $updateSaloon);

if (
    $creditUpdated &&
    $refreshmentUpdated &&
    $saloonUpdated
) {

            $_SESSION['success'] = "Payment added successfully.";
        } else {

            $_SESSION['error'] = "Payment failed.";
        }
    }

    echo "<script>window.location.href='credit_sales.php';</script>";
    exit;
}
?>
<?php
if (! empty($_SESSION['success'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['success']) . "</div>";
    unset($_SESSION['success']);
}
if (! empty($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($_SESSION['error']) . "</div>";
    unset($_SESSION['error']);
}
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Credit Sales</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Orishirishi</li>
    </ol>
</div>
<div class="col-xl-12 col-lg-12 mb-4">
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Credit Sales</h6>
        </div>

        <div class="table-responsive">
            <table class="table align-items-center table-bordered">
                <thead class="thead-light">
                    <tr>
                        <!-- <th>Order ID</th> -->
                        <th>Customer</th>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Amount Paid</th>
                        <th>Total Remaining</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $creditSales = [];
                    $creditSalesSQL = "
SELECT 
    c.orderid,
    c.customer,
    c.status,
    c.item,
    c.item_category,
    c.quantity,
    c.totalprice,
    SUM(c.quantity) AS total_quantity,
    SUM(c.totalprice) AS total_price,
    MAX(c.amount_paid) AS amount_paid,
    COUNT(*) AS total_items,
    MAX(c.unitprice) AS unitprice,
    MAX(c.added_on) AS order_date,
    cu.name,
    cu.email
FROM credit_sales c
INNER JOIN customers cu 
    ON c.customer = cu.unique_id
GROUP BY c.orderid
ORDER BY order_date DESC
";
                    $creditSalesResult = mysqli_query($con, $creditSalesSQL);
                    while ($row = mysqli_fetch_assoc($creditSalesResult)) {
                        $creditSales[] = $row;
                    }
                    if (!count($creditSales) > 0) {
                    ?>
                        <tr>
                            <td colspan="10" style="text-align: center;">No credit sales found.</td>
                        </tr>
                        <?php
                    } else {
                        foreach ($creditSales as $creditSale) {
                        ?>
                            <div class="modal fade"
                                id="makePayment<?= $creditSale['orderid'] ?>"
                                tabindex="-1"
                                aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered mx-3">

                                    <div class="modal-content">

                                        <div class="modal-header" style="background:#000; color:#fff;">

                                            <h5 class="modal-title">
                                                Make payment
                                            </h5>

                                            <button type="button"
                                                class="btn-close btn-close-white"
                                                data-bs-dismiss="modal">
                                            </button>

                                        </div>

                                        <div class="modal-body">

                                            <form method="POST">

                                                <input type="hidden"
                                                    name="orderid"
                                                    value="<?= $creditSale['orderid'] ?>">

                                                <div class="mb-3">

                                                    <label class="mb-2">
                                                        Total Order Amount
                                                    </label>

                                                    <input type="text"
                                                        class="form-control"
                                                        value="₦<?= number_format($creditSale['total_price'], 2) ?>"
                                                        readonly>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="mb-2">
                                                        Amount Paid
                                                    </label>

                                                    <input type="text"
                                                        class="form-control"
                                                        value="₦<?= number_format($creditSale['amount_paid'], 2) ?>"
                                                        readonly>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="mb-2">
                                                        Remaining Balance
                                                    </label>

                                                    <input type="text"
                                                        class="form-control"
                                                        value="₦<?= number_format($creditSale['total_price'] - $creditSale['amount_paid'], 2) ?>"
                                                        readonly>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="mb-2">
                                                        Payment Amount
                                                    </label>

                                                    <input type="number"
                                                        name="payment_amount"
                                                        class="form-control"
                                                        min="1"
                                                        max="<?= $creditSale['total_price'] - $creditSale['amount_paid'] ?>"
                                                        required>

                                                </div>

                                                <button type="submit"
                                                    name="make_payment"
                                                    class="btn btn-primary w-100">

                                                    Submit Payment

                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>
                            <tr>
                                <!-- <td>
                                <?= $creditSale["orderid"] ?>
                            </td> -->
                                <td>
                                    <?= $creditSale["name"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["total_items"] ?> item(s)
                                </td>
                                <td>
                                    <?= $creditSale["item_category"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["unitprice"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["total_quantity"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["total_price"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["amount_paid"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["total_price"] - $creditSale["amount_paid"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["status"] ?>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="dropdown">
                                            Actions <i class="dropdown-toggle"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="view_credit_order.php?orderid=<?= $creditSale['orderid'] ?>"
                                                class="dropdown-item">
                                                View Order
                                            </a>
                                            <?php
                                            if ($creditSale["status"] == 'pending') {
                                            ?>
                                                <a onclick="return confirm('Are you sure you want to confirm this order? This can not be undone.')" href="credit_sales_action.php?action=approve_order&orderid=<?= $creditSale["orderid"] ?>&customer_email=<?= $creditSale["email"] ?>" class="dropdown-item">Approve order</a>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            if ($creditSale["status"] != 'pending' && $creditSale["amount_paid"] < $creditSale["total_price"]) {
                                            ?>

                                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#makePayment<?= $creditSale['orderid'] ?>">Make payment</a>
                                            <?php
                                            }
                                            ?>
                                            <a href="credit_sales_action.php?action=delete_order&orderid=<?= $creditSale["orderid"] ?>&customer_email=<?= $creditSale["email"] ?>" class="dropdown-item"><i class="bi bi-trash text-danger"></i> Delete order</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>