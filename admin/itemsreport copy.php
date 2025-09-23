<?php include "header.php"; ?>

<?php
// Set timezone for accurate dates
date_default_timezone_set('Africa/Lagos');

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
      <p>View items sold in the Orishirishi department for a particular period of time. Select a custom date range and submit to view the report.</p>
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
echo "<p>Showing records from " . htmlspecialchars($from) . " to " . htmlspecialchars($to) . "</p>";

// Pagination settings
$limit = 30; // Rows per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Build SQL query with date filter
$sql_query = "SELECT item, quantity, total_left, date FROM refreshments WHERE date BETWEEN ? AND ? ORDER BY date DESC";
$count_query = "SELECT COUNT(*) AS total_rows FROM refreshments WHERE date BETWEEN ? AND ?";

// Execute count query
$count_stmt = mysqli_prepare($con, $count_query);
mysqli_stmt_bind_param($count_stmt, "ss", $from, $to);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt) or die("Database error: " . mysqli_error($con));
$total_rows = mysqli_fetch_assoc($count_result)['total_rows'];
mysqli_stmt_close($count_stmt);

$total_pages = ceil($total_rows / $limit);
if ($page > $total_pages) $page = $total_pages;
$offset = ($page - 1) * $limit;

// Add LIMIT/OFFSET to main query
$sql_query .= " LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($con, $sql_query);
mysqli_stmt_bind_param($stmt, "ssii", $from, $to, $limit, $offset);
mysqli_stmt_execute($stmt);
$resultset = mysqli_stmt_get_result($stmt) or die("Database error: " . mysqli_error($con));

if (mysqli_num_rows($resultset) > 0) {
    echo "<div class='table-responsive mt-4'>";
    echo "<table class='table table-bordered'>";
    echo "<thead><tr>
            <th>Date</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Total Left</th>
          </tr></thead><tbody>";

    while ($row = mysqli_fetch_assoc($resultset)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['date']) . "</td>
                <td>" . htmlspecialchars($row['item']) . "</td>
                <td>" . htmlspecialchars($row['quantity']) . "</td>
                <td>" . htmlspecialchars($row['total_left']) . "</td>
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