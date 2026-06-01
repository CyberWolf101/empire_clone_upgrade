<?php
include "header.php";
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["make_payment"])) {
    $orderid = $_POST["orderid"];
    $fileUrl = $_POST["fileUrl"];
    $amount = $_POST["amount"];
    $method = $_POST["method"];
    $amountPaid = 0;
    $selectPaid = "SELECT SUM(amount_paid) as paid_amount FROM credit_sales WHERE orderid = '$orderid'";
    $res = mysqli_query($con, $selectPaid);
    while ($ress = mysqli_fetch_assoc($res)) {
        $amountPaid += $ress["paid_amount"];
    }
    $newAmountToPay = $amountPaid + $amount;
    $totalAmount = 0;

$getTotalSql = "
SELECT SUM(totalprice) AS total_amount
FROM credit_sales
WHERE orderid = '$orderid'
";

$getTotalResult = mysqli_query($con, $getTotalSql);

$getTotalRow = mysqli_fetch_assoc($getTotalResult);

$totalAmount = (float)($getTotalRow['total_amount'] ?? 0);
    if ($newAmountToPay <= $totalAmount) {
        $makePayment = "INSERT INTO credit_sales_transfers(orderid,fileUrl,amount_paid,method) VALUES ('$orderid','$fileUrl','$amount','$method')";
        mysqli_query($con, $makePayment) ? $_SESSION["success"] = "Payment successful" : $_SESSION["error"] = "Error occured while making payment";
        /*$creditSalesWithId = [];
        $selectCreditSalesWithId = "SELECT * FROM credit_sales WHERE orderid = '$orderid'";
        $result = mysqli_query($con, $selectCreditSalesWithId);
        while ($row = mysqli_fetch_assoc($result)) {
            $creditSalesWithId[] = $row;
        }*/
        
        // FIXED: Distribute payment proportionally across products based on their price share
        // Calculate total price for the order
        // $totalOrderPrice = 0;
        // foreach ($creditSalesWithId as $item) {
        //     $totalOrderPrice += (float)$item['totalprice'];
        // }
        
        // Distribute payment proportionally to each item
        // if ($totalOrderPrice > 0) {
        //     foreach ($creditSalesWithId as $item) {
        //         // Calculate this item's proportion of the total price
        //         $itemProportion = (float)$item['totalprice'] / $totalOrderPrice;
                
        //         // Calculate proportional payment for this item
        //         $proportionalPayment = $newAmountToPay * $itemProportion;
                
        //         // Round to 2 decimal places (normal currency format)
        //         $proportionalPayment = round($proportionalPayment, 2);
                
        //         // Determine status based on payment completion
        //         $itemStatus = $proportionalPayment >= (float)$item['totalprice'] ? 'paid' : 'partly paid';
                
        //         // Update amount_paid and status for this item
        //         $itemId = $item['id']; // Assuming 's' is the primary key
        //         $updateCreditSales = "UPDATE credit_sales SET amount_paid = $proportionalPayment, status = '$itemStatus' WHERE id = '$itemId'";
        //         mysqli_query($con, $updateCreditSales);
        //     }
        // }
    }
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
    (
SELECT COALESCE(SUM(t.amount_paid),0)
FROM credit_sales_transfers t
WHERE t.orderid = c.orderid
AND t.status = 'paid'
) AS amount_paid,
    COUNT(*) AS total_items,
    MAX(c.unitprice) AS unitprice,
    MAX(c.added_on) AS order_date,
    GROUP_CONCAT(DISTINCT c.item_category SEPARATOR ', ') AS item_categories,
    cu.name,
    cu.email
FROM credit_sales c
INNER JOIN customers cu 
    ON c.customer = cu.unique_id OR c.customer = cu.name
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
                                                <!-- Fields are: orderid,fileUrl, amount, method-->

                                                <input type="hidden"
                                                    name="orderid"
                                                    value="<?= $creditSale['orderid'] ?>">
                                                <input type="hidden" name="fileUrl" value="">

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
                                                        name="amount"
                                                        class="form-control"
                                                        min="1"
                                                        max="<?= $creditSale['total_price'] - $creditSale['amount_paid'] ?>"
                                                        required>

                                                </div>
                                                <div class="mb-3">

                                                    <label class="mb-2">
                                                        Payment Method
                                                    </label>

                                                    <select name="method" id="" class="form-control">
                                                        <option value="Cash" selected>Cash</option>
                                                        <option value="P.O.S">P.O.S</option>
                                                        <option value="Bank Transfer">Bank Transfer</option>
                                                    </select>

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
                                            <!-- <a href="view_credit_order.php?orderid=<?= $creditSale['orderid'] ?>"
                                                class="dropdown-item">
                                                View Order
                                            </a> -->
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

<div class="col-xl-12 col-lg-12 mb-4">
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Pending Credit Sales Transfers</h6>
        </div>
        <div class="table-responsive">
            <table class="table align-items-center table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Transfer ID</th>
                        <th>Order ID</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Bank</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $transfers = [];
                    $tsql = "SELECT * FROM credit_sales_transfers WHERE status !='paid' ORDER BY transfer_date DESC";
                    $tres = mysqli_query($con, $tsql);
                    while ($trow = mysqli_fetch_assoc($tres)) {
                        $transfers[] = $trow;
                    }

                    if (!count($transfers)) {
                        echo '<tr><td colspan="8" class="text-center">No transfers found</td></tr>';
                    } else {
                        foreach ($transfers as $t) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($t['id']) . '</td>';
                            echo '<td>' . htmlspecialchars($t['orderid']) . '</td>';
                            echo '<td>&#8358; ' . number_format((float)$t['amount_paid'], 2) . '</td>';
                            echo '<td>' . htmlspecialchars($t['method']) . '</td>';
                            echo '<td>' . htmlspecialchars($t['bank'] ?? '') . '</td>';
                            echo '<td>' . (!empty($t['fileUrl']) ? '<a href="' . htmlspecialchars($t['fileUrl']) . '" target="_blank">View</a>' : '-') . '</td>';
                            echo '<td>' . htmlspecialchars($t['status'] ?? 'pending') . '</td>';
                            echo '<td>';
                            echo '<a class="btn btn-sm btn-success me-2" href="credit_sales_action.php?action=mark_transfer_paid&transfer_id=' . urlencode($t['id']) . '">Mark Paid</a>';
                            echo '<a class="btn btn-sm btn-danger me-2" href="credit_sales_action.php?action=delete_transfer&transfer_id=' . urlencode($t['id']) . '">Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>