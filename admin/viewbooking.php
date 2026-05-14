<?php
include "header.php";

// Delete
if (isset($_GET['categoryid'])) {
    $order_id = mysqli_real_escape_string($con, $_GET['categoryid']);
    $insert = mysqli_query($con, "UPDATE saloon_orders SET status='completed' WHERE id='$order_id'") or die('Could not connect: ' . mysqli_error($con));

    $sql = "SELECT * FROM appointments a JOIN staff s ON a.staff=s.id WHERE a.id='$order_id'";
    $sql2 = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($sql2)) {
        $staff = $row["staff"];
        $latefee = $row["latefee"];
        $wallet = $row["wallet"];

        if ($latefee > 0) {
            $addtoWallet = $wallet + ($latefee / 2);
            $insert = mysqli_query($con, "UPDATE staff SET wallet='$addtoWallet' WHERE id='$staff'") or die('Could not connect: ' . mysqli_error($con));
        }
    }
    echo "<script>alert('This order has been successfully marked as completed!'); window.location.href = 'viewbooking.php?order=$order_id';</script>";
    exit();
}

if (isset($_GET['order'])) {
    $saloon = mysqli_real_escape_string($con, $_GET['order']);

    $sql = "SELECT s.*, r.discount_added FROM saloon_orders s LEFT JOIN refreshments r ON s.id = r.orderid WHERE id='$saloon'";
    $sql2 = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_array($sql2)) {
        $type = $row["type"];
        $customername = $row["name"];
        $customerphone = $row["phone"];
        $date = $row["date"];
        $subtotal = (float) $row["total_amount"];
        $shipping_fee = (float) ($row["shipping_fee"] ?? 0);
        $shipping_type = $row["shipping_type"] ?? 'pickup';
        $place_details = $row["place_details"] ?? '';
        $method = $row["method"];
        $status = $row["status"];
        $section = $row["section"];

        // Calculate grand total including shipping fee
        $total = ($subtotal + $shipping_fee);
        $total_all = $subtotal - ($subtotal / $row["discount_added"]);

        // Decode place_details for display
        $place_details_data = $place_details ? json_decode($place_details, true) : [];
        $place_description = isset($place_details_data['description']) ? htmlspecialchars($place_details_data['description']) : 'Not specified';

        // Color for status badge
        if ($status == "no") {
            $bg = "badge-warning";
            $status = "booking";
        } else if ($status == "processing") {
            $bg = "badge-primary";
        } else if ($status == "cancelled") {
            $bg = "badge-danger";
        } else if ($status == "processed" || $status == "completed") {
            $bg = "badge-success";
        }
    } else {
        echo "An error occured";
        exit();
    }
} else {
        echo "An error occured";
    // header("location:dashboard.php");
    exit();
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h5 mb-0 text-gray-800">Booking/Order ID #<?php echo htmlspecialchars($saloon); ?></h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Details</li>
    </ol>
</div>

<!-- Row -->
<div class="row justify-content-center mb-8">
    <!-- Datatables -->
    <div class="col-lg-12">
        <p>
            <span class='badge <?php echo $bg; ?>'><?php echo htmlspecialchars($status); ?></span><br>
            Customer Details <br>
            Name: <?php echo htmlspecialchars($customername); ?> <br>
            Phone: <?php echo htmlspecialchars($customerphone); ?> <br>
            <?php if ($shipping_type === 'delivery' && $place_description !== 'Not specified') : ?>
                Delivery Location: <?php echo $place_description; ?><br>
            <?php endif; ?>
            Shipping fee: &#8358;<?php echo $shipping_fee; ?> <br>
        </p>
       

        <p>
            Date: <?php echo htmlspecialchars($date); ?><br>
            Payment Method: <?php echo htmlspecialchars($method); ?>
        </p>

        <div class="card mb-4">
            <?php if ($section == "spa") { ?>
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Services Cart</h6>
                </div>
                <div class="table-responsive p-3">
                    <table class="table align-items-center table-flush text-primary">
                        <thead class="thead-light">
                            <tr>
                                <th>Service</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Staff</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM appointments WHERE id='$saloon' ORDER BY s ASC";
                            $sql2 = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_array($sql2)) {
                                echo "
                                    <tr>
                                        <td>" . htmlspecialchars($row['servicename']) . "</td>
                                        <td>" . htmlspecialchars($row['start_time']) . " - " . htmlspecialchars($row['end_time']) . "</td>
                                        <td>&#8358;" . number_format($row['price'], 2) . "</td>
                                        <td>" . htmlspecialchars($row['staffname']) . "</td>
                                    </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

            <?php
            $bot = "SELECT * FROM refreshments WHERE orderid='$saloon'";
            $bot2 = mysqli_query($con, $bot);
            if (mysqli_affected_rows($con) > 0) {
            ?>
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Refreshment Cart</h6>
                </div>
                <div class="table-responsive p-3">
                    <table class="table align-items-center table-flush text-primary">
                        <thead class="thead-light">
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Discount Added</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM refreshments WHERE orderid='$saloon' ORDER BY s ASC";
                            $sql2 = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_array($sql2)) {
                                $stat = $row['pre_order_complete'] ? 'COMPLETED' : 'PENDING';
                                $class_text = $row['pre_order_complete'] ? 'green-btn' : 'red-btn';
                                echo "
                                    <tr>
                                        <td>" . htmlspecialchars($row['item']) . " " . ($row['preorder'] ? '
                                        <div class=\"pre_order_con\">
                                            <div class=\"pre-order-div\">Pre-order</div>
                                            <div class=\"' . $class_text . '\">' . $stat . '</div>
                                        </div>' : '') . "</td>
                                        <td>&#8358;" . number_format($row['unitprice'], 2) . "</td>
                                        <td>" . htmlspecialchars($row['quantity']) . "</td>
                                        <td>" . htmlspecialchars($row['discount_added']) . "%</td>
                                        <td>&#8358;" . number_format($row['totalprice'] - ($row['totalprice'] / $row['discount_added']), 2) . "</td>
                                    </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

            <br>
            <center>
                <h4 class="font-weight-bold">GRAND TOTAL: &#8358;<?php echo number_format($total_all, 2); ?></h4>
                <?php if ($status == "processed") { ?>
                    <p>
                        <form action='' method='get'
                            onsubmit='return confirm("Are you sure you want to mark this order as completed?");'>
                            <input type='text' name='categoryid' value='<?php echo htmlspecialchars($saloon); ?>' required hidden>
                            <input type='submit' name='delete' value='Mark As Completed' class='btn btn-sm btn-primary'>
                        </form>
                    </p>
                <?php } ?>
            </center>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>