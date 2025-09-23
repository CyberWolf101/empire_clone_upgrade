<?php include "header.php"; ?>

<?php
// Default values
$default_from = date("Y-m-01"); // First day of current month
$default_to = date("Y-m-d");    // Today

// Use POST values if form submitted, otherwise defaults
$from = $_POST['from'] ?? $default_from;
$to = $_POST['to'] ?? $default_to;

// Sanitize dates
$from = mysqli_real_escape_string($con, $from);
$to = mysqli_real_escape_string($con, $to);

// Validate dates
if (!strtotime($from) || !strtotime($to)) {
    $error = "Invalid date format. Using default date range.";
    $from = $default_from;
    $to = $default_to;
}

// Ensure 'to' date includes the full day
$to = date("Y-m-d", strtotime($to)) . " 23:59:59";

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h5 mb-0 text-gray-800">Orishirishi Stocks Report</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Reports</li>
  </ol>
</div>

<div class="col-xl-12 col-lg-12 mb-4">
  <center>
    <div style="text-align:left;">
      <p>View processed sales and stock transactions in the Orishirishi department for a particular period of time. Select a custom date range and submit to view the report.</p>
      <form action="itemsreport.php" method="post" id="report-form">
        <p><label>From</label><input type="date" class="form-control" name="from" value="<?= htmlspecialchars($from) ?>" required /></p>
        <p><label>To</label><input type="date" class="form-control" name="to" value="<?= htmlspecialchars($to) ?>" required /></p>
        <p><button type="submit" name="submit" class="btn btn-sm btn-primary shadow-sm">View Report</button></p>
      </form>
    </div>
  </center>
</div>

<?php
// Display selected range
// echo "<p>Showing records from " . htmlspecialchars($from) . " to " . htmlspecialchars(date("Y-m-d", strtotime($to))) . "</p>";

// Pagination settings
$limit = 200; // Rows per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Check if refreshments has processed records in the date range
$refreshments_check = "SELECT COUNT(*) AS count FROM refreshments WHERE date BETWEEN ? AND ? AND status = 'processed'";
$refreshments_stmt = mysqli_prepare($con, $refreshments_check);
mysqli_stmt_bind_param($refreshments_stmt, "ss", $from, $to);
mysqli_stmt_execute($refreshments_stmt);
$refreshments_result = mysqli_stmt_get_result($refreshments_stmt);
$refreshments_count = mysqli_fetch_assoc($refreshments_result)['count'];
mysqli_stmt_close($refreshments_stmt);

// Build SQL query with UNION ALL to combine refreshments and stock_transactions
$sql_query = "
    SELECT item, quantity, total_left, date, 'sold' AS action FROM refreshments WHERE date BETWEEN ? AND ? AND status = 'processed'
    UNION ALL
    SELECT item, quantity, total_left, date, action FROM stock_transactions WHERE date BETWEEN ? AND ?
    ORDER BY date DESC
    LIMIT ? OFFSET ?
";
$count_query = "
    SELECT COUNT(*) AS total_rows FROM (
        SELECT item FROM refreshments WHERE date BETWEEN ? AND ? AND status = 'processed'
        UNION ALL
        SELECT item FROM stock_transactions WHERE date BETWEEN ? AND ?
    ) AS combined
";

// Execute count query
$count_stmt = mysqli_prepare($con, $count_query);
mysqli_stmt_bind_param($count_stmt, "ssss", $from, $to, $from, $to);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt) or die("Database error: " . mysqli_error($con));
$total_rows = mysqli_fetch_assoc($count_result)['total_rows'];
mysqli_stmt_close($count_stmt);

$total_pages = ceil($total_rows / $limit);
if ($page > $total_pages) $page = $total_pages;
$offset = ($page - 1) * $limit;

// Execute main query
$stmt = mysqli_prepare($con, $sql_query);
mysqli_stmt_bind_param($stmt, "ssssii", $from, $to, $from, $to, $limit, $offset);
mysqli_stmt_execute($stmt);
$resultset = mysqli_stmt_get_result($stmt) or die("Database error: " . mysqli_error($con));

if (mysqli_num_rows($resultset) > 0) {
    // Indicate if only stock transactions are shown
    if ($refreshments_count == 0) {
        // echo "<p class='text-info mt-2'>No processed sales records found for this date range. Showing only stock transactions.</p>";
    }

    echo "<div class='table-responsive mt-4'>";
    echo "<table class='table table-bordered'>";
    echo "<thead><tr>
            <th>Date</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Total Left</th>
            <th>Action</th>
          </tr></thead><tbody>";

    while ($row = mysqli_fetch_assoc($resultset)) {
        // Determine color based on action
        $color_class = in_array(strtolower($row['action']), ['sold', 'subtract']) ? 'text-danger' : 'text-success';
        echo "<tr>
                <td>" . htmlspecialchars($row['date']) . "</td>
                <td>" . htmlspecialchars($row['item']) . "</td>
                <td class='$color_class'>" . htmlspecialchars($row['quantity']) . "</td>
                <td>" . htmlspecialchars($row['total_left']) . "</td>
                <td class='$color_class'>" . htmlspecialchars(ucfirst($row['action'])) . "</td>
              </tr>";
    }

    echo "</tbody></table></div>";

    // Pagination links
    if ($total_pages > 1) {
        $max_links = 5;
        $start = max(1, $page - floor($max_links / 2));
        $end = min($total_pages, $start + $max_links - 1);

        echo "<nav><ul class='pagination justify-content-center'>";
        if ($page > 1) {
            echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "&from=" . urlencode($from) . "&to=" . urlencode($to) . "'>Prev</a></li>";
        }
        for ($i = $start; $i <= $end; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo "<li class='page-item $active'><a class='page-link' href='?page=$i&from=" . urlencode($from) . "&to=" . urlencode($to) . "'>$i</a></li>";
        }
        if ($page < $total_pages) {
            echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "&from=" . urlencode($from) . "&to=" . urlencode($to) . "'>Next</a></li>";
        }
        echo "</ul></nav>";
    }
} else {
    echo "<p class='text-danger mt-4'>No records found for the selected date range.</p>";
}

if (isset($error)) {
    echo "<p class='text-danger mt-4'>" . htmlspecialchars($error) . "</p>";
}

mysqli_stmt_close($stmt);
?>

<?php include "footer.php"; ?>