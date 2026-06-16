<?php
include "header.php";
include "../mailer.php";
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Academy Bookings</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Academy</li>
  </ol>
</div>
<?php
// Ensure $con is accessible and the sendEmail function is included/defined above this script

if (isset($_POST["set-date"])) {
  $customerName = isset($_POST["customer_name"]) ? mysqli_real_escape_string($con, $_POST["customer_name"]) : "";
  $executeSQL = "";
  $date = mysqli_real_escape_string($con, $_POST["date"]);
  $training_id_from_saloon_orders = mysqli_real_escape_string($con, $_POST["training_id_from_saloon_orders"]);
  $training_id = mysqli_real_escape_string($con, $_POST["real_training_id"]);

  // Format a reader-friendly version of the date for the email
  $formattedDate = date("F j, Y", strtotime($date));

  // 1. Check if the training date entry exists
  $findSQL = "SELECT * FROM training_dates WHERE training_id_from_saloon_orders = '$training_id_from_saloon_orders'";
  $res = mysqli_query($con, $findSQL);
  $result = mysqli_fetch_array($res);

  $dbUpdatedOrInserted = false;

  if ($result) {
    // Entry exists -> Update it
    $executeSQL = "UPDATE training_dates SET start_date = '$date' WHERE training_id_from_saloon_orders = '$training_id_from_saloon_orders'";
    if (mysqli_query($con, $executeSQL)) {
      $dbUpdatedOrInserted = true;
      echo "<script>alert('Start date updated successfully');</script>";
    }
  } else {
    // Entry doesn't exist -> Insert it
    $executeSQL = "INSERT INTO training_dates(start_date, training_id_from_saloon_orders) VALUES ('$date', '$training_id_from_saloon_orders')";
    if (mysqli_query($con, $executeSQL)) {
      $dbUpdatedOrInserted = true;
      echo "<script>alert('Start date set successfully');</script>";
    }
  }

  // 2. If database operation succeeded, gather information and trigger email orchestration


  /* A. FETCH CUSTOMER DETAILS 
      Assumes your relational link is tied to 'training_id_from_saloon_orders'. 
      Adjust table/column fields below if your customer mapping uses a different ID variant.
    */
  $customerSql = "SELECT * FROM customers WHERE unique_id = '$customerName'";
  $customerRes = mysqli_query($con, $customerSql);

  if ($customerRes && mysqli_num_rows($customerRes) > 0) {
    $customerData = mysqli_fetch_assoc($customerRes);
    $toEmail = $customerData['email'];
    $recipientName = $customerData['name'];
    // echo $toEmail . " " . $recipientName;
    /* B. FETCH "ITEMS TO BRING" FOR THIS SPECIFIC TRAINING
        Adjust column names if your table signature differs from your previous backend steps
      */
    $itemsSql = "SELECT item_name FROM training_items_to_bring WHERE training_id = '$training_id'";
    $itemsRes = mysqli_query($con, $itemsSql);

    $itemsListString = "";
    if ($itemsRes && mysqli_num_rows($itemsRes) > 0) {
      while ($itemRow = mysqli_fetch_assoc($itemsRes)) {
        $itemsListString .= "<li>" . htmlspecialchars($itemRow['item_name']) . "</li>";
      }
    } else {
      $itemsListString = "<li>No specific requirements listed. Bring your learning enthusiasm!</li>";
    }
    // echo $itemsListString;

    /* C. COMPOSE EMAIL CONTENT (HTML Format)
      */
    $subject = "Important: Your Training Commencement Date & Checklists - CHBLUXURYEMPIRE";

    $message = "
        <html>
        <head>
          <title>Training Commencement Schedule</title>
        </head>
        <body>
          <h2>Hello, " . htmlspecialchars($recipientName) . "!</h2>
          <p>We are excited to inform you that your upcoming training session schedule has been finalized.</p>
          
          <p><strong>Training Start Date:</strong> " . $formattedDate . "</p>
          
          <hr/>
          <h3>Required Checklists (Things to Bring):</h3>
          <ul>
            " . $itemsListString . "
          </ul>
          <hr/>
          
          <p>Please make sure you arrive early with the required items listed above. If you have any inquiries, feel free to respond directly to this email message.</p>
          <p>Best regards,<br/>Academy Training Team</p>
        </body>
        </html>
      ";

    /* D. CALL YOUR EXISTING sendEmail FUNCTION
        Pass your variable requirements matching your local helper signature parameters
      */
    if (sendEmail($toEmail, $subject, $message)) {
?>
    <?php
    } else {
    ?>
<?php
    }
  }
  unset($_POST["set-date"]);
}
?>
<!-- Invoice Example -->
<div class="col-xl-12 col-lg-12 mb-4">
  <div class="card">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Rentals</h6>
    </div>
    <div class="table-responsive">
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <!-- <th>SN</th> -->
            <th>Academy ID</th>
            <th>Customer</th>
            <th>Total Amount</th>
            <th>Payment Status</th>
            <th>Start Date</th>
            <th>Reminder Interval</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT s.*, (a.training) as real_training_id,(SELECT c.unique_id FROM customers c WHERE c.name = s.name) as customer_id,(SELECT td.reminder_interval FROM training_dates td WHERE td.training_id_from_saloon_orders = s.id) as reminder_interval,(SELECT td.reminder_unit FROM training_dates td WHERE td.training_id_from_saloon_orders = s.id) as reminder_unit FROM saloon_orders s LEFT JOIN academy_cart a ON s.id = a.id where s.section='academy' ORDER BY s.s DESC";
          $sql2 = mysqli_query($con, $sql);
          $i = 1;
          while ($row = mysqli_fetch_array($sql2)) {
            $pay_status = $row['pay_status'];
            $status = $row['status'];
            //color
            $bg = "";
            if (in_array($status, ["no", "No", "pending"])) {
              $bg = "badge-warning";
              $status = "booking";
            } else if (in_array($status, ["Processed", "processed"])) {
              $bg = "badge-primary";
            } else if (in_array($status, ["Cancelled", "cancelled"])) {
              $bg = "badge-danger";
            } else if ($status == "processed" || $status == "completed") {
              $bg = "badge-success";
            }
            $statusbg = "";
            if (in_array($pay_status, ["pending", "Pending"])) {
              $statusbg = "badge-warning";
            } else if (in_array($pay_status, ["paid", "Paid"])) {
              $statusbg = "badge-success";
            } else if ($status == "cancelled") {
              $statusbg = "badge-danger";
            }
          ?>
            <tr>
              <!-- <td><?= $i++ ?></td> -->
              <td><?= $row['id'] ?></td>
              <td><?= $row['name'] ?></td>
              <td>&#8358;<?= $row['total_amount'] ?></td>
              <td><span class='badge <?= $statusbg ?>' style='text-transform:capitalize;'><?= $pay_status ?></span></td>
              <td>
                <?php
                $id = $row["id"];
                $datesSQL = "SELECT * FROM training_dates WHERE training_id_from_saloon_orders = '$id'";
                $response = mysqli_query($con, $datesSQL);
                $result = mysqli_fetch_array($response);
                if (empty($result)) {
                ?>
                  <span class="badge bg-warning text-white p-1">Unset</span>
                <?php
                }else{
                  ?>
                <?= $result["start_date"] ?>
                  <?php
                }
                ?>
                <br>
                <button class="btn btn-danger p-1" data-bs-toggle="modal" data-bs-target="#setStartDateModal">Set Date</button>
                <!-- SET DATE -->
                <form action="" method="post">
                  <div class="modal fade" id="setStartDateModal">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          Set Start Date
                        </div>
                        <div class="modal-body">
                          <label for="" class="form-label">Select date</label>
                          <input type="date" name="date" class="form-control" required>
                          <input type="hidden" name="training_id_from_saloon_orders" value="<?= $row["id"] ?>">
                          <input type="hidden" name="real_training_id" value="<?= $row["real_training_id"] ?>">
                          <input type="hidden" name="customer_name" value="<?= $row["customer_id"] ?>">
                        </div>
                        <div class="modal-footer">
                          <button class="btn btn-secondary" data-bs-close="modal">Cancel</button>
                          <button class="btn btn-danger" name="set-date" type="submit">Set Date</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </td>
              <!-- REMINDER INTERVAL -->
              <td>
                <?php
                if(!empty($row["reminder_interval"])){
                  ?>
                  <?= $row["reminder_interval"] ?>
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
                  if ($row['reminder_unit'] == $output["short_form"]) {
                ?>
                    <?= $output["full_form"] ?>
                <?php
                  }
                }
                ?>
                  <?php
                }else{
                  ?>
                  <span class="badge bg-warning text-white p-1">Unset</span>
                  <?php
                }
                ?>
                <button class="btn btn-danger p-1" data-bs-toggle="modal" data-bs-target="#setReminderModal">Set Reminder</button>
                <form action="" method="post">
                  <div class="modal fade" id="setReminderModal">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          Set Reminder
                        </div>
                        <div class="modal-body">
                          <label for="" class="form-label">Enter Interval</label>
                          <input type="number" name="interval" class="form-control" required>
                          <label for="" class="form-label">Select Unit</label>
                          <select name="unit" id="" class="form-control">
                            <option value="">---- SELECT UNIT ----</option>
                            <option value="d">Day(s)</option>
                            <option value="w">Week(s)</option>
                            <option value="m">Month(s)</option>
                            <option value="y">Year(s)</option>
                          </select>
                          <input type="hidden" name="training_id_from_saloon_orders" value="<?= $row["id"] ?>">
                          <!-- <input type="hidden" name="real_training_id" value="<?= $row["real_training_id"] ?>"> -->
                          <!-- <input type="hidden" name="customer_name" value="<?= $row["customer_id"] ?>"> -->
                        </div>
                        <div class="modal-footer">
                          <button class="btn btn-secondary" data-bs-close="modal">Cancel</button>
                          <button class="btn btn-danger" name="set-reminder" type="submit">Set Reminder</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
                <?php
                if (isset($_POST["set-reminder"])) {
                  $id = $_POST["training_id_from_saloon_orders"];
                  $interval = $_POST["interval"];
                  $unit = $_POST["unit"];
                  $query = "UPDATE training_dates SET reminder_interval = '$interval', reminder_unit = '$unit' WHERE training_id_from_saloon_orders = '$id'";
                  if (mysqli_query($con, $query)) {
                ?>
                    <script>
                      alert("Reminder updated successfully!");
                    </script>
                  <?php
                  } else {
                  ?>
                    <script>
                      alert("Reminder updated successfully!");
                    </script>
                <?php
                  }
                  unset($_POST["set-reminder"]);
                  header("Refresh: 2");
                }
                ?>
              </td>
              <td><span class='badge <?= $bg ?>' style='text-transform:capitalize;'><?= $status ?></span></td>
              <td>
                <div class="dropdown">
                  <button class='btn btn-sm btn-primary dropdown-toggle' data-toggle="dropdown">
                    Action
                  </button>
                  <div class="dropdown-menu">
                    <a href='viewacademy.php?order=<?= $row['id'] ?>' class="dropdown-item">View booking</a>
                  </div>
                </div>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer"></div>
  </div>
</div>
<?php include "footer.php"; ?>