<?php include "header.php";


/// Delete
if (isset($_GET['categoryid'])) {
  $service_delete = $_GET['categoryid'];
  $del = mysqli_query($con, "DELETE from durations where id='$service_delete'") or die('Could not connect: ' . mysqli_error($con));
  echo "<script>alert('Duration Deleted successfully!'); window.location.href = 'duration.php';</script>";
  exit(); // Make sure to exit the script after the alert
}



if (isset($_GET['update'])) {
  $service =  $_GET['service'];
  $price =  $_GET['price'];
  $unit =  $_GET['duration_unit'];
  $id =  $_GET['serviceid'];
  // echo $service;
  $insert = mysqli_query($con, "UPDATE durations SET price= '$price' where id='$id'") or die('Could not connect: ' . mysqli_error($con));
  $insert = mysqli_query($con, "UPDATE durations SET  duration= '$service', duration_unit = '$unit' where id='$id'") or die('Could not connect: ' . mysqli_error($con));
  echo "<script>alert('Duration Updated successfully!'); window.location.href = 'duration.php';</script>";
}
?>


<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">All Training Durations</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Academy</li>
  </ol>
</div>


<!-- Row -->
<div class="row">

  <div class="col-lg-12">
    <script type="text/javascript">
      function showMeal() {
        if (document.getElementById('formMeal').style.display == 'none') {
          // clock is visible. hide it
          document.getElementById('formMeal').style.display = 'block';
        } else {
          // clock is hidden. show it
          document.getElementById('formMeal').style.display = 'none';
        }
      }
    </script>
    <?php include "addduration.php"; ?>
    <p><button onClick="showMeal()" class="btn btn-warning">Add Duration To Training</button></p>

    <div class="arizona" id="formMeal" style="display:none;">
      <form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:left;">
        <input type="text" class="form-control" name="name" placeholder="*Duration" required /><br />
        <div class="form-group"><label>Select Training</label>
          <select class="form-control" name="category" style="width:100%;">
            <?php
            $sql = "select id,name from training";
            $sql2 = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_array($sql2)) {
              echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            } ?></select>
        </div>
        <input type="number" class="form-control" name="price" placeholder="*Cost Price" required /><br />
        <input type='submit' name='register' value='Add Duration' class='btn btn-primary w-100'>
      </form>
    </div>
  </div>





  <!-- Datatables -->
  <div class="col-lg-12" style="margin-top:2%;">
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"></h6>
      </div>
      <div class="table-responsive p-3">
        <table class="table align-items-center table-flush text-primary" id="dataTable" style="font-size:15px;">
          <thead class="thead-light">
            <tr>
              <th>Duration</th>
              <th>Duration Select</th>
              <th>Price</th>
              <th>Training</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Service Name</th>
              <th>Service</th>
              <th>Price</th>
              <th>Duration</th>
              <th>Subcategory</th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>
          <tbody>
            <?php
            $sql = "SELECT * from durations ORDER BY id ASC";
            $sql2 = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_array($sql2)) {
              $sub = $row["category"];

              $sq = "SELECT * from training where id='$sub'";
              $sq2 = mysqli_query($con, $sq);
              while ($rows = mysqli_fetch_array($sq2)) {
                $categoryname = $rows["name"];
              }

            ?>
              <tr>
                <td><?= $row['duration'] ?>
                  <?php
                  $unitOutputs = [
                    [
                      "short_form" => "d",
                      "full_form" => "Day(s)"
                    ],
                    [
                      "short_form" => "w",
                      "full_form" => "Weeks(s)"
                    ],
                    [
                      "short_form" => "m",
                      "full_form" => "Months(s)"
                    ],
                    [
                      "short_form" => "y",
                      "full_form" => "Year(s)"
                    ]
                  ];
                  foreach ($unitOutputs as $output) {
                    if ($row['duration_unit'] == $output["short_form"]) {
                  ?>
                      <?= $output["full_form"] ?>
                  <?php
                    }
                  }
                  ?>
                  </td>
                <form action='' method='get'>
                  <td style="display: flex;justify-content: space-between;">
                    <div style="width: 30%;">
                      <label for="" class="form-label">Inteval</label>
                      <input type='number' name='service' class="form-control" value='<?= $row['duration'] ?>' required>
                    </div>
                    <div style="width: 60%;">
                      <label for="" class="form-label">Unit</label>
                      <select name="duration_unit" id="" class="form-control">
                        <option value="">-- Select Unit --</option>
                        <option value="d">Day(s)</option>
                        <option value="w">Week(s)</option>
                        <option value="m">Month(s)</option>
                        <option value="y">Year(s)</option>
                      </select>
                      <!-- <input type='text' name='service' value='<?= $row['duration'] ?>' style='width:200px; font-size:12px;' required> -->
                    </div>
                  </td>
                  <td>
                    <input type='text' name='serviceid' value='<?= $row['id'] ?>' required hidden>
                    <input type='text' name='price' value='<?= $row['price'] ?>' style='width:80px;' required>
                  </td>
                  <td>
                    <input type='submit' name='update' value='Update training' class='btn btn-sm btn-primary'>
                  </td>
                </form>
                <td><?= $categoryname ?></td>
                <td>
                  <form action='' method='get' onsubmit='return confirm("Are you sure you want to delete this duration(<?= $row["duration"] ?>)?")'>
                    <input type='text' name='categoryid' value='<?= $row[' id'] ?>' required hidden>
                    <input type='submit' name='delete' value='Delete Duration' class='btn btn-sm btn-danger'>
                  </form>
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