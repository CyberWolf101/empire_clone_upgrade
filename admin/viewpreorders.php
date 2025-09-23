<?php
include "header.php";

// Add pre_order_complete column to refreshments table if it doesn't exist
$checkColumnQuery = "SHOW COLUMNS FROM refreshments LIKE 'pre_order_complete'";
if (mysqli_num_rows(mysqli_query($con, $checkColumnQuery)) == 0) {
    $alterQuery = "ALTER TABLE refreshments ADD pre_order_complete TINYINT(1) DEFAULT 0";
    if (!mysqli_query($con, $alterQuery)) {
        error_log("Failed to add pre_order_complete column: " . mysqli_error($con));
        echo "<script>alert('Error updating refreshments table: " . addslashes(mysqli_error($con)) . "');</script>";
    }
}

// Handle pre-order completion
if (isset($_GET['itemid']) && !empty($_GET['itemid'])) {
    $item_id = mysqli_real_escape_string($con, $_GET['itemid']);
    $sql = "UPDATE refreshments SET pre_order_complete = 1 WHERE s = ? AND preorder = 1";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>alert('Pre-order marked as completed!');</script>";
            header("Location: viewpreorders.php");
        } else {
            echo "<script>alert('No rows updated. Item may not be a pre-order or already completed.');</script>";
            header("Location: viewpreorders.php");
        }
    } else {
        $error = mysqli_error($con);
        echo "<script>alert('Error updating pre-order status: " . addslashes($error) . "');</script>";
        header("Location: viewpreorders.php");
    }
    mysqli_stmt_close($stmt);
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Pre-Orders</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pre-Orders</li>
    </ol>
</div>

<!-- Pre-Orders Table -->
<div class="col-xl-12 col-lg-12 mb-4">
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Pre-Order Items</h6>
        </div>
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>SN</th>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Total Price</th>
                        <th>Pre-Order Status</th>
                        <th>View</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Pagination settings
                    $limit = 30;
                    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                    if ($page < 1)
                        $page = 1;

                    // Count query
                    $count_query = "SELECT COUNT(*) AS total_rows 
                                    FROM refreshments r 
                                    JOIN saloon_orders so ON r.orderid = so.id 
                                    WHERE r.preorder = 1 
                                    AND so.section = 'refreshments' 
                                    AND so.type = 'online'";
                    $count_stmt = mysqli_prepare($con, $count_query);
                    mysqli_stmt_execute($count_stmt);
                    $count_result = mysqli_stmt_get_result($count_stmt) or die("Database error: " . mysqli_error($con));
                    $total_rows = mysqli_fetch_assoc($count_result)['total_rows'];
                    mysqli_stmt_close($count_stmt);

                    $total_pages = ceil($total_rows / $limit);
                    if ($page > $total_pages)
                        $page = $total_pages;
                    $offset = ($page - 1) * $limit;

                    // Main query
                    $sql = "SELECT r.s, r.orderid, r.item, r.quantity, r.totalprice, r.pre_order_complete, so.name, so.date
                            FROM refreshments r 
                            JOIN saloon_orders so ON r.orderid = so.id 
                            WHERE r.preorder = 1 
                            AND so.section = 'refreshments' 
                            AND so.type = 'online' 
                            ORDER BY so.date DESC
                            LIMIT ? OFFSET ?";
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
                    mysqli_stmt_execute($stmt);
                    $resultset = mysqli_stmt_get_result($stmt) or die("Database error: " . mysqli_error($con));

                    $i = ($page - 1) * $limit + 1;
                    while ($row = mysqli_fetch_assoc($resultset)) {
                        $item_id = htmlspecialchars($row['s'], ENT_QUOTES, 'UTF-8');
                        $order_id = htmlspecialchars($row['orderid'], ENT_QUOTES, 'UTF-8');
                        $preOrderStatus = $row['pre_order_complete'] ? 'Completed' : 'Pending';
                        $statusBadge = $row['pre_order_complete'] ? 'badge-success' : 'badge-warning';
                        $disabled = $row['pre_order_complete'] ? 'disabled' : '';

                        // Check if orderid exists in bank_transfers as item_id
                        $transfer_query = "SELECT id FROM bank_transfers WHERE item_id = ?";
                        $transfer_stmt = mysqli_prepare($con, $transfer_query);
                        mysqli_stmt_bind_param($transfer_stmt, "s", $order_id);
                        mysqli_stmt_execute($transfer_stmt);
                        $transfer_result = mysqli_stmt_get_result($transfer_stmt);
                        $isPendingTransfer = mysqli_num_rows($transfer_result) > 0;
                        mysqli_stmt_close($transfer_stmt);
                        $date = isset($row['date']) ? date('d/m/Y', strtotime($row['date'])) : '-';

                        echo "<tr>
                            <td>$i</td>
                            <td><a href='viewbooking.php?order=$order_id'>$order_id</a></td>
                            <td>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>" . htmlspecialchars($row['item'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>" . htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>" . $date . "</td>
                            <td>&#8358;" . htmlspecialchars($row['totalprice'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td><span class='badge $statusBadge'>$preOrderStatus</span></td>
                            <td><button class='btn btn-dark btn-sm' onclick='printPreOrderReceipt(\"$item_id\")'>Print Receipt</button></td>
                            <td>" . ($isPendingTransfer || $row['pre_order_complete'] ? '' : "<button class='btn btn-success btn-sm' $disabled onclick='confirmPreOrderComplete(\"$item_id\")'>Complete</button>") . "</td>
                        </tr>";
                        $i++;
                    }
                    mysqli_stmt_close($stmt);
                    ?>
                </tbody>
            </table>
        </div>

        <?php
        // Pagination links
        if ($total_pages > 1) {
            $max_links = 5;
            $start = max(1, $page - floor($max_links / 2));
            $end = min($total_pages, $start + $max_links - 1);

            echo "<nav><ul class='pagination justify-content-center'>";
            if ($page > 1) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Prev</a></li>";
            }
            for ($i = $start; $i <= $end; $i++) {
                $active = ($i == $page) ? 'active' : '';
                echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>$i</a></li>";
            }
            if ($page < $total_pages) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Next</a></li>";
            }
            echo "</ul></nav>";
        }
        ?>
        <div class="card-footer"></div>
    </div>
</div>

<script>
    function printPreOrderReceipt(itemId) {
        console.log('Print receipt button clicked for item ID: ' + itemId); // Debugging
        window.location.href = 'print_preorder_receipt.php?itemid=' + encodeURIComponent(itemId);
    }
    function confirmPreOrderComplete(itemId) {
        console.log('Pre-order complete button clicked for item ID: ' + itemId); // Debugging
        if (confirm("Are you sure you want to mark this pre-order as completed?")) {
            window.location.href = 'viewpreorders.php?itemid=' + encodeURIComponent(itemId);
        }
    }
</script>

<?php include "footer.php"; ?>