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

                    $sql = mysqli_query($con,"
                        SELECT
                            br.*,
                            bg.item AS guide_name
                        FROM bakers_requests br
                        LEFT JOIN bakers_guide bg
                            ON bg.guide_id = br.guide_id
                        ORDER BY br.id DESC
                    ");

                    while($row = mysqli_fetch_assoc($sql))
                    {
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
                            if($s === "completed" || $s === "collected") {
                                echo '<span class="badge badge-success" style="text-transform:capitalize;">Completed</span>';
                            } elseif($s === "approved") {
                                echo '<span class="badge badge-info" style="text-transform:capitalize;">Approved</span>';
                            } elseif($s === "rejected") {
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
                            if ($isAdmin && !in_array($s, ['approved','rejected','completed'])) {
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
// ...existing code...