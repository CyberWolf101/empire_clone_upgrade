<?php include "header.php";

// Delete
if (isset($_GET['categoryid'])) {
    $service_delete = mysqli_real_escape_string($con, $_GET['categoryid']);
    // Check if the user is not a superadmin before deleting
    $check_status = mysqli_query($con, "SELECT status, name FROM admin WHERE s='$service_delete'");
    $row = mysqli_fetch_assoc($check_status);
    if ($row && $row['status'] !== 'superadmin') {
        $del = mysqli_query($con, "DELETE FROM admin WHERE s='$service_delete'") or die('Could not connect: ' . mysqli_error($con));
        $_SESSION['success_message'] = "Staff " . htmlspecialchars($row['name']) . " deleted successfully!";
        header("Location: managers.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Cannot delete a superadmin!";
        header("Location: managers.php");
        exit();
    }
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Managers</h1> 
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Staff</li>
    </ol>
</div>

<?php
if (!empty($_SESSION['success_message'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
    unset($_SESSION['error_message']);
}
?>

<!-- Row -->
<div class="row">
    <!-- Datatables -->
    <div class="col-lg-12" style="margin-top:2%;">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"></h6>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-items-center table-flush text-primary" id="dataTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM admin ORDER BY s ASC";
                        $sql2 = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_array($sql2)) {
                            $categories = [$row['sections']];
                            echo "
                                <tr>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td>" . htmlspecialchars($row['email']) . "</td>
                                    <td>" . htmlspecialchars(implode(', ', $categories)) . "</td>
                                    <td>" . htmlspecialchars($row['status']) . "</td>
                                    <td>
                                        <form action='editstaff.php' method='get'>
                                            <input type='hidden' name='category' value='" . htmlspecialchars($row['s']) . "'>  
                                            <input type='submit' name='edit' value='Edit Details' class='btn btn-sm btn-primary'>
                                        </form>
                                    </td>
                                    <td>" . ($row['status'] !== 'superadmin' ? "
                                        <form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this staff (" . htmlspecialchars($row['name']) . ")?\");'>
                                            <input type='hidden' name='categoryid' value='" . htmlspecialchars($row['s']) . "'>  
                                            <input type='submit' name='delete' value='Delete Staff' class='btn btn-sm btn-danger'>
                                        </form>" : "") . "</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>