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
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $creditSales = [];
                    $creditSalesSQL = "SELECT c.*,cu.* FROM credit_sales c INNER JOIN customers cu ON c.customer = cu.unique_id";
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
                            <tr>
                                <!-- <td>
                                <?= $creditSale["orderid"] ?>
                            </td> -->
                                <td>
                                    <?= $creditSale["customer"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["item"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["item_category"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["unitprice"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["quantity"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["totalprice"] ?>
                                </td>
                                <td>
                                    <?= $creditSale["amount_paid"] ?>
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
                                            <a href="approve_credit_sale.php?orderid=<?= $creditSale["orderid"] ?>&customer_email=<?= $creditSale["email"] ?>" class="dropdown-item">Approve order</a>
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