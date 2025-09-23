<?php include "header.php";

/// Delete
if (isset($_GET['categoryid'])) {
  $service_delete = $_GET['categoryid'];
  $del = mysqli_query($con, "DELETE from chb_inventory_department where s='$service_delete'") or die('Could not connect: ' . mysqli_error($con));
  echo "<script>alert('Department Deleted Successfully!'); window.location.href = 'inventory_departments.php';</script>";
  exit(); // Make sure to exit the script after the alert
}

//Update Store
if (isset($_POST['update_store'])) {
  $name = $_POST['name'];

  $insert = mysqli_query($con, "UPDATE chb_inventory_department SET name='$name' where s='$id'") or die('Could not connect: ' . mysqli_error($con));
  echo "<script>alert('Department details updated successfully!'); window.location.href = 'inventory_departments.php';</script>";
}

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h5 mb-0 text-gray-800">Inventory Departments</h1>
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
    </script>
    <?php include "add-inventory-department.php"; ?>
    <p>
      <button onClick="showAri()" class="btn btn-warning w-100"
    <?php if (!$isAdmin) echo 'disabled'; ?>>
    Add New Department
</button>

    </p>
    <div class="arizona" id="formAri" style="display:none;">
      <form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:center;">
        <input type="text" class="form-control" name="name" placeholder="*Name" required /><br />
        <input type='submit' name='register' value='Register Department' class='btn btn-primary w-100'>
      </form>
    </div>
  </div>


  <!-- Datatables -->
  <div class="col-lg-12" style="margin-top:2%;">
    <div class=" mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Inventory Departments</h6>
      </div>
      <div class="p-2" style="overflow:scroll">
        <table class="table align-items-center table-flush text-primary" id="dataTable">
          <thead class="thead-light">
            <tr>
              <th>Department</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT * from chb_inventory_department ORDER BY s ASC";
            $sql2 = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_array($sql2)) {
              $disabled = $isAdmin ? '' : 'disabled';
              echo "
                         <tr>
                         <td>" . $row['name'] . "</td>
                         <td> <button type='button'  data-toggle='modal' data-target='#modal" . $row['s'] . "' class='btn btn-sm btn-primary'>Edit Department</button></td>
                        <td><form action=''  method='get' onsubmit='return confirm(\"Are you sure you want to delete this department (" . $row['name'] . ")?\");'>
		                <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Department' class='btn btn-sm btn-danger' $disabled></form></td>
                        </tr>";





              echo '	<div class="modal fade" id="modal' . $row['s'] . '" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
				<div class="modal-header">
				<h6 style="color:black;">Edit Department Details</h6>
				</div>
                <div class="modal-body">
                <form id="form" name="form" action="" method="post" enctype="multipart/form-data"> 
                 <div class="row mb-3">
                      <div class="col-md-12">
                          
                          
                   <p><input type="text" name="name" class="form-control" value="' . $row['name'] . '" placeholder="Name" required></p>
                   <p><input type="hidden" name="id" class="form-control" value="' . $row['s'] . '" placeholder="Advert Text" required></p> </div>
					  <div class="modal-footer">
					  <input id="submit" name="update_store" class="btn btn-sm btn-primary shadow-sm w-100" type="submit" value="Update Department"></form>
                    </div>
                  </div>
                </div></div>
               </div><!-- End Modal Dialog Scrollable-->';

              $i++;
            }
            ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>




  <?php include "footer.php"; ?>