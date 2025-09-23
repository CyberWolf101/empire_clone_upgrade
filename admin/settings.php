<?php include "header.php";


$sql = "SELECT * from site_settings";
$sql2 = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($sql2)) {
  $apikey = $row["apikey"];
  $rentprice = $row["rental"];
  $kitprice = $row["pedicurekit"]; // Corrected column name
}


//Update Store
if (isset($_POST['update_store'])) {
  $id = 1;
  $pp = $_POST['pprice'];
  $rp = $_POST['rprice'];
  $key = $_POST['key'];

  $insert = mysqli_query($con, "UPDATE site_settings SET  apikey='$key' where s='$id'") or die('Could not connect: ' . mysqli_error($con));
  $insert = mysqli_query($con, "UPDATE site_settings SET  rental='$rp' where s='$id'") or die('Could not connect: ' . mysqli_error($con));
  $insert = mysqli_query($con, "UPDATE site_settings SET  pedicurekit='$pp' where s='$id'") or die('Could not connect: ' . mysqli_error($con));
  echo "<script>alert('Settings Updated Successfully!'); window.location.href = 'settings.php';</script>";
}

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Settings</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Site Settings</li>
  </ol>
</div>

<!-- Row -->
<div class="row">

  <div align="center" class="col-lg-12">

    <div class="arizona" id="formAri">
      <form enctype="multipart/form-data" method="post" style="width:60%; margin:auto; text-align:left;">
        <label>Pedicure Kit Price</label><br><input type="number" value="<?php echo $kitprice; ?>" class="form-control"
          name="pprice" required /><br />
        <label>Rental Hall price(a day)</label><br><input type="number" value="<?php echo $rentprice; ?>"
          class="form-control" name="rprice" required /><br />
        <label>API KEY</label><br><input type="text" value="<?php echo $apikey; ?>" class="form-control" name="key"
          required /><br />
        <input type='submit' name='update_store' value='Update settings' class='btn btn-primary'>
      </form>
    </div>
  </div>

</div>

<?php include "otherSettings.php"?>
</div>




<?php include "footer.php"; ?>