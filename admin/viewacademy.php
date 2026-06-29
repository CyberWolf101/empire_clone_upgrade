<?php include "header.php"; ?>

<?php
/// Mark as completed
if (isset($_GET['categoryid'])) {
    $order_id = $_GET['categoryid'];

    mysqli_query($con, "
        UPDATE saloon_orders 
        SET status='completed' 
        WHERE id='$order_id'
    ") or die(mysqli_error($con));

    echo "<script>
        alert('Training successfully marked as completed!');
        window.location.href = 'viewacademy.php?order=$order_id';
    </script>";
    exit();
}

/* -----------------------------
   GET ORDER INFO
------------------------------*/
if (!isset($_GET['order'])) {
    header("location:dashboard.php");
    exit();
}

$saloon = $_GET['order'];

$orderQuery = mysqli_query($con, "
    SELECT * FROM saloon_orders WHERE id='$saloon'
");

$order = mysqli_fetch_assoc($orderQuery);

$customername = $order['name'];
$customerphone = $order['phone'];
$email = $order['email'];
$date = $order['date'];
$stats = $order['status'];

if ($stats == "no") {
    $bg = "badge-warning";
    $statsText = "booking";
} elseif ($stats == "processing") {
    $bg = "badge-primary";
    $statsText = "processing";
} elseif ($stats == "cancelled") {
    $bg = "badge-danger";
    $statsText = "cancelled";
} else {
    $bg = "badge-success";
    $statsText = "completed";
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h5 mb-0 text-gray-800">Academy ID #<?= $saloon ?></h1>
</div>

<p>
    <span class="badge <?= $bg ?>"><?= $statsText ?></span><br>
    Name: <?= $customername ?><br>
    Email: <?= $email ?><br>
    Phone: <?= $customerphone ?><br>
    Date: <?= $date ?>
</p>

<?php
/* =========================================================
   1. GET UNIQUE TRAINING ITEMS (FIXED DUPLICATION ISSUE)
========================================================= */
$trainingQuery = mysqli_query($con, "
    SELECT DISTINCT
        a.training,
        a.trainingname,
        a.price,
        a.discount_applied,
        t.discount_added
    FROM academy_cart a
    LEFT JOIN training t ON a.training = t.id
    WHERE a.id = '$saloon'
");

$trainings = [];

$training_total = 0;
$discount_percent = 0;

while ($row = mysqli_fetch_assoc($trainingQuery)) {
    $trainings[] = $row;

    // each training counted ONCE
    $training_total += $row['price'];

    if ($row['discount_applied'] == "true") {
        $discount_percent = $row['discount_added'];
    }
}

/* =========================================================
   2. TRAINING ITEMS TOTAL (ADD-ONS)
========================================================= */
$itemQuery = mysqli_query($con, "
    SELECT SUM(t.price) AS total
    FROM academy_cart_training_items a
    JOIN training_items t ON t.item_id = a.training_item_id
    WHERE a.item_for = '$saloon'
");

$itemRow = mysqli_fetch_assoc($itemQuery);
$items_total = $itemRow['total'] ?? 0;

/* =========================================================
   3. FINAL CALCULATION (CORRECT)
========================================================= */

$subtotal = $training_total + $items_total;

$discount_amount = ($discount_percent > 0)
    ? ($training_total * $discount_percent / 100)
    : 0;

$grand_total = $subtotal - $discount_amount;
?>

<!-- ================= TRAINING TABLE ================= -->
<div class="card">
    <div class="card-header">
        <h6>Training Cart</h6>
    </div>

    <div class="table-responsive p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Training</th>
                    <th>Price</th>
                    <th>Discount</th>
                </tr>
            </thead>

            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($trainings as $row) { ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $row['trainingname'] ?></td>
                        <td>₦<?= number_format($row['price'], 2) ?></td>
                        <td>
                            <?= $row['discount_applied'] == "true"
                                ? $row['discount_added']
                                : 0 ?>%
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- ================= GRAND TOTAL ================= -->
        <div style="text-align:right; margin-top:20px; font-size:18px; font-weight:bold;">
            Subtotal: ₦<?= number_format($subtotal, 2) ?><br>
            Discount: ₦<?= number_format($discount_amount, 2) ?><br>
            <hr>
            GRAND TOTAL: ₦<?= number_format($grand_total, 2) ?>
        </div>
    </div>
</div>

<!-- ================= TRAINING ITEMS ================= -->
<div class="card mt-4">
    <div class="card-header">
        <h6>Bought Training Items</h6>
    </div>

    <div class="table-responsive p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Item Name</th>
                    <th>Price</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $query = mysqli_query($con, "
                    SELECT t.id, t.name, t.price
                    FROM training_items t
                    LEFT JOIN academy_cart_training_items a
                        ON a.training_item_id = t.item_id
                    WHERE a.item_for = '$saloon'
                    GROUP BY t.name
                ");

                $i = 1;
                while ($rr = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $rr['name'] ?></td>
                        <td>₦<?= number_format($rr['price'], 2) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "footer.php"; ?>