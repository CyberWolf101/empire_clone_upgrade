<?php include "header.php"; ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Academy Bookings</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Academy</li>
  </ol>
</div>
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
            <th>SN</th>
            <th>Academy ID</th>
            <th>Customer</th>
            <th>Total Amount</th>
            <th>Payment Status</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT  * FROM saloon_orders where pay_status='paid' AND section='academy' ORDER BY s DESC";
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
              <td><?= $i++ ?></td>
              <td><?= $row['id'] ?></td>
              <td><?= $row['name'] ?></td>
              <td>&#8358;<?= $row['total_amount'] ?></td>
              <td><span class='badge <?= $statusbg ?>' style='text-transform:capitalize;'><?= $pay_status ?></span></td>
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