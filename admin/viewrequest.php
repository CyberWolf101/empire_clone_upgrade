<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// session_start(); // Handled by header.php if already active
include "header.php";

if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$request_id = (int)$_GET['id'];

// Safe global boundary alignment check
if (!isset($isAdmin)) {
    $isAdmin = (isset($_SESSION['role']) && in_array(strtolower($_SESSION['role']), ['admin', 'superadmin']));
}

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

// Normalize status metrics safely
$status_l = strtolower(trim((string)($request['status'] ?? '')));

// Collection is valid for admin if the request is approved, partially collected, or partially rejected
$canCollect = $isAdmin && in_array($status_l, ['approved', 'partially collected', 'partially rejected']);

// CRITICAL FIX: If the global master tracking status is explicitly marked rejected, completely drop override capabilities
if ($status_l === 'rejected') {
    $canCollect = false;
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        Request <?= htmlspecialchars($request['request_code']); ?>
    </h1>

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item"><a href="bakersrequests.php">Bakers Requests</a></li>
        <li class="breadcrumb-item active">View Request</li>
    </ol>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'];
                                        unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'];
                                    unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header"><strong>Request Details</strong></div>
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
                if (in_array($status_l, ['collected', 'completed'])) {
                    echo '<span class="badge badge-success" style="text-transform:capitalize;">Collected</span>';
                } elseif ($status_l === 'partially collected') {
                    echo '<span class="badge badge-primary" style="text-transform:capitalize;">Partially Collected</span>';
                } elseif ($status_l === 'partially rejected') {
                    echo '<span class="badge badge-warning" style="text-transform:capitalize;">Partially Rejected</span>';
                } elseif ($status_l === 'approved') {
                    echo '<span class="badge badge-info" style="text-transform:capitalize;">Approved</span>';
                } elseif ($status_l === 'rejected') {
                    echo '<span class="badge badge-danger" style="text-transform:capitalize;">Rejected</span>';
                } else {
                    echo '<span class="badge badge-warning" style="text-transform:capitalize;">Pending</span>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="process_bakers_request.php">
    <input type="hidden" name="request_id" value="<?= $request_id; ?>">

    <div class="card shadow">
        <div class="card-header"><strong>Requested Ingredients</strong></div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ingredient</th>
                            <th>Requested Qty</th>
                            <th>Collected Qty</th>
                            <th>Available Stock</th>
                            <th>Status</th>
                            <th>Collect Now</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $items = mysqli_query($con, "
                            SELECT
                                bri.*,
                                ci.productname,
                                bri.status as item_status,
                                ci.inventory AS stock_qty
                            FROM bakers_request_items bri
                            LEFT JOIN chb_inventory ci
                                ON ci.product = bri.item_id
                            WHERE bri.request_id = '$request_id'
                        ");

                        $hasRemainingItems = false;

                        while ($item = mysqli_fetch_assoc($items)) {
                            $item_status_l = strtolower(trim((string)($item['item_status'] ?? '')));
                            $remaining = $item['quantity'] - $item['collected_quantity'];

                            // Only count remaining items if they aren't fully rejected
                            if ($remaining > 0 && $item_status_l !== 'rejected') {
                                $hasRemainingItems = true;
                            }

                            // FIX: Inputs stay operational if master allows it and this row is not rejected
                            $input_attrs = ($canCollect && $status_l !== 'rejected' && $item_status_l !== 'rejected') ? '' : 'disabled';
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['productname']); ?></td>
                                <td><?= $item['quantity']; ?></td>
                                <td><?= $item['collected_quantity']; ?></td>
                                <td class="<?= ($item['stock_qty'] < 1) ? 'text-danger' : 'text-success' ?>"><?= $item['stock_qty']; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $item_status_l == 'rejected' || $item_status_l == 'partially rejected' ? 'danger' : ($item_status_l == 'pending' ? 'warning' : 'success') ?> text-white">
                                        <?= $item['item_status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($item_status_l === 'rejected') { ?>
                                        <span class="text-danger small">Rejected</span>
                                    <?php } elseif ($item_status_l === 'partially rejected') {
                                    ?>
                                        <span class="text-danger small">Partially Rejected</span>
                                    <?php
                                    } elseif ($remaining > 0) { ?>
                                        <input type="number"
                                            step="0.01"
                                            min="0"
                                            max="<?= $remaining; ?>"
                                            class="form-control"
                                            name="collect_qty[<?= $item['id']; ?>]"
                                            placeholder="0"
                                            <?= $input_attrs ?>>
                                    <?php } else { ?>
                                        <span class="text-success">Fully Collected</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (!in_array($item_status_l, ['rejected', 'partially rejected']) && $isAdmin && !$item['collected_quantity'] <= $item['quantity']) { ?>
                                        <button class="btn btn-danger btn-sm reject-ingredient-btn"
                                            type="button"
                                            data-item-id="<?= $item['id']; ?>">
                                            <i class="fas fa-times me-1"></i> <?php echo $item['collected_quantity'] > 0 ? "Reject remaining" : "Reject" ?>
                                        </button>
                                    <?php } else { ?>
                                        <span class="text-muted small">-</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer text-right">
            <?php if (in_array($status_l, ['collected', 'completed', 'rejected']) || !$hasRemainingItems): ?>
                <button type="button" class="btn btn-secondary" disabled>No Actions Available</button>
                <a href="bakersrequests.php" class="btn btn-secondary">Back</a>
            <?php else: ?>
                <?php if ($canCollect && $isAdmin): ?>
                    <button type="submit" class="btn btn-success">Process Collection</button>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary" disabled>Awaiting Approval</button>
                <?php endif; ?>

                <a href="bakersrequests.php" class="btn btn-secondary">Back</a>

                <?php if ($isAdmin && !in_array($status_l, ['approved', 'rejected', 'collected', 'completed', 'partially collected', 'partially rejected'])): ?>
                    <a href="approve_request.php?id=<?= urlencode($request_id); ?>"
                        onclick="return confirm('Approve this request?');"
                        class="btn btn-success">Approve</a>
                    <a href="approve_request.php?id=<?= urlencode($request_id); ?>&reject=1"
                        onclick="return confirm('Reject this request?');"
                        class="btn btn-danger">Reject All</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $(".reject-ingredient-btn").on("click", function() {
            let $btn = $(this);
            let itemId = $btn.data("item-id");
            let $currentRow = $btn.closest("tr");
            let ingredientName = $currentRow.find("td:first").text().trim();

            if (!confirm("Are you sure you want to reject '" + ingredientName + "' from this baker request?")) {
                return;
            }

            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

            $.ajax({
                url: 'reject_single_ingredient.php',
                method: 'POST',
                data: {
                    bakers_item_id: itemId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert("Ingredient successfully marked as rejected!");
                        window.location.reload();
                    } else {
                        alert("Error: " + response.message);
                        $btn.prop('disabled', false).html('<i class="fas fa-times me-1"></i> Reject');
                    }
                },
                error: function() {
                    alert("An error occurred reaching the rejection handler script.");
                    $btn.prop('disabled', false).html('<i class="fas fa-times me-1"></i> Reject');
                }
            });
        });
    });
</script>

<?php include "footer.php"; ?>