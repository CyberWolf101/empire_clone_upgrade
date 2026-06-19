<?php
include "header.php";

$bakersGuideId = isset($_GET["id"]) ? $_GET["id"] : 0;

if ($bakersGuideId <= 0) {
    die("Invalid guide ID");
}
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Bakers Guide</h1>

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active">View Bakers Guide</li>
    </ol>
</div>
<div class="row">
    <div class="col-lg-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Ingredients Needed</span>

                <button class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#editGuideModal">
                    Edit Guide
                </button>
            </div>

            <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ingredient</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>

                    <tbody><?php
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
                                echo "<tr><td colspan='2'>No ingredients found</td></tr>";
                            }

                            while ($row = mysqli_fetch_assoc($sql)) {
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['productname']); ?></td>
                                <td><?= htmlspecialchars($row['quantity']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="editGuideModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Ingredients</h5>

                <button type="button"
                    class="close"
                    data-bs-dismiss="modal">
                    &times;
                </button>
            </div>

            <form method="POST" action="update_bakers_guide.php">

                <div class="modal-body">

                    <input type="hidden" name="guide_id"
                        value="<?= $bakersGuideId ?>">

                    <div id="ingredientContainer">
                        <!-- Existing ingredients will be loaded here -->
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

                                <input type="hidden"
                                    name="row_id[]"
                                    value="<?= $r['id']; ?>">

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

                                    <input type="number"
                                        name="request_row[<?= $r['id']; ?>][qty]"
                                        class="form-control mt-1"
                                        placeholder="Request Qty (optional)">
                                </div>

                                <div class="col-md-2 text-center">

                                    <!-- Remove row -->
                                    <button type="button"
                                        class="btn btn-danger removeRow mb-1">
                                        X
                                    </button>

                                    <br>

                                    <!-- Request toggle (PER ROW) -->
                                    <input type="checkbox"
                                        class="request-toggle"
                                        name="request_row[<?= $r['id']; ?>][active]"
                                        value="1">

                                    <small>Request</small>

                                </div>

                            </div>

                        <?php } ?>
                    </div> <!-- ingredientContainer -->

                    <button type="button"
                        class="btn btn-secondary mt-3"
                        id="addIngredientRow">
                        + Add Ingredient
                    </button>

                </div>

                <div class="modal-footer">
                    <button type="submit"
                        class="btn btn-primary">
                        Save Changes
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>
<script>
    $(document).ready(function() {

        // ADD ROW
        $('#addIngredientRow').click(function() {

            let row = $('.ingredient-row:first').clone();

            row.find('select').val('');
            row.find('input[name="quantity[]"]').val('');
            row.find('input[name="row_id[]"]').remove();

            $('#ingredientContainer').append(row);
        });

        // REMOVE ROW
        $(document).on('click', '.removeRow', function() {

            if ($('.ingredient-row').length > 1) {
                $(this).closest('.ingredient-row').remove();
            }
        });
        $('.ingredient-row').each(function() {

            let cb = $(this).find('.request-toggle');
            let qty = $(this).find('input[name*="[qty]"]');

            if (!cb.is(':checked')) {
                qty.prop('readonly', true);
            }
        });

    });
    $(document).on('change', '.request-toggle', function() {

        let row = $(this).closest('.ingredient-row');
        let qtyInput = row.find('input[name*="[qty]"]');

        if ($(this).is(':checked')) {
            qtyInput.prop('readonly', false);
        } else {
            qtyInput.prop('readonly', true).val('');
        }
    });
</script>