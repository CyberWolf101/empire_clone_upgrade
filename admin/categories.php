<?php include "header.php";

/// Delete
if (isset($_GET['categoryid'])) {
  $service_delete = $_GET['categoryid'];
  $del = mysqli_query($con, "DELETE from category where id='$service_delete'") or die('Could not connect: ' . mysqli_error($con));
  echo "<script>alert('Category Deleted successfully!'); window.location.href = 'categories.php';</script>";
  exit(); // Make sure to exit the script after the alert
}

if (isset($_GET['toggleid'])) {
    $s = (int) $_GET['toggleid'];
    $current = (int) $_GET['current']; // current status
    $newStatus = $current ? 0 : 1;

    mysqli_query($con, "UPDATE category SET isEnabled='$newStatus' WHERE s='$s'") 
        or die('Could not update: ' . mysqli_error($con));

    echo "<script>window.location.href = 'categories.php';</script>";
    exit();
}

// if (isset($_GET['toggleid'])) {
//     $s = (int) $_GET['toggleid'];       // using s instead of id
//     $current = (int) $_GET['current'];  // current status
//     $newStatus = $current ? 0 : 1;

//     $query = "UPDATE category SET isEnabled='$newStatus' WHERE s='$s'";
//     mysqli_query($con, $query) or die('Could not update: ' . mysqli_error($con));

//     // Check row after update
//     $check = mysqli_query($con, "SELECT s, isEnabled FROM category WHERE s='$s'");
//     if ($row = mysqli_fetch_assoc($check)) {
//         echo "Row after update -> S: {$row['s']}, isEnabled: {$row['isEnabled']}<br>";
//     }

//     exit(); // remove redirect for debugging
// }



?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Categories</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Categories</li>
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
    <?php include "addcategory.php"; ?>
    <p><button onClick="showAri()" class="btn btn-warning">Add New Category</button></p>
    <div class="arizona" id="formAri" style="display:none;">
      <form enctype="multipart/form-data" method="post" style="width:60%; margin:auto; text-align:center;">
        <input type="text" class="form-control" name="name" placeholder="*Category Name" required /><br />
        <textarea type="text" class="form-control" name="described" placeholder="*About Category"
          required></textarea><br>
        <p><input type="file" name="file" class="form-control" id="customFile" required></p>
        <input type='submit' name='register' value='Register Category' class='btn btn-primary'>
      </form>
    </div>
  </div>


  <!-- Datatables -->
  <div class="col-lg-12" style="margin-top:2%;">
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Saloon and Spa Categories</h6>
      </div>
      <div class="table-responsive p-3">
        <table class="table align-items-center table-flush text-primary" id="dataTable">
          <thead class="thead-light">
            <tr>
              <th>Name</th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Name</th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>
          <tbody>
            <?php
            $sql = "SELECT * from category  ORDER BY name ASC";
            $sql2 = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_array($sql2)) {

              $isEnabled = $row['isEnabled'] ?? 0;

              echo "
                         <tr>
                         <td>" . $row['name'] . "</td>
                         <td><form action='subcategory.php' method='get'>
			             <input type='text' name='category' value='" . $row['id'] . "' required hidden>  
                         <input type='submit' name='view' value='View Sub Categories' class='btn btn-sm btn-warning' ></form></td>	
                        <td><form action='editcategory.php' method='get'>
		                <input type='text' name='category' value='" . $row['id'] . "' required hidden>  
                        <input type='submit' name='edit' value='Edit Category' class='btn btn-sm btn-primary' ></form></td>	
                        <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this category (" . $row['name'] . ")?\");'>
		                <input type='text' name='categoryid' value='" . $row['id'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Category' class='btn btn-sm btn-danger' ></form></td>	
     <td>
          <form action='' method='get'>
            <input type='hidden' name='toggleid' value='" . $row['s'] . "'>  
            <input type='hidden' name='current' value='" . $isEnabled . "'>  
            <input type='submit' 
                   value='" . ($isEnabled ? "Disable" : "Enable") . " Category' 
                   class='btn btn-sm " . ($isEnabled ? "btn-secondary" : "btn-success") . "'>
          </form>
        </td>

                        </tr>";


            }
            ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>




  <?php include "footer.php"; ?>