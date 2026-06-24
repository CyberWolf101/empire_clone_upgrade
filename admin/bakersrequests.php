<?php
// ...existing code...
include "header.php";
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Bakers Requests</h1>

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="dashboard.php">Home</a>
        </li>
        <li class="breadcrumb-item active">
            Bakers Requests
        </li>
    </ol>
</div>

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
                            br.approved_status,
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
                                } elseif ($s === "partially collected") {
                                    echo '<span class="badge badge-primary" style="text-transform:capitalize;">Partially Collected</span>';
                                } else {
                                    echo '<span class="badge badge-warning" style="text-transform:capitalize;">Pending</span> ';
                                }
                                ?>

                            </td>

                            <td>

                                <div class="dropdown">
                                    <button class="dropdown-toggle btn btn-primary" data-bs-toggle="dropdown" class="btn btn-primary">Actions</button>
                                    <div class="dropdown-menu">
                                        <a href="viewrequest.php?id=<?= urlencode($row['id']); ?>"
                                            class="dropdown-item">
                                            View
                                        </a>
                                        <?php
                                        // $status is the current user's role variable from header.php
                                        $isAdmin = isset($status) && ($status === "superadmin" || $status === "admin");

                                        // Show Approve button only to admin and when not already approved/rejected/completed
                                        if ($isAdmin && $row["approved_status"] != 'approved') {
                                        ?>
                                            <a href="approve_request.php?id=<?= urlencode($row['id']); ?>"
                                                onclick="return confirm('Approve this request?');"
                                                class="dropdown-item text-success">
                                                Approve
                                            </a>
                                            <a href="approve_request.php?id=<?= urlencode($row['id']); ?>&reject=1"
                                                onclick="return confirm('Reject this request?');"
                                                class="dropdown-item text-danger">
                                                Reject
                                            </a>
                                        <?php
                                        }

                                        // Show Collect button only when approved (bakers) or admin can always collect
                                        if ($row["approved_status"] === 'approved' && $isAdmin && $s != 'completed') {
                                        ?>
                                            <a href="collect_request.php?id=<?= urlencode($row['id']); ?>"
                                                onclick="return confirm('Mark as collected?');"
                                                class="dropdown-item">
                                                Mark Collected
                                            </a>
                                        <?php
                                        } else {
                                            // not approved and not admin: show disabled indicator
                                            if (!$isAdmin) {
                                                echo '<a class="dropdown-item" disabled>Awaiting Approval</a>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
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