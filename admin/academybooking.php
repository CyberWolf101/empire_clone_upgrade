<?php
include "header.php";
include "../mailer.php";

/* =========================
   HANDLE SET DATE
========================= */
if (isset($_POST["set-date"])) {

  $date = mysqli_real_escape_string($con, $_POST["date"]);
  $training_id_from_saloon_orders = mysqli_real_escape_string($con, $_POST["training_id_from_saloon_orders"]);
  $training_id = mysqli_real_escape_string($con, $_POST["real_training_id"]);

  // ✅ FIXED: correct key
  $customer_id = mysqli_real_escape_string($con, $_POST["customer_id"]);

  $dbUpdatedOrInserted = false;

  $findSQL = "SELECT * FROM training_dates 
              WHERE training_id_from_saloon_orders = '$training_id_from_saloon_orders'";
  $res = mysqli_query($con, $findSQL);
  $result = mysqli_fetch_assoc($res);

  if ($result) {
    $executeSQL = "UPDATE training_dates 
                   SET start_date = '$date' 
                   WHERE training_id_from_saloon_orders = '$training_id_from_saloon_orders'";
  } else {
    $executeSQL = "INSERT INTO training_dates 
                   (start_date, training_id_from_saloon_orders) 
                   VALUES ('$date', '$training_id_from_saloon_orders')";
  }

  if (mysqli_query($con, $executeSQL)) {
    $dbUpdatedOrInserted = true;
  }

  /* =========================
     SEND EMAIL ONLY IF SUCCESS
  ========================== */
  if ($dbUpdatedOrInserted) {

    $customerSql = "SELECT * FROM customers WHERE unique_id = '$customer_id'";
    $customerRes = mysqli_query($con, $customerSql);

    if ($customerRes && mysqli_num_rows($customerRes) > 0) {

      $customerData = mysqli_fetch_assoc($customerRes);
      $toEmail = $customerData['email'];
      $recipientName = $customerData['name'];

      // ✅ SAFE DATE FORMAT
      $formattedDate = (!empty($date) && strtotime($date))
        ? date("F j, Y", strtotime($date))
        : "Not set";

      /* ITEMS */
      $itemsSql = "SELECT item_name 
                   FROM training_items_to_bring 
                   WHERE training_id = '$training_id'";
      $itemsRes = mysqli_query($con, $itemsSql);

      $itemsListString = "";

      if ($itemsRes && mysqli_num_rows($itemsRes) > 0) {
        while ($itemRow = mysqli_fetch_assoc($itemsRes)) {
          $itemsListString .= "<li>" . htmlspecialchars($itemRow['item_name']) . "</li>";
        }
      } else {
        $itemsListString = "<li>No specific requirements listed.</li>";
      }

      $subject = "Training Schedule Update - CHBLUXURYEMPIRE";

      $message = "
        <html>
        <body>
          <h2>Hello " . htmlspecialchars($recipientName) . "</h2>

          <p>Your training schedule has been updated.</p>

          <p><strong>Start Date:</strong> $formattedDate</p>

          <h3>Items to Bring:</h3>
          <ul>$itemsListString</ul>

          <p>Best regards,<br>Training Team</p>
        </body>
        </html>";

      sendEmail($toEmail, $subject, $message);

      
    }
  }

  unset($_POST["set-date"]);
}


/* =========================
   HANDLE REMINDER
========================= */
if (isset($_POST["set-reminder"])) {

  $id = mysqli_real_escape_string($con, $_POST["training_id_from_saloon_orders"]);
  $interval = mysqli_real_escape_string($con, $_POST["interval"]);
  $unit = mysqli_real_escape_string($con, $_POST["unit"]);

  $query = "UPDATE training_dates 
            SET reminder_interval = '$interval', reminder_unit = '$unit' 
            WHERE training_id_from_saloon_orders = '$id'";

  if (mysqli_query($con, $query)) {
    echo "<script>alert('Reminder updated successfully!');</script>";
  } else {
    echo "<script>alert('Failed to update reminder');</script>";
  }

  header("Refresh: 1");
  exit;
}
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Academy Bookings</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Academy</li>
  </ol>
</div>

<div class="col-xl-12 col-lg-12 mb-4">
  <div class="card">
    <div class="card-header">
      <h6 class="m-0 font-weight-bold text-primary">Rentals</h6>
    </div>

    <div class="table-responsive">
      <table class="table table-flush">
        <thead>
          <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Start Date</th>
            <!-- <th>Reminder</th> -->
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>

          <?php
          $sql = "SELECT
    s.*,
    a.training AS real_training_id,
    a.price AS tuition_price,
    COALESCE(items.training_items_total,0) AS training_items_total,
    (
        SELECT c.unique_id
        FROM customers c
        WHERE c.name = s.name
        LIMIT 1
    ) AS customer_id,
    (
        SELECT td.reminder_interval
        FROM training_dates td
        WHERE td.training_id_from_saloon_orders = s.id
        LIMIT 1
    ) AS reminder_interval,
    (
        SELECT td.reminder_unit
        FROM training_dates td
        WHERE td.training_id_from_saloon_orders = s.id
        LIMIT 1
    ) AS reminder_unit
FROM saloon_orders s
LEFT JOIN academy_cart a ON a.id = s.id
LEFT JOIN (
    SELECT act.item_for, SUM(ti.price) AS training_items_total
    FROM academy_cart_training_items act
    JOIN training_items ti ON ti.item_id = act.training_item_id
    GROUP BY act.item_for
) items ON items.item_for = a.id
WHERE s.section='academy' AND s.pay_status='paid'
ORDER BY s.id DESC";

          $res = mysqli_query($con, $sql);

          while ($row = mysqli_fetch_assoc($res)) {

            $status = strtolower($row['status']);
            $pay_status = strtolower($row['pay_status']);

            $grandTotal = (float)$row['tuition_price'] + (float)$row['training_items_total'];

            $datesSQL = "SELECT * FROM training_dates 
                 WHERE training_id_from_saloon_orders = '{$row['id']}'";
            $dateRes = mysqli_query($con, $datesSQL);
            $dateRow = mysqli_fetch_assoc($dateRes);

          ?>

            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= $row['name'] ?></td>

              <td>₦<?= number_format($grandTotal, 2) ?></td>

              <td>
                <span class="badge"><?= $pay_status ?></span>
              </td>

              <td>
                <?php if ($dateRow) {
                  echo $dateRow['start_date'];
                } else {
                  echo "<span class='badge bg-warning'>Unset</span>";
                } ?>

                <?php if ($status == "completed") { ?>
                  <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#setStartDate<?= $row['id'] ?>">
                    Set
                  </button>
                <?php } ?>

                <!-- START DATE MODAL -->
                <div class="modal fade" id="setStartDate<?= $row['id'] ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form method="post">
                        <div class="modal-header">Set Start Date</div>

                        <div class="modal-body">
                          <input type="date" name="date" class="form-control" required>

                          <input type="hidden" name="training_id_from_saloon_orders" value="<?= $row['id'] ?>">
                          <input type="hidden" name="real_training_id" value="<?= $row['real_training_id'] ?>">
                          <input type="hidden" name="customer_id" value="<?= $row['customer_id'] ?>">
                        </div>

                        <div class="modal-footer">
                          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                          <button class="btn btn-danger" name="set-date">Save</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

              </td>
<!-- 
        
REMINDER

-->
              <!-- <td>
                <?= $row['reminder_interval'] ?? "<span class='badge bg-warning'>Unset</span>" ?>
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

                <?php if ($status == "completed") { ?>
                  <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reminder<?= $row['id'] ?>">
                    Set
                  </button>
                <?php } ?>

                
                <div class="modal fade" id="reminder<?= $row['id'] ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form method="post">
                        <div class="modal-header">Set Reminder</div>

                        <div class="modal-body">
                          <input type="number" name="interval" class="form-control" placeholder="* Reminder Interval" required>

                          <select name="unit" class="form-control mt-2" required>
                            <option value="">----- Reminder Unit -----</option>
                            <option value="d">Day(s)</option>
                            <option value="w">Week(s)</option>
                            <option value="m">Month(s)</option>
                            <option value="y">Year(s)</option>
                          </select>

                          <input type="hidden" name="training_id_from_saloon_orders" value="<?= $row['id'] ?>">
                        </div>

                        <div class="modal-footer">
                          <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button class="btn btn-primary" name="set-reminder">Save</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

              </td> -->

              <td>
                <span class="badge"><?= $status ?></span>
              </td>

              <td>
                <a href="viewacademy.php?order=<?= $row['id'] ?>">View</a> |
                <a href="deleteacademy.php?order=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete booking ' + '<?= $row['id'] ?>' + '?' )">Delete</a>
              </td>

            </tr>

          <?php } ?>

        </tbody>
      </table>
    </div>

  </div>
</div>

<?php include "footer.php"; ?>