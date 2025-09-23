<?php include "header.php"; ?>

<?php
// Default values
$default_section = "all";
$default_from = date("Y-m-01"); // first day of current month
$default_to = date("Y-m-d");  // today

// Use POST values if form submitted, otherwise defaults
$section = $_POST['section'] ?? $default_section;
$from = $_POST['from'] ?? $default_from;
$to = $_POST['to'] ?? $default_to;
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h5 mb-0 text-gray-800">Sales Report</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Reports</li>
  </ol>
</div>

<div class="col-xl-12 col-lg-12 mb-4">
  <center>
    <div style="text-align:left;">
      <p>View sales made daily in each department of chbluxuryempire. Enter the custom duration and proceed.</p>
      <form action="" method="post" id="report-form">
        <p>
          <select class="form-control" name="section" required>
            <option value="">- Select Department -</option>
            <option value="all" <?= ($section == "all" ? "selected" : "") ?>>All Departments</option>
            <option value="saloon" <?= ($section == "saloon" ? "selected" : "") ?>>Saloon</option>
            <option value="orishirishi" <?= ($section == "orishirishi" ? "selected" : "") ?>>Orishirishi</option>
            <option value="kitchen" <?= ($section == "kitchen" ? "selected" : "") ?>>Delta Kitchen</option>
            <option value="academy" <?= ($section == "academy" ? "selected" : "") ?>>Academy</option>
            <option value="members" <?= ($section == "members" ? "selected" : "") ?>>Membership</option>
            <option value="giftcard" <?= ($section == "giftcard" ? "selected" : "") ?>>Giftcard</option>
            <option value="voucher" <?= ($section == "voucher" ? "selected" : "") ?>>Vouchers</option>
            <option value="rental" <?= ($section == "rental" ? "selected" : "") ?>>Rental</option>
          </select>
        </p>
        <p><label>From</label><input type="date" class="form-control" name="from" value="<?= $from ?>" required /></p>
        <p><label>To</label><input type="date" class="form-control" name="to" value="<?= $to ?>" required /></p>
        <p><button type="submit" class="btn btn-sm btn-primary shadow-sm">Generate Report</button></p>
      </form>
    </div>
  </center>
</div>




<?php
// Build SQL only when section is not empty
if (!empty($section)) {

    // Pagination settings
    $limit = 20; // rows per page
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    if ($page < 1) $page = 1;

    $sql_query = null;
    $count_query = null; // For total rows

    // Helper: wrap SUM with IFNULL
    $sum_or_zero = function($field) {
        return "IFNULL(SUM($field),0)";
    };

    // Build SQL query per section
    if ($section == "saloon") {
        $sql_query = "SELECT DATE(`date`) AS order_date, COUNT(*) AS order_count, {$sum_or_zero('total_amount')} AS order_amount_count
                      FROM saloon_orders
                      WHERE `date` BETWEEN '$from' AND '$to'
                      AND (pay_status = 'paid' OR pay_status IS NULL)
                      AND section='spa'
                      GROUP BY order_date
                      ORDER BY order_date ASC";

        $count_query = "SELECT COUNT(DISTINCT DATE(`date`)) AS total_rows 
                        FROM saloon_orders
                        WHERE `date` BETWEEN '$from' AND '$to'
                        AND (pay_status = 'paid' OR pay_status IS NULL)
                        AND section='spa'";
    } 
    elseif ($section == "orishirishi") {
        $sql_query = "SELECT DATE(`date`) AS order_date, COUNT(*) AS order_count, {$sum_or_zero('total_amount')} AS order_amount_count
                      FROM saloon_orders
                      WHERE `date` BETWEEN '$from' AND '$to'
                      AND (pay_status = 'paid' OR pay_status IS NULL)
                      AND section='refreshments'
                      GROUP BY order_date
                      ORDER BY order_date ASC";

        $count_query = "SELECT COUNT(DISTINCT DATE(`date`)) AS total_rows 
                        FROM saloon_orders
                        WHERE `date` BETWEEN '$from' AND '$to'
                        AND (pay_status = 'paid' OR pay_status IS NULL)
                        AND section='refreshments'";
    } 
    elseif ($section == "kitchen") {
        $sql_query = "SELECT DATE(`date`) AS order_date, COUNT(*) AS order_count, {$sum_or_zero('total_amount')} AS order_amount_count
                      FROM saloon_orders
                      WHERE `date` BETWEEN '$from' AND '$to'
                      AND (pay_status = 'paid' OR pay_status IS NULL)
                      AND section='kitchen'
                      GROUP BY order_date
                      ORDER BY order_date ASC";

        $count_query = "SELECT COUNT(DISTINCT DATE(`date`)) AS total_rows 
                        FROM saloon_orders
                        WHERE `date` BETWEEN '$from' AND '$to'
                        AND (pay_status = 'paid' OR pay_status IS NULL)
                        AND section='kitchen'";
    } 
    elseif ($section == "academy") {
        $sql_query = "SELECT DATE(`date`) AS order_date, COUNT(*) AS order_count, {$sum_or_zero('total_amount')} AS order_amount_count
                      FROM saloon_orders
                      WHERE `date` BETWEEN '$from' AND '$to'
                      AND (pay_status = 'paid' OR pay_status IS NULL)
                      AND section='academy'
                      GROUP BY order_date
                      ORDER BY order_date ASC";

        $count_query = "SELECT COUNT(DISTINCT DATE(`date`)) AS total_rows 
                        FROM saloon_orders
                        WHERE `date` BETWEEN '$from' AND '$to'
                        AND (pay_status = 'paid' OR pay_status IS NULL)
                        AND section='academy'";
    } 
    elseif ($section == "members") {
        $sql_query = "SELECT DATE(start_date) AS order_date, COUNT(*) AS order_count, {$sum_or_zero('total_amount')} AS order_amount_count
                      FROM members
                      WHERE `start_date` BETWEEN '$from' AND '$to'
                      GROUP BY order_date
                      ORDER BY order_date ASC";

        $count_query = "SELECT COUNT(DISTINCT DATE(start_date)) AS total_rows 
                        FROM members
                        WHERE `start_date` BETWEEN '$from' AND '$to'";
    } 
    elseif ($section == "giftcard") {
        $sql_query = "SELECT DATE(`date`) AS order_date, COUNT(*) AS order_count, {$sum_or_zero('amount')} AS order_amount_count
                      FROM giftcard
                      WHERE `date` BETWEEN '$from' AND '$to'
                      AND status='paid'
                      GROUP BY order_date
                      ORDER BY order_date ASC";

        $count_query = "SELECT COUNT(DISTINCT DATE(`date`)) AS total_rows
                        FROM giftcard
                        WHERE `date` BETWEEN '$from' AND '$to'
                        AND status='paid'";
    } 
    elseif ($section == "voucher") {
        $sql_query = "SELECT DATE(`date`) AS order_date, COUNT(*) AS order_count, {$sum_or_zero('total_amount')} AS order_amount_count
                      FROM voucher_orders
                      WHERE `date` BETWEEN '$from' AND '$to'
                      GROUP BY order_date
                      ORDER BY order_date ASC";

        $count_query = "SELECT COUNT(DISTINCT DATE(`date`)) AS total_rows
                        FROM voucher_orders
                        WHERE `date` BETWEEN '$from' AND '$to'";
    } 
    elseif ($section == "rental") {
        $sql_query = "SELECT DATE(`date`) AS order_date, COUNT(*) AS order_count, {$sum_or_zero('total_amount')} AS order_amount_count
                      FROM rentals
                      WHERE `date` BETWEEN '$from' AND '$to'
                      GROUP BY order_date
                      ORDER BY order_date ASC";

        $count_query = "SELECT COUNT(DISTINCT DATE(`date`)) AS total_rows
                        FROM rentals
                        WHERE `date` BETWEEN '$from' AND '$to'";
    } 
    elseif ($section == "all") {
        $sql_query = "
            SELECT * FROM (
                SELECT DATE(`date`) AS order_date, COUNT(*) AS order_count, IFNULL(SUM(total_amount),0) AS order_amount_count
                FROM saloon_orders
                WHERE `date` BETWEEN '$from' AND '$to'
                AND (pay_status='paid' OR pay_status IS NULL)
                AND section IN ('spa','refreshments','kitchen','academy')
                GROUP BY order_date
                UNION ALL
                SELECT DATE(start_date) AS order_date, COUNT(*) AS order_count, IFNULL(SUM(total_amount),0) AS order_amount_count
                FROM members
                WHERE start_date BETWEEN '$from' AND '$to'
                GROUP BY order_date
                UNION ALL
                SELECT DATE(`date`) AS order_date, COUNT(*) AS order_count, IFNULL(SUM(amount),0) AS order_amount_count
                FROM giftcard
                WHERE `date` BETWEEN '$from' AND '$to' AND status='paid'
                GROUP BY order_date
                UNION ALL
                SELECT DATE(`date`) AS order_date, COUNT(*) AS order_count, IFNULL(SUM(total_amount),0) AS order_amount_count
                FROM voucher_orders
                WHERE `date` BETWEEN '$from' AND '$to'
                GROUP BY order_date
                UNION ALL
                SELECT DATE(`date`) AS order_date, COUNT(*) AS order_count, IFNULL(SUM(total_amount),0) AS order_amount_count
                FROM rentals
                WHERE `date` BETWEEN '$from' AND '$to'
                GROUP BY order_date
            ) AS combined
            ORDER BY order_date ASC
        ";
        $count_query = "
            SELECT COUNT(*) AS total_rows FROM (
                SELECT DATE(`date`) AS order_date FROM saloon_orders
                WHERE `date` BETWEEN '$from' AND '$to'
                AND (pay_status='paid' OR pay_status IS NULL)
                AND section IN ('spa','refreshments','kitchen','academy')
                UNION ALL
                SELECT DATE(start_date) AS order_date FROM members
                WHERE start_date BETWEEN '$from' AND '$to'
                UNION ALL
                SELECT DATE(`date`) AS order_date FROM giftcard
                WHERE `date` BETWEEN '$from' AND '$to' AND status='paid'
                UNION ALL
                SELECT DATE(`date`) AS order_date FROM voucher_orders
                WHERE `date` BETWEEN '$from' AND '$to'
                UNION ALL
                SELECT DATE(`date`) AS order_date FROM rentals
                WHERE `date` BETWEEN '$from' AND '$to'
            ) AS combined_count
        ";
    }

    // Execute count query first
    $count_result = mysqli_query($con, $count_query) or die("Database error: " . mysqli_error($con));
    $total_rows = mysqli_fetch_assoc($count_result)['total_rows'];
    $total_pages = ceil($total_rows / $limit);
    if ($page > $total_pages) $page = $total_pages;
    $offset = ($page - 1) * $limit;

    // Add LIMIT/OFFSET to main query
    if ($section != "all") {
        $sql_query .= " LIMIT $limit OFFSET $offset";
    } else {
        $sql_query .= " LIMIT $limit OFFSET $offset";
    }

    // Execute main query
    $resultset = mysqli_query($con, $sql_query) or die("Database error: " . mysqli_error($con));

    if (mysqli_num_rows($resultset) > 0) {
        echo "<div class='table-responsive mt-4'>";
        echo "<table class='table table-bordered'>";
        echo "<thead><tr>
                <th>Date</th>
                <th>Total Orders</th>
                <th>Total Amount</th>
              </tr></thead><tbody>";

        while ($row = mysqli_fetch_assoc($resultset)) {
            echo "<tr>
                    <td>" . $row['order_date'] . "</td>
                    <td>" . $row['order_count'] . "</td>
                    <td>₦" . $row['order_amount_count'] . "</td>
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
    } else {
        echo "<p class='text-danger mt-4'>No records found for the selected period.</p>";
    }
}
?>






<?php include "footer.php"; ?>