<?php
include "header.php";
if (!empty($_SESSION['success_message'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
    unset($_SESSION['success_message']); // Clear after showing
}
$today = date("Y-m-d");

include "order_logic.php";
?>


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h5 mb-0 text-gray-800">Order ID #<?php echo htmlspecialchars($saloon); ?></h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Orishirishi Transaction</li>
    </ol>
</div>


<!-- Transaction name modal -->
<div class="modal fade" id="saveOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Save Order for Later</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="transaction_name">Enter Transaction Name:</label>
                    <input type="text" name="transaction_name" id="transaction_name" class="form-control"
                        value="<?php echo isset($transaction_name) ? htmlspecialchars($transaction_name) : ''; ?>"
                        required>

                </div>
                <div class="modal-footer">
                    <button type="submit" name="save_for_later" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Display awaing transactions -->
<div class="modal fade" id="awaitingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Awaiting Transactions</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (!empty($awaiting_orders)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Transaction Name</th>
                                <th style="width:180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($awaiting_orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['transaction_name']); ?></td>
                                    <td>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="download_orderid" value="<?php echo $order['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-success small">Resume</button>
                                        </form>
                                        <form method="post" style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this order?');">
                                            <input type="hidden" name="delete_orderid" value="<?php echo $order['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger small">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No awaiting transactions found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="flex_spaced mb-3">
    <div>

        <?php if ($awaiting_count > 0): ?>
            <div class="awaiting-box active_awaiting" data-toggle="modal" data-target="#awaitingModal"
                style="cursor:pointer;">
                <i class="fas fa-layer-group"></i>
                <span class="badge badge-danger"><?php echo $awaiting_count; ?></span>
            </div>

        <?php endif; ?>

    </div>

    <!-- Save for later button -->
    <button type="button" class="btn btn-warning small" data-toggle="modal" data-target="#saveOrderModal">
        Save Order
    </button>

</div>


<!-- Row -->
<div class="row">
    <div align="center" class="col-lg-12">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by item name here...">
        <p>
        <form enctype="multipart/form-data" method="post">
            <table id="results" width="95%" border="0" cellspacing='0'
                style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
                <thead>
                    <th></th>
                    <th></th>
                </thead>
                <tbody id="searchResults"></tbody>
            </table>
        </form>
    </div>

    <?php include "food_cart.php"; ?>

    <script>
        $(document).ready(function () {
            $('#searchInput').on('input', function () {
                var query = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: 'search.php',
                    data: { query: query },
                    success: function (response) {
                        $('#searchResults').html(response);
                    }
                });
            });
        });
    </script>

    <!-- auto clear alert -->
    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                alert.style.transition = "opacity 0.5s";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000); // 3 seconds
    </script>

    <?php
    // Add to cart
    if (isset($_POST['addtocart'])) {
        $item = $_POST['food'];
        $value = $_POST['value'];

        // Fetch item details
        $sqk = "SELECT * FROM food_menu WHERE s = ?";
        $stmt = $con->prepare($sqk);
        $stmt->bind_param("s", $item);
        $stmt->execute();
        $sqlp = $stmt->get_result();
        while ($rowe = $sqlp->fetch_assoc()) {
            $itemname = $rowe['item'];
            $itemprice = $rowe['price'];
        }
        $stmt->close();

        // Check if item already exists in cart
        $bot = "SELECT * FROM refreshments WHERE orderid = ? AND itemid = ?";
        $stmt = $con->prepare($bot);
        $stmt->bind_param("ss", $saloon, $item);
        $stmt->execute();
        $bot2 = $stmt->get_result();

        if ($bot2->num_rows == 0) {
            // Insert new item
            $totalvalue = $value * $itemprice;
            $submit = $con->prepare("INSERT INTO refreshments (orderid, itemid, item, unitprice, quantity, totalprice, status) VALUES (?, ?, ?, ?, ?, ?, 'processing')");
            $submit->bind_param("ssssid", $saloon, $item, $itemname, $itemprice, $value, $totalvalue);
            $submit->execute() or die('Could not connect: ' . mysqli_error($con));
            $submit->close();
        } else {
            // Update existing item
            $sqk = "SELECT * FROM refreshments WHERE orderid = ? AND itemid = ?";
            $stmt = $con->prepare($sqk);
            $stmt->bind_param("ss", $saloon, $item);
            $stmt->execute();
            $sqlp = $stmt->get_result();
            while ($rowe = $sqlp->fetch_assoc()) {
                $quantity = $rowe['quantity'];
                $rowfood = $rowe['s'];
            }
            $stmt->close();

            $newquantity = $quantity + $value;
            $totalvalue = $newquantity * $itemprice;

            $insert = $con->prepare("UPDATE refreshments SET quantity = ?, unitprice = ?, totalprice = ? WHERE s = ?");
            $insert->bind_param("idss", $newquantity, $itemprice, $totalvalue, $rowfood);
            $insert->execute() or die('Could not connect: ' . mysqli_error($con));
            $insert->close();
        }

        header("location:orderfood.php");
        exit();
    }



    include "footer.php";
    ?>