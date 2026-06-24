<?php
include "header.php";

$bakersGuideId = isset($_GET["id"]) ? $_GET["id"] : 0;

// if ($bakersGuideId <= 0) {
//     die("Invalid guide ID");
// }
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Bakers Guide</h1>

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active">View Bakers Guide</li>
    </ol>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-12">

        <div class="card shadow mb-4">
<p><strong>GUide </strong></p>
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Ingredients Needed</h6>

                <button class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#editGuideModal">
                    <i class="fas fa-edit fa-sm"></i> Edit Guide Structure
                </button>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Ingredient</th>
                                <th width="15%">Standard Guide Qty</th>
                                <th width="35%">Direct Action Request Box</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $sql = mysqli_query($con, "
                                SELECT
                                    gni.id,
                                    gni.item_id,
                                    gni.quantity,
                                    ci.productname
                                FROM guides_needed_items gni
                                JOIN chb_inventory ci
                                    ON ci.product = gni.item_id
                                WHERE gni.guide_id = '$bakersGuideId'
                            ");

                            if (mysqli_num_rows($sql) == 0) {
                                echo "<tr><td colspan='3' class='text-center text-muted'>No ingredients found linked to this guide template.</td></tr>";
                            }

                            while ($row = mysqli_fetch_assoc($sql)) {
                            ?>
                            <tr>
                                <td class="font-weight-bold text-dark"><?= htmlspecialchars($row['productname']); ?></td>
                                <td><span class="badge badge-secondary"><?= htmlspecialchars($row['quantity']); ?></span></td>
                                <td>
                                    <form method="POST" action="submit_quick_request.php" class="row g-2 align-items-center m-0">
                                        <input type="hidden" name="guide_id" value="<?= $bakersGuideId; ?>">
                                        <input type="hidden" name="item_id" value="<?= $row['item_id']; ?>">
                                        
                                        <div class="col-auto">
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0.01" 
                                                   name="quick_request_qty" 
                                                   class="form-control form-control-sm" 
                                                   placeholder="Enter Order Qty" 
                                                   required>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-success btn-sm font-weight-bold">
                                                <i class="fas fa-paper-plane fa-xs"></i> Request Item
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="editGuideModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Ingredients Structure</h5>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>

            <form method="POST" action="update_bakers_guide.php">
                <div class="modal-body">
                    <input type="hidden" name="guide_id" value="<?= $bakersGuideId ?>">

                    <div id="ingredientContainer">
                        <?php
                        $editSql = mysqli_query($con, "
                            SELECT
                                gni.id,
                                gni.item_id,
                                gni.quantity,
                                ci.productname
                            FROM guides_needed_items gni
                            JOIN chb_inventory ci
                                ON ci.product = gni.item_id
                            WHERE gni.guide_id = '$bakersGuideId'
                        ");

                        while ($r = mysqli_fetch_assoc($editSql)) {
                        ?>
                        <div class="ingredient-row row mb-2">
                            <input type="hidden" name="row_id[]" value="<?= $r['id']; ?>">

                            <div class="col-md-6">
                                <select class="form-control" name="item_id[]" required>
                                    <option value="<?= $r['item_id']; ?>">
                                        <?= htmlspecialchars($r['productname']); ?>
                                    </option>
                                    <?php
                                    $inv = mysqli_query($con, "SELECT product, productname FROM chb_inventory");
                                    while ($i = mysqli_fetch_assoc($inv)) {
                                    ?>
                                        <option value="<?= $i['product']; ?>">
                                            <?= htmlspecialchars($i['productname']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <input type="number"
                                    name="quantity[]"
                                    class="form-control"
                                    step="0.01"
                                    value="<?= $r['quantity']; ?>"
                                    required>
                            </div>

                            <div class="col-md-2 text-center">
                                <button type="button" class="btn btn-danger removeRow mb-1">X</button>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <button type="button" class="btn btn-secondary mt-3" id="addIngredientRow">
                        + Add Ingredient Row
                    </button>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Template Updates</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // ADD NEW TEMPLATE ROW INSIDE MODAL
        $('#addIngredientRow').click(function() {
            let row = $('.ingredient-row:first').clone();
            row.find('select').val('');
            row.find('input[name="quantity[]"]').val('');
            row.find('input[name="row_id[]"]').remove();
            $('#ingredientContainer').append(row);
        });

        // REMOVE ROW FROM TEMPLATE MODAL
        $(document).on('click', '.removeRow', function() {
            if ($('.ingredient-row').length > 1) {
                $(this).closest('.ingredient-row').remove();
            }
        });
    });
</script>