<?php
date_default_timezone_set('Africa/Lagos');
include "header.php";

// Ensure expenses table exists with order_id column
$createTableSql = "
CREATE TABLE IF NOT EXISTS expenses (
    s INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    amount DECIMAL(10,2) NOT NULL,
    order_id VARCHAR(255),
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($con, $createTableSql);

// Add order_id column if it doesn't exist
$checkColumnSql = "SHOW COLUMNS FROM expenses LIKE 'order_id'";
$checkResult = mysqli_query($con, $checkColumnSql);
if (mysqli_num_rows($checkResult) == 0) {
    $addColumnSql = "ALTER TABLE expenses ADD COLUMN order_id VARCHAR(255)";
    mysqli_query($con, $addColumnSql);
}

// Handle add expense
if (isset($_POST['add_expense'])) {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $amount = floatval($_POST['amount']);

    $insertSql = "INSERT INTO expenses (title, description, amount) 
                  VALUES ('$title', '$description', '$amount')";

    if (mysqli_query($con, $insertSql)) {
        header("Location: expenses.php?status=success&message=Expense+added+successfully");
        exit;
    } else {
        header("Location: expenses.php?status=error&message=Error+adding+expense:+" . urlencode(mysqli_error($con)));
        exit;
    }
}

// Handle update expense order_id
if (isset($_POST['update_expense'])) {
    $expense_id = intval($_POST['expense_id']);
    $order_id = isset($_POST['completed_order_id']) && !empty($_POST['completed_order_id']) 
        ? mysqli_real_escape_string($con, $_POST['completed_order_id'])
        : (isset($_POST['order_id']) ? mysqli_real_escape_string($con, $_POST['order_id']) : null);

    if ($order_id) {
        $updateSql = "UPDATE expenses SET order_id = '$order_id' WHERE s = $expense_id";
        if (mysqli_query($con, $updateSql)) {
            header("Location: expenses.php?status=success&message=Expense+linked+to+order+successfully");
            exit;
        } else {
            header("Location: expenses.php?status=error&message=Error+linking+expense:+" . urlencode(mysqli_error($con)));
            exit;
        }
    } else {
        header("Location: expenses.php?status=error&message=Please+select+or+enter+an+order+ID");
        exit;
    }
}

// Handle delete expense
if ($isAdmin && isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $deleteSql = "DELETE FROM expenses WHERE s = $delete_id";
    mysqli_query($con, $deleteSql);
    echo "<script>
            alert('Expense deleted successfully!');
            window.location='expenses.php';
          </script>";
    exit;
}

// Filter by date range or today
$filter_from = isset($_GET['from']) ? $_GET['from'] : '';
$filter_to = isset($_GET['to']) ? $_GET['to'] : '';
$show_completed_orders = isset($_GET['completed_orders']) && $_GET['completed_orders'] === '1' ? 1 : 0;

if (isset($_GET['today']) && $_GET['today'] === '1') {
    $filter_from = date('Y-m-d');
    $filter_to = date('Y-m-d');
}

$filterCondition = '';
$from_sql = '';
$to_sql = '';

if ($filter_from && preg_match('/^\d{4}-\d{2}-\d{2}$/', $filter_from)) {
    $from_sql = $filter_from . ' 00:00:00';
}
if ($filter_to && preg_match('/^\d{4}-\d{2}-\d{2}$/', $filter_to)) {
    $to_sql = $filter_to . ' 23:59:59';
}

if ($show_completed_orders) {
    // Show expenses from completed orders today
    $todayDate = date('Y-m-d');
    $filterCondition = "WHERE e.date_added >= '$todayDate 00:00:00' 
                        AND e.date_added <= '$todayDate 23:59:59' 
                        AND e.order_id IS NOT NULL 
                        AND e.order_id IN (SELECT id FROM saloon_orders WHERE status='completed' AND DATE(date_added) = '$todayDate')";
} elseif ($from_sql && $to_sql) {
    $filterCondition = "WHERE e.date_added BETWEEN '$from_sql' AND '$to_sql'";
} elseif ($from_sql) {
    $filterCondition = "WHERE e.date_added >= '$from_sql'";
} elseif ($to_sql) {
    $filterCondition = "WHERE e.date_added <= '$to_sql'";
}

// Today total amount
$todayStart = date('Y-m-d 00:00:00');
$todayEnd = date('Y-m-d 23:59:59');
$todaySql = "SELECT COALESCE(SUM(amount), 0) AS today_total FROM expenses WHERE date_added BETWEEN '$todayStart' AND '$todayEnd'";
$todayResult = mysqli_query($con, $todaySql);
$todayData = mysqli_fetch_assoc($todayResult);
$today_total = $todayData['today_total'] ?? 0;

// Completed orders from today total
$todayCompletedSql = "SELECT COALESCE(SUM(e.amount), 0) AS completed_today_total 
                      FROM expenses e 
                      WHERE e.date_added >= '$todayStart' 
                      AND e.date_added <= '$todayEnd' 
                      AND e.order_id IS NOT NULL 
                      AND e.order_id IN (SELECT id FROM saloon_orders WHERE status='completed' AND DATE(date_added) = DATE(NOW()))";
$completedResult = mysqli_query($con, $todayCompletedSql);
$completedData = mysqli_fetch_assoc($completedResult);
$completed_today_total = $completedData['completed_today_total'] ?? 0;
?>

<!-- Display status messages -->
<?php
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $message = isset($_GET['message']) ? urldecode($_GET['message']) : '';
    $alertClass = $status === 'success' ? 'alert-success' : 'alert-danger';
    echo "<div class='alert $alertClass alert-dismissible fade show' role='alert'>
            $message
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h5 mb-0 text-gray-800">Expenses</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Expenses</li>
    </ol>
</div>

<div class="col-lg-12">
    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Expenses List</h6>
        </div>
        <div class="card-body">
            <form class="row gx-2 gy-2 align-items-end mb-3" method="get" action="expenses.php">
                <div class="col-auto">
                    <label class="form-label">From</label>
                    <input type="date" class="form-control" name="from"
                        value="<?php echo htmlspecialchars($filter_from); ?>">
                </div>
                <div class="col-auto">
                    <label class="form-label">To</label>
                    <input type="date" class="form-control" name="to"
                        value="<?php echo htmlspecialchars($filter_to); ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
            <div class="table-responsive p-3">
                <table class="table table-bordered" id="expensesTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Order ID</th>
                            <th class="text-end">Amount</th>
                            <?php if ($isAdmin) echo "<th>Action</th>"; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        $sql = "SELECT e.* FROM expenses e $filterCondition ORDER BY e.date_added DESC";
                        $result = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_array($result)) {
                            $total += $row['amount'];
                            $formattedAmount = (intval($row['amount']) == $row['amount'])
                                ? number_format($row['amount'], 0)
                                : number_format($row['amount'], 2);

                            echo "<tr>
                                    <td>" . htmlspecialchars($row['title']) . "</td>
                                    <td>" . htmlspecialchars($row['description']) . "</td>
                                    <td class='text-nowrap'>" . date('Y-m-d H:i', strtotime($row['date_added'])) . "</td>
                                    <td>" . htmlspecialchars($row['order_id'] ?? '-') . "</td>
                                    <td class='text-end'>₦" . $formattedAmount . "</td>";
                            if ($isAdmin) {
                                echo "<td class='text-center'>
                                        <a href='expenses.php?delete_id={$row['s']}' 
                                           onclick=\"return confirm('Are you sure you want to delete this expense?');\" 
                                           class='btn btn-outline-danger btn-sm'>
                                           <i class='fas fa-trash'></i>
                                        </a>
                                      </td>";
                            }
                            echo "</tr>";
                        }
                        $totalFormatted = (intval($total) == $total)
                            ? number_format($total, 0)
                            : number_format($total, 2);
                        $todayTotalFormatted = (intval($today_total) == $today_total)
                            ? number_format($today_total, 0)
                            : number_format($today_total, 2);
                        $completedTodayFormatted = (intval($completed_today_total) == $completed_today_total)
                            ? number_format($completed_today_total, 0)
                            : number_format($completed_today_total, 2);
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <?php if ($isAdmin): ?>
                            <th colspan="5" class="text-end">Total:</th>
                            <th class="text-end">₦<?php echo $totalFormatted; ?></th>
                            <?php else: ?>
                            <th colspan="4" class="text-end">Total:</th>
                            <th class="text-end">₦<?php echo $totalFormatted; ?></th>
                            <?php endif; ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Floating Add Button -->
<button class="btn btn-primary rounded-circle"
    style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; font-size: 24px;"
    data-bs-toggle="modal" data-bs-target="#addExpenseModal">
    <i class="fas fa-plus"></i>
</button>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="expenses.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExpenseModalLabel">Add New Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <select name="title" class="form-control" required>
                            <option value="" disabled selected>Select a title</option>
                            <?php
                            $sql = "SELECT title FROM expense_titles ORDER BY title";
                            $result = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<option value='" . htmlspecialchars($row['title']) . "'>" . htmlspecialchars($row['title']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Amount</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_expense" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Update Expense Modal -->
<div class="modal fade" id="updateExpenseModal" tabindex="-1" aria-labelledby="updateExpenseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="expenses.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateExpenseModalLabel">Link Expense to Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">x</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="expense_id" id="expense_id">
                    <div class="mb-3">
                        <label>Order ID</label>
                        <input type="text" name="order_id" id="order_id" class="form-control"
                            placeholder="Enter Order ID">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Or Select from Completed Orders Today</label>
                        <select name="completed_order_id" id="completed_order_id" class="form-control">
                            <option value="" selected>-- None --</option>
                            <?php
                            $todayDate = date('Y-m-d');
                            $completedOrdersSql = "SELECT id, name FROM saloon_orders WHERE status='completed' AND DATE(date_added) = '$todayDate' ORDER BY date_added DESC";
                            $completedOrdersResult = mysqli_query($con, $completedOrdersSql);
                            while ($completedOrder = mysqli_fetch_array($completedOrdersResult)) {
                                echo "<option value='" . htmlspecialchars($completedOrder['id']) . "'>" . htmlspecialchars($completedOrder['id'] . " - " . $completedOrder['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update_expense" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include "footer.php"; ?>

<script>
function updateExpense(expenseId, currentOrderId) {
    document.getElementById('expense_id').value = expenseId;
    document.getElementById('order_id').value = currentOrderId || '';
    document.getElementById('completed_order_id').value = '';
}
</script>