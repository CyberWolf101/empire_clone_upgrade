<?php
include "header.php";
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Bakers Guide</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Bakers Guide</li>
  </ol>
</div>
<div class="row">
  <div class="col-lg-12">
    <!-- ADD GUIDE MODAL -->
    <div class="modal fade" id="addGuideModal" tabindex="-1" role="dialog" aria-labelledby="addGuideModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addGuideModalLabel">Add Guide</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" action="addbakersguide.php">

              <div class="mb-3">
                <label>Guide Name</label>
                <input type="text"
                  class="form-control"
                  name="item"
                  required>
              </div>

              <hr>

              <h6>Ingredients</h6>

              <div id="ingredients-container">

                <div class="ingredient-row row mb-2">

                  <div class="col-md-7">
                    <select class="form-control"
                      name="item_id[]"
                      required>

                      <option value="">
                        Select Ingredient
                      </option>

                      <?php
                      $sql = "SELECT product, productname
                                FROM chb_inventory
                                ORDER BY productname";

                      $result = mysqli_query($con, $sql);

                      while ($inv = mysqli_fetch_assoc($result)) {
                      ?>
                        <option value="<?= $inv['product']; ?>">
                          <?= htmlspecialchars($inv['productname']); ?>
                        </option>
                      <?php } ?>

                    </select>
                  </div>

                  <div class="col-md-3">
                    <input type="number"
                      step="0.01"
                      min="0"
                      class="form-control"
                      name="quantity[]"
                      placeholder="Qty"
                      required>
                  </div>

                  <div class="col-md-2">
                    <button type="button"
                      class="btn btn-danger remove-row">
                      X
                    </button>
                  </div>

                </div>

              </div>

              <button type="button"
                id="addIngredient"
                class="btn btn-secondary mb-3">
                + Add Ingredient
              </button>

              <br>

              <button type="submit"
                class="btn btn-primary">
                Save Guide
              </button>

            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Content for the Bakers Guide page -->
    <div class="card">
      <div class="card-header font-weight-bold d-flex justify-content-between align-items-center" style="color: gold;">Bakers Guide
        <?php if ($isAdmin): ?>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGuideModal">Add Guide</button>
        <?php endif; ?>
      </div>
      <div class="card-body">

        <!-- Add your content here -->
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <!-- <th>ID</th> -->
              <th>Item</th>
              <!-- <th>Items Needed</th> -->
              <th>Added On</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $results = [];
            $query = "SELECT * FROM bakers_guide ORDER BY added_on DESC";
            $result = mysqli_query($con, $query);
            while ($row1 = mysqli_fetch_assoc($result)) {
              $results[] = $row1;
            }
            if (empty($results)) {
            ?>
              <tr>
                <td colspan='4'>No bakers guide items found.</td>
              </tr>
            <?php
            }
            foreach ($results as $row) {
            ?>
              <tr>
                <!-- <td><?= htmlspecialchars($row['id']) ?></td> -->
                <td><?= htmlspecialchars($row['item']) ?></td>
                <!-- <td><?= htmlspecialchars($row['item_id']) ?></td> -->
                <td><?= htmlspecialchars($row['added_on']) ?></td>
                <td>
                  <a class="btn btn-sm btn-primary" href="viewbakersguide.php?id=<?= $row['guide_id'] ?>&name=<?= $row["item"] ?>">View</a>
                  <?php if ($isAdmin): ?>
                    <a href="deletebakersguide.php?id=<?= $row['guide_id'] ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this guide?')">
                    Delete
                  </a>
                  <?php endif; ?>
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
</div>
<script>
  $(document).ready(function() {

    $('#addIngredient').click(function() {

      let row = $('.ingredient-row:first').clone();

      row.find('select').val('');
      row.find('input').val('');

      $('#ingredients-container').append(row);
    });

    $(document).on('click', '.remove-row', function() {

      if ($('.ingredient-row').length > 1) {
        $(this).closest('.ingredient-row').remove();
      }

    });

  });
</script>