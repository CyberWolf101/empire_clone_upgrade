<?php include "header.php"; ?>

<?php
// Handle deletion if a superadmin clicked the trash icon
if (isset($_GET['delete_id'])) {
  $delete_id = intval($_GET['delete_id']);
  $delete_sql = "DELETE FROM chb_inventory_history WHERE s = $delete_id";
  if (mysqli_query($con, $delete_sql)) {
    echo "<script>alert('Log deleted successfully'); window.location='inventory_system.php';</script>";
  } else {
    echo "<script>alert('Error deleting log'); window.location='inventory_system.php';</script>";
  }
}
?>


<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h5 mb-0 text-gray-800">Inventory Logs</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Inventory</li>
  </ol>
</div>

<!-- Row -->
<div class="row">

  <div align="center" class="col-lg-12">
    <script type="text/javascript">
      function showAri() {
        if (document.getElementById('formAri').style.display == 'none') {
          // clock is visible. hide it
          document.getElementById('formAri').style.display = 'block';
        }

        else {
          // clock is hidden. show it
          document.getElementById('formAri').style.display = 'none';
        }
      }


      $(document).ready(function () {
        // Handle change event of the select element
        $('#shopSelect').on('change', function () {
          var selectedShop = $(this).val();
          // Make an AJAX request to fetch the prices based on the selected shop
          $.ajax({
            type: 'POST',
            url: 'fetchdata.php', // Create a PHP file to handle the database query
            data: { shop: selectedShop }, // Send the selected shop as data
            success: function (response) {
              // Update the priceResults div with the fetched data
              $('#priceResults').html(response);
            }
          });
        });
      });
    </script>
    <?php include "deduct-inventory.php"; ?>
    <p><button onClick="showAri()" class="btn btn-warning w-100">Deduct Inventory</button></p>
    <div class="arizona" id="formAri" style="display:none;">
      <form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:left;">
        <p><select class="form-control" id="shopSelect" name="product" required>
            <option value="" selected>- Select Product -</option>
            <?php
            $sql = "select ci.*,cd.name from chb_inventory ci LEFT JOIN chb_inventory_department cd ON ci.department = cd.s";
            $sql2 = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_array($sql2)) {
              echo '<option value="' . $row['product'] . '">' . $row['productname'] . ' (' . $row['name'] . ')</option>';
            } ?>
          </select></p>
        <input type="text" class="form-control" name="deducted_by" placeholder="Deducted by" required /><br />
        <input type="text" class="form-control" placeholder="Given to.." name="given_to" required /><br />
        <div id="priceResults"></div>
        <input type='submit' name='register' value='Register Log' class='btn btn-primary w-100'>
      </form>
    </div>
  </div>

  <!-- Datatables -->
  <div class="col-lg-12" style="margin-top:2%;">
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Inventory List</h6>
      </div>
      <div class="table-responsive p-3">
        <table class="table align-items-center table-flush text-primary" id="dataTable">
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
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT cih.*, cd.name
        FROM chb_inventory_history cih 
        LEFT JOIN chb_inventory ci ON cih.product = ci.product
        LEFT JOIN chb_inventory_department cd ON ci.department = cd.s ORDER BY cih.date DESC";

            $sql2 = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_array($sql2)) {
              $act = $row['action'];
              $badge = ($act == "added") ? "badge-success" : "badge-danger";

              echo "
    <tr>
        <td><span class='badge $badge'>" . $row['action'] . "</span></td>
        <td>" . $row['productname'] . "</td>
        <td>" . $row['name'] . "</td>
        <td>" . $row['quantity'] . "</td>	
        <td>" . $row['total_left'] . "</td>	
        <td>" . $row['deducted_by'] . "</td>
        <td>" . $row['collected_by'] . "</td>
        <td>" . $row['date'] . "</td>
        <td>";

              // Only show delete button for superadmins
              if ($isAdmin) {
                echo "<a href='inventory_system.php?delete_id=" . $row['s'] . "' class='btn btn-outline-danger'
                 onclick=\"return confirm('Are you sure you want to delete this log?');\">
                 <i class='fas fa-trash'></i>
              </a>";
              }

              echo "</td>
    </tr>";

            }
            ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>




  <?php include "footer.php"; ?>