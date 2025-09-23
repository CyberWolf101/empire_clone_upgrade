<?php
include "header.php";


// Create bank_accounts table if it doesn't exist
$createTableSql = "
CREATE TABLE IF NOT EXISTS bank_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bank_name VARCHAR(100) NOT NULL,
    account_name VARCHAR(100) NOT NULL,
    account_number VARCHAR(20) NOT NULL,
    username VARCHAR(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($con, $createTableSql) or die('Could not create table: ' . mysqli_error($con));

// Check if username column exists and add it if not
$checkColumnSql = "SHOW COLUMNS FROM bank_accounts LIKE 'username'";
$result = mysqli_query($con, $checkColumnSql);
if (mysqli_num_rows($result) == 0) {
    $alterTableSql = "ALTER TABLE bank_accounts ADD username VARCHAR(100) NOT NULL";
    mysqli_query($con, $alterTableSql) or die('Could not add username column: ' . mysqli_error($con));
}

// Handle add bank account submission
if (isset($_POST['add_bank'])) {
    $bank_name = mysqli_real_escape_string($con, $_POST['bank_name'] ?? '');
    $account_name = mysqli_real_escape_string($con, $_POST['account_name'] ?? '');
    $account_number = mysqli_real_escape_string($con, $_POST['account_number'] ?? '');
    $admin_username = mysqli_real_escape_string($con, $username);

    if (empty($bank_name) || empty($account_name) || empty($account_number)) {
        echo "<script>alert('All fields are required!'); window.location='admin_bank_accounts.php';</script>";
        exit;
    }
    if (!preg_match('/^[0-9]{10,20}$/', $account_number)) {
        echo "<script>alert('Account number must be 10-20 digits!'); window.location='admin_bank_accounts.php';</script>";
        exit;
    }

    $insertSql = "INSERT INTO bank_accounts (bank_name, account_name, account_number, username) 
                  VALUES ('$bank_name', '$account_name', '$account_number', '$admin_username')";
    if (mysqli_query($con, $insertSql)) {
        echo "<script>alert('Bank account added successfully!'); window.location='admin_bank_accounts.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error adding bank account: " . mysqli_error($con) . "'); window.location='admin_bank_accounts.php';</script>";
        exit;
    }
}

// Handle edit bank account submission
if (isset($_POST['edit_bank'])) {
    $bank_id = intval($_POST['bank_id'] ?? 0);
    $bank_name = mysqli_real_escape_string($con, $_POST['bank_name'] ?? '');
    $account_name = mysqli_real_escape_string($con, $_POST['account_name'] ?? '');
    $account_number = mysqli_real_escape_string($con, $_POST['account_number'] ?? '');
    $admin_username = mysqli_real_escape_string($con, $username);

    if (empty($bank_name) || empty($account_name) || empty($account_number)) {
        echo "<script>alert('All fields are required!'); window.location='admin_bank_accounts.php';</script>";
        exit;
    }
    if (!preg_match('/^[0-9]{10,20}$/', $account_number)) {
        echo "<script>alert('Account number must be 10-20 digits!'); window.location='admin_bank_accounts.php';</script>";
        exit;
    }

    $updateSql = "UPDATE bank_accounts SET bank_name='$bank_name', account_name='$account_name', account_number='$account_number', username='$admin_username' WHERE id='$bank_id'";
    if (mysqli_query($con, $updateSql)) {
        echo "<script>alert('Bank account updated successfully!'); window.location='admin_bank_accounts.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error updating bank account: " . mysqli_error($con) . "'); window.location='admin_bank_accounts.php';</script>";
        exit;
    }
}

// Handle delete action
if ($isAdmin && isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $deleteSql = "DELETE FROM bank_accounts WHERE id = $delete_id";
    if (mysqli_query($con, $deleteSql)) {
        echo "<script>alert('Bank account deleted successfully!'); window.location='admin_bank_accounts.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error deleting bank account: " . mysqli_error($con) . "'); window.location='admin_bank_accounts.php';</script>";
        exit;
    }
}

// Fetch all bank accounts
$bank_accounts = [];
$sql = "SELECT * FROM bank_accounts ORDER BY created_at DESC";
$result = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($result)) {
    $bank_accounts[] = $row;
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h5 mb-0 text-gray-800">Bank Accounts</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Bank Accounts</li>
    </ol>
</div>

<div class="col-lg-12">
    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bank Accounts List</h6>
        </div>
        <div class="p-3" style="overflow:scroll">
            <table class="table table-bordered" id="bankAccountsTable">
                <thead class="thead-light">
                    <tr>
                        <th>Bank Name</th>
                        <th>Account Name</th>
                        <th>Account Number</th>
                        <th>Added By</th>
                        <th>Added On</th>
                        <?php if ($isAdmin) echo "<th>Action</th>"; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bank_accounts)) { ?>
                        <tr>
                            <td colspan="<?php echo $isAdmin ? '6' : '5'; ?>" class="text-center">No bank accounts added yet.</td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($bank_accounts as $account) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($account['bank_name']); ?></td>
                                <td><?php echo htmlspecialchars($account['account_name']); ?></td>
                                <td><?php echo htmlspecialchars($account['account_number']); ?></td>
                                <td><?php echo htmlspecialchars($account['username']); ?></td>
                                <td><?php echo $account['created_at']; ?></td>
                                <?php if ($isAdmin) { ?>
                                    <td class="text-center">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editBankModal<?php echo $account['id']; ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                           <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="admin_bank_accounts.php?delete_id=<?php echo $account['id']; ?>" 
                                           onclick="return confirm('Are you sure you want to delete this bank account?');" 
                                           class="btn btn-outline-danger btn-sm">
                                           <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                <?php } ?>
                            </tr>
                            <!-- Edit Bank Account Modal -->
                            <div class="modal fade" id="editBankModal<?php echo $account['id']; ?>" tabindex="-1" aria-labelledby="editBankModalLabel<?php echo $account['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="post" action="admin_bank_accounts.php">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editBankModalLabel<?php echo $account['id']; ?>">Edit Bank Account</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="bank_id" value="<?php echo $account['id']; ?>">
                                                <div class="mb-3">
                                                    <label>Bank Name</label>
                                                    <input type="text" name="bank_name" class="form-control" value="<?php echo htmlspecialchars($account['bank_name']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Account Name</label>
                                                    <input type="text" name="account_name" class="form-control" value="<?php echo htmlspecialchars($account['account_name']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Account Number</label>
                                                    <input type="text" name="account_number" class="form-control" value="<?php echo htmlspecialchars($account['account_number']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="edit_bank" class="btn btn-primary">Save</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Floating Add Button -->
<button class="btn btn-primary rounded-circle"
        style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; font-size: 24px;"
        data-bs-toggle="modal" data-bs-target="#addBankModal">
    <i class="fas fa-plus"></i>
</button>

<!-- Add Bank Account Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1" aria-labelledby="addBankModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="admin_bank_accounts.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBankModalLabel">Add New Bank Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Account Name</label>
                        <input type="text" name="account_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Account Number</label>
                        <input type="text" name="account_number" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_bank" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include "footer.php"; ?>