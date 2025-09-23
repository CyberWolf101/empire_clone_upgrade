<?php
date_default_timezone_set('Africa/Lagos');
include "header.php";

// Ensure expenses table exists
$createTableSql = "
CREATE TABLE IF NOT EXISTS expenses (
    s INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    amount DECIMAL(10,2) NOT NULL,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($con, $createTableSql);

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
        <div class="table-responsive p-3">
            <table class="table table-bordered" id="expensesTable">
                <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th class="text-end">Amount</th>
                        <?php if ($isAdmin) echo "<th>Action</th>"; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    $sql = "SELECT * FROM expenses ORDER BY date_added DESC";
                    $result = mysqli_query($con, $sql);
                    while ($row = mysqli_fetch_array($result)) {
                        $total += $row['amount'];
                        $formattedAmount = (intval($row['amount']) == $row['amount'])
                            ? number_format($row['amount'], 0)
                            : number_format($row['amount'], 2);

                        echo "<tr>
                                <td>" . htmlspecialchars($row['title']) . "</td>
                                <td>" . htmlspecialchars($row['description']) . "</td>
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
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <?php if ($isAdmin): ?>
                            <th colspan="3" class="text-end">Total:</th>
                            <th class="text-end">₦<?php echo $totalFormatted; ?></th>
                        <?php else: ?>
                            <th colspan="2" class="text-end">Total:</th>
                            <th class="text-end">₦<?php echo $totalFormatted; ?></th>
                        <?php endif; ?>
                    </tr>
                </tfoot>
            </table>
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

<?php include "footer.php"; ?>