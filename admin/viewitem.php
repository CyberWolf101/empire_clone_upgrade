<?php include "header.php"; ?>

<?php
$error = '';
$itemid = null;
$original_s = isset($_GET['s']) ? mysqli_real_escape_string($con, $_GET['s']) : null;

// Fetch itemid for the given s
if ($original_s) {
    $sql = "SELECT itemid, item FROM refreshments WHERE s = ? AND status = 'processed'";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $original_s);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $itemid = $row['itemid'];
        $item_name = $row['item'];
    } else {
        $error = "No processed sale found for the specified ID.";
    }
    mysqli_stmt_close($stmt);
} else {
    $error = "No record ID specified.";
}

// Pagination settings
$limit = 30; // Rows per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

if (!$error && $itemid) {
    // Build SQL query for all processed records with the same itemid
    $sql_query = "
        SELECT s, item, quantity, total_left, date
        FROM refreshments
        WHERE itemid = ? AND status = 'processed'
        ORDER BY date DESC
        LIMIT ? OFFSET ?
    ";
    $count_query = "
        SELECT COUNT(*) AS total_rows
        FROM refreshments
        WHERE itemid = ? AND status = 'processed'
    ";

    // Execute count query
    $count_stmt = mysqli_prepare($con, $count_query);
    mysqli_stmt_bind_param($count_stmt, "i", $itemid);
    mysqli_stmt_execute($count_stmt);
    $count_result = mysqli_stmt_get_result($count_stmt) or die("Database error: " . mysqli_error($con));
    $total_rows = mysqli_fetch_assoc($count_result)['total_rows'];
    mysqli_stmt_close($count_stmt);

    $total_pages = ceil($total_rows / $limit);
    if ($page > $total_pages) $page = $total_pages;
    $offset = ($page - 1) * $limit;

    // Execute main query
    $stmt = mysqli_prepare($con, $sql_query);
    mysqli_stmt_bind_param($stmt, "iii", $itemid, $limit, $offset);
    mysqli_stmt_execute($stmt);
    $resultset = mysqli_stmt_get_result($stmt) or die("Database error: " . mysqli_error($con));
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h5 mb-0 text-gray-800"><?php echo $itemid ? ' ' . htmlspecialchars($item_name) . '' : 'N/A'; ?></h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item"><a href="foodsalesreport.php">Food Sales Report</a></li>
    <li class="breadcrumb-item active" aria-current="page">Item Sales</li>
  </ol>
</div>

<div class="col-xl-12 col-lg-12 mb-4">
  <center>
    <?php if ($error): ?>
      <p class="text-danger"><?php echo htmlspecialchars($error); ?></p>
      <a href="foodsalesreport.php" class="btn btn-sm btn-primary">Back to Sales Report</a>
    <?php else: ?>
      <?php if (mysqli_num_rows($resultset) > 0): ?>
        <div class="table-responsive mt-4">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Date</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Total Left</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($resultset)): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['date']); ?></td>
                  <td><?php echo htmlspecialchars($row['item']); ?></td>
                  <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                  <td><?php echo htmlspecialchars($row['total_left']); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination links -->
        <?php if ($total_pages > 1): ?>
          <nav>
            <ul class="pagination justify-content-center">
              <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?s=<?php echo urlencode($original_s); ?>&page=<?php echo $page - 1; ?>">Prev</a></li>
              <?php endif; ?>
              <?php
              $max_links = 5;
              $start = max(1, $page - floor($max_links / 2));
              $end = min($total_pages, $start + $max_links - 1);
              for ($i = $start; $i <= $end; $i++):
                $active = ($i == $page) ? 'active' : '';
              ?>
                <li class="page-item <?php echo $active; ?>"><a class="page-link" href="?s=<?php echo urlencode($original_s); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
              <?php endfor; ?>
              <?php if ($page < $total_pages): ?>
                <li class="page-item"><a class="page-link" href="?s=<?php echo urlencode($original_s); ?>&page=<?php echo $page + 1; ?>">Next</a></li>
              <?php endif; ?>
            </ul>
          </nav>
        <?php endif; ?>
      <?php else: ?>
        <p class="text-danger mt-4">No processed sales records found for Item ID: <?php echo htmlspecialchars($itemid); ?>.</p>
      <?php endif; ?>
      <a href="foodsalesreport.php" class="btn btn-sm btn-primary">Back to Sales Report</a>
    <?php endif; ?>
  </center>
</div>

<?php if (isset($stmt)) mysqli_stmt_close($stmt); ?>
<?php include "footer.php"; ?>