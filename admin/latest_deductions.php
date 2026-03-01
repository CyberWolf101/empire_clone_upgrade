<?php
if (!isset($con)) {
    die("Database connection not found.");
}

// Optional product filter
$productFilter = "";
if (isset($product)) {
    $safeProduct = mysqli_real_escape_string($con, $product);
    $productFilter = "AND cih.product = '$safeProduct'";
}

// Fetch latest 5 records
$sql = "SELECT cih.*, cd.name
        FROM chb_inventory_history cih
        LEFT JOIN chb_inventory ci ON cih.product = ci.product
        LEFT JOIN chb_inventory_department cd ON ci.department = cd.s
        ORDER BY cih.date DESC
        LIMIT 5";

$result = mysqli_query($con, $sql);
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Inventory actions</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-items-center table-flush text-primary">
                <thead class="thead-light">
                    <tr>
                        <th>Action</th>
                        <th>Item</th>
                        <th>Department</th>
                        <th>Quantity</th>
                        <th>Qty Left</th>
                        <th>Deducted By</th>
                        <th>Given To</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {

                            $act = $row['action'];
                            $badge = ($act == "added") ? "badge-success" : "badge-danger";

                            echo "<tr>
                                <td><span class='badge $badge'>" . htmlspecialchars($act) . "</span></td>
                                <td>" . htmlspecialchars($row['productname']) . "</td>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . (int)$row['quantity'] . "</td>
                                <td>" . (int)$row['total_left'] . "</td>
                                <td>" . htmlspecialchars($row['deducted_by']) . "</td>
                                <td>" . htmlspecialchars($row['collected_by']) . "</td>
                                <td>" . date('d M Y H:i', strtotime($row['date'])) . "</td>
                                <td>";

                           

                            echo "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No recent deductions found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>