<?php include "header.php";

/// Delete
if (isset($_GET['categoryid'])) {
  $order_id = $_GET['categoryid'];
  $insert = mysqli_query($con, "UPDATE saloon_orders SET status='completed' where id='$order_id'") or die('Could not connect: ' . mysqli_error($con));
  echo "<script>  alert('Traning successfully marked as completed!'); window.location.href = 'viewacademy.php?order=$order_id'; // Refresh the current page
   </script>";

  exit(); // Make sure to exit the script after the alert
}



if (isset($_GET['order'])) {
  $saloon = $_GET['order'];

  $sql = "SELECT * from saloon_orders where id='$saloon' ";
  $sql2 = mysqli_query($con, $sql);
  while ($row = mysqli_fetch_array($sql2)) {
    $date = $row["date"];
    $customername = $row["name"];
    $customerphone = $row["phone"];
    $email = $row["email"];

    $stats = $row['status'];

    //color
    //color
    if ($stats == "no") {
      $bg = "badge-warning";
      $stats = "booking";
    } else if ($stats == "processing") {
      $bg = "badge-primary";
    } else if ($stats == "cancelled") {
      $bg = "badge-danger";
    } else if ($stats == "processed" || $stats == "completed") {
      $bg = "badge-success";
    }
  }
} else {
  header("location:dashboard.php");
}

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h5 mb-0 text-gray-800">Academy ID #<?php echo $saloon; ?></h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Details</li>
  </ol>
</div>







<!-- Row -->
<div class="row justify-content-center mb-8">


  <!-- Datatables -->
  <div class="col-lg-12">




    <p><span class='badge <?php echo $bg; ?>'><?php echo $stats; ?></span><br>
      Customer Details <br>
      Name: <?php echo $customername; ?> <br>
      Email: <?php echo $email; ?> <br>
      Phone: <?php echo $customerphone; ?> </p>
    <p>Date : <?php echo $date; ?></p>




    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-warning">Training Cart</h6>
      </div>
      <div class="table-responsive p-3">
        <table class="table align-items-center table-flush text-primary">
          <thead class="thead-light">
            <tr>
              <th>S/N</th>
              <th>Training</th>
              <th>Duration</th>
              <th>Duration Price</th>
              <!-- <th>Discount</th> -->
              <th>Discount Applied</th>
              <th>Total Price</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT a.*,(d.duration) as duration_int,(d.duration_unit) as duration_unit,(SELECT t.discount_added FROM training t WHERE t.id = d.category) as discount_added,(d.price) as duration_price from academy_cart a LEFT JOIN durations d ON a.training = d.category where a.id='$saloon' GROUP BY a.trainingname ORDER BY a.s ASC";
            $sql2 = mysqli_query($con, $sql);
            $i = 1;
            $arr = [];
            while ($row1 = mysqli_fetch_array($sql2)) {
              $arr[] = $row1;
            }
            foreach ($arr as $row) {
              echo "
                         <tr>
                          <td>" . $i++ . "</td>
                         <td>" . $row['trainingname'] . "</td>	
                         ";
            ?>
              <td>
                <?= $row["duration_int"] ?>
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
            <?php
              echo "
                         <td>&#8358;" . $row["duration_price"] . "</td>
                         <td>" . ($row["discount_applied"] == "true" ? ($row["discount_added"]) : ("0")) . "%</td>
                         <td>&#8358;" . $row["price"] . "</td>
                        </tr>";
            }
            ?>

          </tbody>
        </table>



        <br>
        <center>
          <?php if ($stats == "pending") { ?><p>
            <form action='' method='get' onsubmit='return confirm("Are you sure you want mark this training as completed?");'>
              <input type='text' name='categoryid' value='<?php echo $saloon; ?>' required hidden>
              <input type='submit' name='delete' value='Mark as Completed' class='btn btn-sm btn-danger w-100'>
            </form>
            </p><?php } ?>
        </center>

      </div>
    </div>
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-warning">Bought Training Items</h6>
      </div>
      <div class="table-responsive p-3">
        <table class="table align-items-center table-flush text-primary">
          <thead class="thead-light">
            <tr>
              <th>S/N</th>
              <th>Item Name</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = "SELECT t.id,t.name, t.price FROM training_items t LEFT JOIN academy_cart_training_items a ON a.training_id = t.training_id AND a.training_item_id = t.name WHERE a.item_for = '$saloon' GROUP BY t.name";
            $result = mysqli_query($con, $query);
            $res = [];
            while ($row = mysqli_fetch_array($result)) {
              $res[] = $row;
            }
            // var_dump($res);
            // var_dump($result);
            foreach ($res as $rr) {
            ?>
              <tr>
                <td><?= $rr["id"] ?></td>
                <td><?= $rr["name"] ?></td>
                <td>&#8358; <?= $rr["price"] ?></td>
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



<?php include "footer.php"; ?>