<?php
include "header.php";
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
    <div>
        <a href="#pendingTransfers" class="btn btn-sm btn-warning me-2">Pending Transfers</a>
        <ol class="breadcrumb d-inline-flex align-items-center mb-0">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Orishirishi</li>
        </ol>
    </div>
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

                                            <form method="POST" action="make_credit_sale_payment.php">
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

<div class="col-xl-12 col-lg-12 mb-4" id="pendingTransfers">
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
                        <th>Proof</th>
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
                    ?>
                        <tr>
                            <td colspan="8" class="text-center">No transfers found</td>
                        </tr>
                        <?php
                    } else {
                        foreach ($transfers as $t) {
                        ?>
                            <tr>
                                <td><?= $t['id']  ?></td>
                                <td><?= $t['orderid']  ?></td>
                                <td>&#8358;<?= number_format((float)$t['amount_paid'], 2)  ?></td>
                                <td><?= htmlspecialchars($t['method'])  ?></td>
                                <td><?= htmlspecialchars($t['bank'] ?? '')  ?></td>
                                <td><?php
                                    if (!empty($t["fileUrl"])) {
                                    ?>
                                        <a class="btn btn-sm btn-info" href="<?= htmlspecialchars($t['fileUrl'])  ?>" target="_blank">View Proof</a>
                                    <?php
                                    } else {
                                    ?>
                                        -
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($t['status'] ?? 'pending') ?></td>
                                <td>
                                    <a class="btn btn-sm btn-secondary me-2" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#transferInfoModal<?= htmlspecialchars($t['id'])  ?>">Details</a>
                                    <a class="btn btn-sm btn-success me-2" href="credit_sales_action.php?action=mark_transfer_paid&transfer_id=<?= urlencode($t['id']) ?>">Mark Paid</a>
                                    <a class="btn btn-sm btn-danger me-2" href="credit_sales_action.php?action=delete_transfer&transfer_id=<?= urlencode($t['id']) ?>">Delete</a>
                                </td>
                            </tr>
                            <div class="modal fade" id="transferInfoModal<?= htmlspecialchars($t['id']) ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Transfer Info #<?= htmlspecialchars($t['id'])  ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Order ID:</strong> <?= htmlspecialchars($t['orderid']) ?></p>
                                            <p><strong>Amount:</strong> &#8358; <?= number_format((float)$t['amount_paid'], 2) ?></p>
                                            <p><strong>Method:</strong> <?= htmlspecialchars($t['method']) ?></p>
                                            <p><strong>Bank:</strong> <?= htmlspecialchars($t['bank'] ?? '-') ?></p>
                                            <p><strong>Status:</strong> <?= htmlspecialchars($t['status'] ?? 'pending') ?></p>
                                            <p><strong>Proof:</strong>
                                                <?php
                                                if (!empty($t['fileUrl'])) {
                                                ?>
                                                    <a href="<?= htmlspecialchars($t['fileUrl']) ?>" target="_blank">Open transfer proof</a>
                                                <?php
                                                } else {
                                                ?>
                                            <p>No proof attached.</p>
                                        <?php
                                                }
                                        ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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