<?php
date_default_timezone_set('Africa/Lagos');
include "header.php";

// Create expense_titles table if it doesn't exist
$createTableSql = "
CREATE TABLE IF NOT EXISTS expense_titles (
    s INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL UNIQUE,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($con, $createTableSql);

// Handle add title
if (isset($_POST['add_title'])) {
    $title = mysqli_real_escape_string($con, trim($_POST['title']));
    if (!empty($title)) {
        $insertSql = "INSERT INTO expense_titles (title) VALUES ('$title')";
        if (mysqli_query($con, $insertSql)) {
            header("Location: expense_title.php?status=success&message=Title+added+successfully");
            exit;
        } else {
            header("Location: expense_title.php?status=error&message=Error+adding+title:+" . urlencode(mysqli_error($con)));
            exit;
        }
    } else {
        header("Location: expense_title.php?status=error&message=Title+cannot+be+empty");
        exit;
    }
}

// Handle edit title
if ($isAdmin && isset($_POST['edit_title'])) {
    $title_id = intval($_POST['title_id']);
    $title = mysqli_real_escape_string($con, trim($_POST['title']));
    if (!empty($title)) {
        $updateSql = "UPDATE expense_titles SET title = '$title' WHERE s = $title_id";
        if (mysqli_query($con, $updateSql)) {
            header("Location: expense_title.php?status=success&message=Title+updated+successfully");
            exit;
        } else {
            header("Location: expense_title.php?status=error&message=Error+updating+title:+" . urlencode(mysqli_error($con)));
            exit;
        }
    } else {
        header("Location: expense_title.php?status=error&message=Title+cannot+be+empty");
        exit;
    }
}

// Handle delete title
if ($isAdmin && isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $deleteSql = "DELETE FROM expense_titles WHERE s = $delete_id";
    mysqli_query($con, $deleteSql);
    header("Location: expense_title.php?status=success&message=Title+deleted+successfully");
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
    <h1 class="h5 mb-0 text-gray-800">Expense Titles</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Expense Titles</li>
    </ol>
</div>

<div class="col-lg-12">
    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Expense Titles List</h6>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-bordered" id="expenseTitlesTable">
                <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>Date Added</th>
                        <?php if ($isAdmin) echo "<th>Action</th>"; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT s, title, date_added FROM expense_titles ORDER BY date_added DESC";
                    $result = mysqli_query($con, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['title']) . "</td>
                                    <td>" . htmlspecialchars($row['date_added']) . "</td>";
                            if ($isAdmin) {
                                echo "<td class='text-center'>
                                        <a href='#' 
                                           class='btn btn-outline-primary btn-sm me-2'
                                           data-bs-toggle='modal' 
                                           data-bs-target='#editTitleModal'
                                           data-id='{$row['s']}'
                                           data-title='" . htmlspecialchars($row['title']) . "'>
                                           <i class='fas fa-edit'></i>
                                        </a>
                                        <a href='expense_title.php?delete_id={$row['s']}' 
                                           onclick=\"return confirm('Are you sure you want to delete this title?');\" 
                                           class='btn btn-outline-danger btn-sm'>
                                           <i class='fas fa-trash'></i>
                                        </a>
                                      </td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='" . ($isAdmin ? 3 : 2) . "' class='text-center'>No titles found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Floating Add Button -->
<button class="btn btn-primary rounded-circle"
        style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; font-size: 24px;"
        data-bs-toggle="modal" data-bs-target="#addTitleModal">
    <i class="fas fa-plus"></i>
</button>

<!-- Add Title Modal -->
<div class="modal fade" id="addTitleModal" tabindex="-1" aria-labelledby="addTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="expense_title.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTitleModalLabel">Add New Expense Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_title" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Title Modal -->
<div class="modal fade" id="editTitleModal" tabindex="-1" aria-labelledby="editTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="expense_title.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTitleModalLabel">Edit Expense Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" id="editTitleInput" class="form-control" required>
                        <input type="hidden" name="title_id" id="editTitleId">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_title" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript to handle edit modal population -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var editModal = document.getElementById('editTitleModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var title = button.getAttribute('data-title');
        
        var modalTitleInput = editModal.querySelector('#editTitleInput');
        var modalIdInput = editModal.querySelector('#editTitleId');
        
        modalTitleInput.value = title;
        modalIdInput.value = id;
    });
});
</script>

<?php include "footer.php"; ?>