<?php include "header.php"; ?>

<?php
// Handle completion
if (isset($_GET['order']) && !empty($_GET['order'])) {
  $order_id = $_GET['order'];
  $sql = "UPDATE saloon_orders SET status = 'completed' WHERE id = ?";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "s", $order_id);
  if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
      echo "<script>alert('Order updated successfully!');</script>";
      header("Location: onlineorders.php");
    } else {
      echo "<script>alert('No rows updated. Order may not exist or already marked as completed.');</script>";
      header("Location: onlineorders.php");
    }
  } else {
    $error = mysqli_error($con);
    echo "<script>alert('Error updating order status: " . addslashes($error) . "');</script>";
    header("Location: onlineorders.php");
  }
  mysqli_stmt_close($stmt);
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Online Orders</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Orishirishi</li>
  </ol>
</div>

<!-- Invoice Example -->
<div class="col-xl-12 col-lg-12 mb-4">
  <div class="card">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Online Orders</h6>
    </div>

    <div class="table-responsive">
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th>SN</th>
            <th>Booking ID</th>
            <th>Customer</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>

          <?php
          // Pagination settings
          $limit = 30; // Rows per page
          $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
          if ($page < 1)
            $page = 1;

          // Check if date column exists
          $has_date = false;
          $result = mysqli_query($con, "SHOW COLUMNS FROM saloon_orders LIKE 'date'");
          if (mysqli_num_rows($result) > 0) {
            $has_date = true;
          }

          // Build SQL query
          $sql = "SELECT id, name, total_amount, status, pay_status, shipping_fee" . ($has_date ? ", date" : "") . " FROM saloon_orders WHERE section='refreshments' 
      AND type='online' 
      AND (pay_status='pending' OR pay_status='complete' OR pay_status='paid')";

          $sql .= $has_date ? " ORDER BY date DESC" : " ORDER BY id DESC";
          $count_query = "SELECT COUNT(*) AS total_rows FROM saloon_orders WHERE section='refreshments' 
      AND type='online' 
      AND (pay_status='pending' OR pay_status='complete' OR pay_status='paid')";

          // Execute count query
          $count_stmt = mysqli_prepare($con, $count_query);
          mysqli_stmt_execute($count_stmt);
          $count_result = mysqli_stmt_get_result($count_stmt) or die("Database error: " . mysqli_error($con));
          $total_rows = mysqli_fetch_assoc($count_result)['total_rows'];
          mysqli_stmt_close($count_stmt);

          $total_pages = ceil($total_rows / $limit);
          if ($page > $total_pages)
            $page = $total_pages;
          $offset = ($page - 1) * $limit;

          // Add LIMIT/OFFSET to main query
          $sql .= " LIMIT ? OFFSET ?";
          $stmt = mysqli_prepare($con, $sql);
          mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
          mysqli_stmt_execute($stmt);
          $resultset = mysqli_stmt_get_result($stmt) or die("Database error: " . mysqli_error($con));

          $i = ($page - 1) * $limit + 1; // Adjust SN for pagination
          while ($row = mysqli_fetch_assoc($resultset)) {
            $status = isset($row['status']) && $row['status'] !== '' ? $row['status'] : 'pending';

            // Color
            if ($status == "no") {
              $bg = "badge-warning";
              $status = "booking";
            } else if ($status == "processing") {
              $bg = "badge-primary";
            } else if ($status == "cancelled") {
              $bg = "badge-danger";
            } else if ($status == "processed" || $status == "completed") {
              $bg = "badge-success";
            } else {
              $bg = "badge-danger";
            }
            $hasPaid = $row['pay_status'] == 'paid' ? 1 : 0;
            $completeHidden = !$hasPaid ? 'hidden' : '';
            $completeDisabled = ($status == "processed" || $status == "completed" || $completeHidden) ? 'disabled' : '';
            $completeText = ($status == "processed" || $status == "completed") ? 'Completed' : 'Complete';
            $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
            $date = $has_date && !empty($row['date']) ? date('d/m/Y', strtotime($row['date'])) : '-';
            $combined_total = (float) $row['total_amount'] + (float) ($row['shipping_fee'] ?? 0);
            echo "
        <tr>
            <td>" . $i++ . "</td>
            <td>" . $id . "</td>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>&#8358; " .$combined_total . "</td>
            <td><span class='badge $bg' style='text-transform:capitalize;'>$status</span></td>
            <td>" . $date . "</td>
            <td>
              <div class='dropdown'>
                <button class='btn btn-sm btn-primary dropdown-toggle' type='button' id='dropdownMenu_$id' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  Action
                </button>
                <div class='dropdown-menu' aria-labelledby='dropdownMenu_$id'>
                  <a class='dropdown-item' href='viewbooking.php?order=" . $id . "'>View Order Details</a>
                  <a class='dropdown-item' href='saloonreciept.php?order=" . $id . "'>Print Receipt</a>
                  <a class='dropdown-item $completeDisabled $completeHidden' href='javascript:void(0);' onclick='confirmPayment(\"" . $id . "\")'>$completeText</a>
                  <a class='dropdown-item' href='javascript:void(0);' onclick='confirmRefund(\"" . $id . "\")'>Refund</a>
                </div>
              </div>
            </td>
        </tr>";
          }

          mysqli_stmt_close($stmt);
          ?>

        </tbody>
      </table>
    </div>

    <?php
    // Pagination links
    if ($total_pages > 1) {
      $max_links = 5;
      $start = max(1, $page - floor($max_links / 2));
      $end = min($total_pages, $start + $max_links - 1);

      echo "<nav><ul class='pagination justify-content-center'>";
      if ($page > 1) {
        echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Prev</a></li>";
      }
      for ($i = $start; $i <= $end; $i++) {
        $active = ($i == $page) ? 'active' : '';
        echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>$i</a></li>";
      }
      if ($page < $total_pages) {
        echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Next</a></li>";
      }
      echo "</ul></nav>";
    }
    ?>

    <div class="card-footer"></div>
  </div>
  <script>
    function confirmPayment(orderId) {
      console.log('Button clicked for order ID: ' + orderId); // Debugging
      try {
        if (confirm("Are you sure you want to mark this order as complete?")) {
          window.location.href = 'onlineorders.php?order=' + encodeURIComponent(orderId);
        }
      } catch (e) {
        console.error('Error in online orders: ', e);
      }
    }

    function confirmRefund(orderId) {
      console.log('Refund clicked for order ID: ' + orderId); // Debugging
      try {
        if (confirm("Are you sure you want to initiate a refund for this order?")) {
          window.location.href = 'refunditems.php?order=' + encodeURIComponent(orderId);
        }
      } catch (e) {
        console.error('Error in refund action: ', e);
      }
    }
  </script>
</div>

<?php include "footer.php"; ?>