<?php include "header.php"; ?>

<?php
// Default values
$default_from = date("Y-01-01"); // First day of current year
$default_to = date("Y-m-d");     // Today

// Check if form was submitted via POST, otherwise use GET or defaults
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = $_POST['from'] ?? $default_from;
    $to = $_POST['to'] ?? $default_to;
    $period = $_POST['period'] ?? 'daily';
    $itemid = $_POST['itemid'] ?? '';

    // Sanitize inputs
    $from = mysqli_real_escape_string($con, $from);
    $to = mysqli_real_escape_string($con, $to);
    $period = mysqli_real_escape_string($con, $period);
    $itemid = mysqli_real_escape_string($con, $itemid);

    // Validate dates
    if (!strtotime($from) || !strtotime($to)) {
        $error = "Invalid date format. Using default date range.";
        $from = $default_from;
        $to = $default_to;
    }
    $default_from = $from;
    $default_to = $to;
    // Redirect to GET to avoid form resubmission
    $redirect_url = "foodsalesreport.php?from=" . urlencode($from) . "&to=" . urlencode($to) . "&period=" . urlencode($period) . ($itemid ? "&itemid=" . urlencode($itemid) : "");
    header("Location: $redirect_url");
    exit;
} else {
    // Use GET parameters or defaults
    $from = $_GET['from'] ?? $default_from;
    $to = $_GET['to'] ?? $default_to;
    $period = $_GET['period'] ?? 'daily';
    $itemid = $_GET['itemid'] ?? '';

    // Sanitize inputs
    $from = mysqli_real_escape_string($con, $from);
    $to = mysqli_real_escape_string($con, $to);
    $period = mysqli_real_escape_string($con, $period);
    $itemid = mysqli_real_escape_string($con, $itemid);

    // Validate dates
    if (!strtotime($from) || !strtotime($to)) {
        $error = "Invalid date format. Using default date range.";
        $from = $default_from;
        $to = $default_to;
    }
}

// Ensure 'to' date includes the full day
$to_sql = date("Y-m-d 23:59:59", strtotime($to));
$from_sql = date("Y-m-d 00:00:00", strtotime($from));
$selectedFrom = new DateTime($from_sql);
$selectedTo = new DateTime($to_sql);
// Get unique items for dropdown
$item_query = "SELECT * FROM refreshments WHERE status = 'processed' ORDER BY item";
$item_result = mysqli_query($con, $item_query);
$items = [];
while ($row = mysqli_fetch_assoc($item_result)) {
    $items[] = $row;
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h5 mb-0 text-gray-800">Orishirishi Food Sales Report</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Food Sales Report</li>
    </ol>
</div>

<div class="col-xl-12 col-lg-12 mb-4">
    <center>
        <div style="text-align:left;">
            <p>View processed sales in the Orishirishi department for a particular period of time.</p>
            <form action="foodsalesreport.php" method="post" id="report-form">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="small">From</label>
                        <input type="date" class="form-control" name="from"
                            value="<?= htmlspecialchars($from, ENT_QUOTES, 'UTF-8') ?>" />
                    </div>
                    <div class="col-6 mb-3">
                        <label class="small">To</label>
                        <input type="date" class="form-control" name="to"
                            value="<?= htmlspecialchars($to, ENT_QUOTES, 'UTF-8') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="small">Period</label>
                        <select class="form-control" name="period" required>
                            <option value="daily" <?= $period === 'daily' ? 'selected' : '' ?>>Daily</option>
                            <option value="weekly" <?= $period === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                            <option value="monthly" <?= $period === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                            <option value="yearly" <?= $period === 'yearly' ? 'selected' : '' ?>>Yearly</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="small">Food (Optional)</label>
                        <select class="form-control" name="itemid">
                            <option value="">All Items</option>
                            <?php foreach ($items as $item): ?>
                                <option value="<?= htmlspecialchars($item['itemid'], ENT_QUOTES, 'UTF-8') ?>"
                                    <?= $itemid === $item['itemid'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($item['item'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <p><button type="submit" name="submit" class="btn btn-sm btn-primary shadow-sm">View Report</button></p>
            </form>
        </div>
    </center>
</div>
<?php
// Pagination settings
$limit = 100; // Rows per page for daily and weekly
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;

// Build SQL query based on period and itemid
$group_by = '';
$select_date = 'date';
$order_by = 'date DESC';
$count_distinct = '';
if ($period == 'weekly') {
    $select_date = "DATE_FORMAT(r.date, '%Y-%u') AS period";
    $group_by = "GROUP BY DATE_FORMAT(r.date, '%Y-%u')";
    $order_by = "DATE_FORMAT(r.date, '%Y-%u') DESC";
    $count_distinct = "DATE_FORMAT(date, '%Y-%u')";
} elseif ($period == 'monthly') {
    $select_date = "DATE_FORMAT(r.date, '%Y-%m') AS period";
    $group_by = "GROUP BY DATE_FORMAT(r.date, '%Y-%m')";
    $order_by = "DATE_FORMAT(r.date, '%Y-%m') DESC";
    $count_distinct = "DATE_FORMAT(date, '%Y-%m')";
} elseif ($period == 'yearly') {
    $select_date = "DATE_FORMAT(r.date, '%Y') AS period";
    $group_by = "GROUP BY DATE_FORMAT(r.date, '%Y')";
    $order_by = "DATE_FORMAT(r.date, '%Y') DESC";
    $count_distinct = "DATE_FORMAT(date, '%Y')";
} else {
    // Daily: Group by date
    $select_date = "DATE_FORMAT(r.date, '%Y-%m-%d') AS period";
    $group_by = "GROUP BY DATE_FORMAT(r.date, '%Y-%m-%d')";
    $count_distinct = "DATE_FORMAT(date, '%Y-%m-%d')";
}

$sql_query = $itemid ? "
    SELECT $select_date, r.item, SUM(r.quantity) AS total_quantity, SUM(r.totalprice) AS total_price, r.s,
           (SELECT total_left FROM refreshments r2
            WHERE r2.itemid = r.itemid
            AND DATE_FORMAT(r2.date, '%Y-%m-%d') <= r.date
            AND r2.date BETWEEN ? AND ?
            AND r2.status = 'processed'
            ORDER BY r2.date DESC LIMIT 1) AS total_left
    FROM refreshments r
    WHERE r.date >= ? AND r.date <= ? AND r.status = 'processed' AND r.itemid = ?
    $group_by
    ORDER BY $order_by
    LIMIT ? OFFSET ?
" : "
    SELECT $select_date, SUM(r.quantity) AS total_quantity, SUM(r.totalprice) AS total_price
    FROM refreshments r
    WHERE r.date >= ? AND r.date <= ? AND r.status = 'processed'
    $group_by
    ORDER BY $order_by
    LIMIT ? OFFSET ?
";

$count_query = "
    SELECT COUNT(DISTINCT $count_distinct) AS total_rows
    FROM refreshments
    WHERE date BETWEEN ? AND ? AND status = 'processed'" . ($itemid ? " AND itemid = ?" : "");

// Execute count query
$count_stmt = mysqli_prepare($con, $count_query);
if ($itemid) {
    mysqli_stmt_bind_param($count_stmt, "sss", $from_sql, $to_sql, $itemid);
} else {
    mysqli_stmt_bind_param($count_stmt, "ss", $from_sql, $to_sql);
}
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt) or die("Database error: " . mysqli_error($con));
$total_rows = mysqli_fetch_assoc($count_result)['total_rows'];
mysqli_stmt_close($count_stmt);

$total_pages = ($period === 'daily' || $period === 'weekly') ? ceil($total_rows / $limit) : 1;
if ($page > $total_pages)
    $page = $total_pages;
$offset = ($period === 'daily' || $period === 'weekly') ? ($page - 1) * $limit : 0;

// Execute main query
$stmt = mysqli_prepare($con, $sql_query);
if ($itemid) {
    mysqli_stmt_bind_param($stmt, "sssssii", $from_sql, $to_sql, $from_sql, $to_sql, $itemid, $limit, $offset);
} else {
    mysqli_stmt_bind_param($stmt, "ssii", $from_sql, $to_sql, $limit, $offset);
}
mysqli_stmt_execute($stmt);
$resultset = mysqli_stmt_get_result($stmt) or die("Database error: " . mysqli_error($con));

if (mysqli_num_rows($resultset) > 0) {
        $today = date("Y-m-d");
        $filterToday = new DateTime($today);
    $totalPrices = [];
    echo "<div class='table-responsive mt-4'>";
    echo "<table class='table table-bordered'>";
    echo "<thead><tr>
            <th>" . ($itemid ? 'Date' : ucfirst($period) . ' Period') . "</th>"
        . ($itemid ? "<th>Item</th>" : "") . "
            <th>Total Quantity</th>
            <th>Total Price</th>"
             
        . ($itemid ? "<th>Total Left</th>" : "") . "
        " . ($itemid ? "<th>Action</th>" : "") . "
          </tr></thead><tbody>";

    while ($row = mysqli_fetch_assoc($resultset)) {

        $totalPrices[] = $row['total_price'];
        $calculatedPrice = 0;
        foreach($totalPrices as $price){
            $calculatedPrice += $price;
        }
        
        $display_date = $row['period'] ?? '';
        $expensesSql = "SELECT SUM(amount) AS total FROM expenses";
        $result = mysqli_query($con, $expensesSql);
        $rowE = mysqli_fetch_assoc($result);

        $totalExpenses = $rowE['total'];

        echo "<tr>
                <td>" . htmlspecialchars($display_date, ENT_QUOTES, 'UTF-8') . "</td>";
        if ($itemid) {
            echo "<td>" . htmlspecialchars($row['item'], ENT_QUOTES, 'UTF-8') . "</td>";
        }
        echo "<td>" . htmlspecialchars($row['total_quantity'], ENT_QUOTES, 'UTF-8') . "</td>
              <td>&#8358;" . htmlspecialchars($row['total_price'], ENT_QUOTES, 'UTF-8') . "</td>";
        if ($itemid) {
            echo "<td>" . htmlspecialchars($row['total_left'] ?? '-', ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td><a href='viewitem.php?s=" . urlencode($row['s']) . "' class='btn btn-sm btn-primary'>View</a></td>";
        }
        echo "</tr>";
    }

    echo "
    ". ($period == "daily" && $selectedFrom->format("d") == $filterToday->format("d") ? "<tr>
    <td colspan='2'>Total price - Expenses(today)</td>
    <td>&#8358;" . $calculatedPrice - $totalExpenses . "</td>
    </tr>" : "")."
    </tbody></table></div>";

    // Pagination links (only for daily and weekly)
    if (($period === 'daily' || $period === 'weekly') && $total_pages > 1) {
        $max_links = 5;
        $start = max(1, $page - floor($max_links / 2));
        $end = min($total_pages, $start + $max_links - 1);

        echo "<nav><ul class='pagination justify-content-center'>";
        if ($page > 1) {
            echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "&from=" . urlencode($from) . "&to=" . urlencode($to) . "&period=" . urlencode($period) . ($itemid ? "&itemid=" . urlencode($itemid) : "") . "'>Prev</a></li>";
        }
        for ($i = $start; $i <= $end; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo "<li class='page-item $active'><a class='page-link' href='?page=$i&from=" . urlencode($from) . "&to=" . urlencode($to) . "&period=" . urlencode($period) . ($itemid ? "&itemid=" . urlencode($itemid) : "") . "'>$i</a></li>";
        }
        if ($page < $total_pages) {
            echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "&from=" . urlencode($from) . "&to=" . urlencode($to) . "&period=" . urlencode($period) . ($itemid ? "&itemid=" . urlencode($itemid) : "") . "'>Next</a></li>";
        }
        echo "</ul></nav>";
    }
} else {
    echo "<p class='text-danger mt-4'>No processed sales records found for the selected date range and filters.</p>";
}

if (isset($error)) {
    echo "<p class='text-danger mt-4'>" . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "</p>";
}

mysqli_stmt_close($stmt);
?>

<?php include "footer.php"; ?>