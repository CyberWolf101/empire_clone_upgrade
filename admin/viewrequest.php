<?php
// ...existing code...
include "header.php";

if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$request_id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| REQUEST HEADER
|--------------------------------------------------------------------------
*/
$requestSql = mysqli_query($con, "
    SELECT
        br.*,
        bg.item AS guide_name
    FROM bakers_requests br
    LEFT JOIN bakers_guide bg
        ON bg.guide_id = br.guide_id
    WHERE br.id = '$request_id'
");

if (mysqli_num_rows($requestSql) == 0) {
    die("Request not found");
}

$request = mysqli_fetch_assoc($requestSql);

// normalize status and compute permissions
$status_l = strtolower(trim((string)($request['status'] ?? '')));
$isAdmin = isset($status) && ($status === "superadmin" || $status === "admin");
$canCollect = $isAdmin || ($status_l === 'approved');

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        Request <?= htmlspecialchars($request['request_code']); ?>
    </h1>

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="dashboard.php">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="bakersrequests.php">Bakers Requests</a>
        </li>
        <li class="breadcrumb-item active">
            View Request
        </li>
    </ol>
</div>

<div class="card shadow mb-4">

    <div class="card-header">
        <strong>Request Details</strong>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-3">
                <strong>Request Code</strong><br>
                <?= htmlspecialchars($request['request_code']); ?>
            </div>

            <div class="col-md-3">
                <strong>Guide</strong><br>
                <?= htmlspecialchars($request['guide_name']); ?>
            </div>

            <div class="col-md-3">
                <strong>Requested By</strong><br>
                <?= htmlspecialchars($request['requested_by']); ?>
            </div>

            <div class="col-md-3">
                <strong>Status</strong><br>

                <?php
                if (in_array($status_l, ['collected','completed'])) {
                    echo '<span class="badge badge-success" style="text-transform:capitalize;">Collected</span>';
                } elseif ($status_l === 'approved') {
                    echo '<span class="badge badge-info" style="text-transform:capitalize;">Approved</span>';
                } elseif ($status_l === 'rejected') {
                    echo '<span class="badge badge-danger" style="text-transform:capitalize;">Rejected</span>';
                } else {
                    echo '<span class="badge badge-warning" style="text-transform:capitalize;">Pending</span>';
                }
                ?>

                <?php
                // show approval metadata when available
                if (!empty($request['approved_by']) || !empty($request['approved_on'])) {
                    echo '<div class="small text-muted mt-1">';
                    if (!empty($request['approved_by'])) {
                        echo 'Approved by: ' . htmlspecialchars($request['approved_by']) . '<br>';
                    }
                    if (!empty($request['approved_on'])) {
                        echo 'On: ' . date("d M Y h:i A", strtotime($request['approved_on']));
                    }
                    echo '</div>';
                }
                ?>

            </div>

        </div>

    </div>

</div>

<form method="POST" action="process_bakers_request.php">

    <input type="hidden"
           name="request_id"
           value="<?= $request_id; ?>">

    <div class="card shadow">

        <div class="card-header">
            <strong>Requested Ingredients</strong>
        </div>

        <div class="card-body">

            <?php if (!$canCollect && !$isAdmin): ?>
                <div class="alert alert-warning">
                    This request must be approved by an admin before collection can be processed.
                </div>
            <?php endif; ?>

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Ingredient</th>
                            <th>Requested Qty</th>
                            <th>Collected Qty</th>
                            <th>Available Stock</th>
                            <th>Collect Now</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        $items = mysqli_query($con, "
                            SELECT
                                bri.*,
                                ci.productname,
                                ci.inventory AS stock_qty
                            FROM bakers_request_items bri
                            LEFT JOIN chb_inventory ci
                                ON ci.product = bri.item_id
                            WHERE bri.request_id = '$request_id'
                        ");

                        while ($item = mysqli_fetch_assoc($items)) {

                            $remaining =
                                $item['quantity']
                                - $item['collected_quantity'];

                            // disable inputs if user cannot collect
                            $input_attrs = $canCollect ? '' : 'disabled';
                        ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($item['productname']); ?>
                                </td>

                                <td>
                                    <?= $item['quantity']; ?>
                                </td>

                                <td>
                                    <?= $item['collected_quantity']; ?>
                                </td>

                                <td>
                                    <?= $item['stock_qty']; ?>
                                </td>

                                <td>

                                    <?php if ($remaining > 0) { ?>

                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               max="<?= $remaining; ?>"
                                               class="form-control"
                                               name="collect_qty[<?= $item['id']; ?>]"
                                               placeholder="0"
                                               <?= $input_attrs ?>>

                                    <?php } else { ?>

                                        <span class="text-success">
                                            Fully Collected
                                        </span>

                                    <?php } ?>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer text-right">

            <?php if (in_array($status_l, ['collected','completed'])): ?>

                <button type="button" class="btn btn-secondary" disabled>Already Collected</button>

            <?php else: ?>

                <?php if ($status_l === 'approved'): ?>
                    <button type="submit" class="btn btn-success">Process Collection</button>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary" disabled>Awaiting Approval</button>
                <?php endif; ?>

                <a href="bakersrequests.php" class="btn btn-secondary">Back</a>

                <?php if ($isAdmin && !in_array($status_l, ['approved','rejected','collected','completed'])): ?>
                    <a href="approve_request.php?id=<?= urlencode($request_id); ?>"
                       onclick="return confirm('Approve this request?');"
                       class="btn btn-success">Approve</a>
                    <a href="approve_request.php?id=<?= urlencode($request_id); ?>&reject=1"
                       onclick="return confirm('Reject this request?');"
                       class="btn btn-danger">Reject</a>
                <?php endif; ?>

            <?php endif; ?>

        </div>

    </div>

</form>

<?php include "footer.php"; ?>