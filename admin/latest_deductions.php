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

<?php
if ($status != "baker") {
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
                                <td>" . (int) $row['quantity'] . "</td>
                                <td>" . (int) $row['total_left'] . "</td>
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
<?php
} else {
?>
    <?php
    // ...existing code...
    // pending bakers requests (not yet accepted/approved/rejected/collected)
    $pending_bakers_sql = "
    SELECT COUNT(*) AS cnt
    FROM bakers_requests
    WHERE LOWER(TRIM(COALESCE(status,''))) NOT IN ('approved','rejected','collected','completed')
";
    $pending_bakers_count = 0;
    $res = mysqli_query($con, $pending_bakers_sql);
    if ($res) {
        $pending_bakers_count = (int) mysqli_fetch_assoc($res)['cnt'];
    }

    // ...existing code...
    ?>
    <!-- Pending bakers requests icon -->

    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Request Tracking
            </h6>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="thead-light">
                        <tr>
                            <th>Request Code</th>
                            <th>Guide</th>
                            <th>Requested By</th>
                            <th>Date Requested</th>
                            <th>Status</th>
                            <th width="200">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        $sql = mysqli_query($con, "
                        SELECT
                            br.*,
                            bg.item AS guide_name
                        FROM bakers_requests br
                        LEFT JOIN bakers_guide bg
                            ON bg.guide_id = br.guide_id
                        ORDER BY br.id DESC
                    ");

                        while ($row = mysqli_fetch_assoc($sql)) {
                        ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($row['request_code']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['guide_name']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['requested_by']); ?>
                                </td>

                                <td>
                                    <?= date(
                                        "d M Y h:i A",
                                        strtotime($row['requested_on'])
                                    ); ?>
                                </td>

                                <td>

                                    <?php
                                    $s = strtolower($row['status']);
                                    if ($s === "completed" || $s === "collected") {
                                        echo '<span class="badge badge-success" style="text-transform:capitalize;">Completed</span>';
                                    } elseif ($s === "approved") {
                                        echo '<span class="badge badge-info" style="text-transform:capitalize;">Approved</span>';
                                    } elseif ($s === "rejected") {
                                        echo '<span class="badge badge-danger" style="text-transform:capitalize;">Rejected</span>';
                                    } else {
                                        echo '<span class="badge badge-warning" style="text-transform:capitalize;">Pending</span>';
                                    }
                                    ?>

                                </td>

                                <td>

                                    <a href="viewrequest.php?id=<?= urlencode($row['id']); ?>"
                                        class="btn btn-sm btn-primary">
                                        View
                                    </a>

                                    <?php
                                    // $status is the current user's role variable from header.php
                                    $isAdmin = isset($status) && ($status === "superadmin" || $status === "admin");

                                    // Show Approve button only to admin and when not already approved/rejected/completed
                                    if ($isAdmin && !in_array($s, ['approved', 'rejected', 'completed'])) {
                                    ?>
                                        <a href="approve_request.php?id=<?= urlencode($row['id']); ?>"
                                            onclick="return confirm('Approve this request?');"
                                            class="btn btn-sm btn-success">
                                            Approve
                                        </a>
                                        <a href="approve_request.php?id=<?= urlencode($row['id']); ?>&reject=1"
                                            onclick="return confirm('Reject this request?');"
                                            class="btn btn-sm btn-danger">
                                            Reject
                                        </a>
                                    <?php
                                    }

                                    // Show Collect button only when approved (bakers) or admin can always collect
                                    if ($s === 'approved' || $isAdmin) {
                                    ?>
                                        <a href="collect_request.php?id=<?= urlencode($row['id']); ?>"
                                            onclick="return confirm('Mark as collected?');"
                                            class="btn btn-sm btn-primary">
                                            Mark Collected
                                        </a>
                                    <?php
                                    } else {
                                        // not approved and not admin: show disabled indicator
                                        if (!$isAdmin) {
                                            echo '<button class="btn btn-sm btn-secondary" disabled>Awaiting Approval</button>';
                                        }
                                    }
                                    ?>

                                </td>

                            </tr>

                        <?php
                        }
                        ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
    <?php include "footer.php"; ?>
<?php
}
?>