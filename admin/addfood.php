<?php include "header.php"; ?>

<?php
// Display success or error messages
if (isset($_GET['success'])) {
    echo '<div class="alert alert-success text-center">' . htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8') . '</div>';
}
if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger text-center">' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') . '</div>';
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Add New Orishirishi</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">New Orishirishi</li>
    </ol>
</div>

<!-- Row -->
<div class="row">          
    <div align="center" class="col-lg-12">
        <?php include "add_food.php"; ?>
        <div class="arizona">
            <form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:center;">
                <input type="text" class="form-control" name="name" placeholder="*Name" required /><br />
                <input type="number" class="form-control" name="price" placeholder="*Price" required /><br />
                <input type="number" class="form-control" name="quantity" placeholder="*Quantity in stock" required /><br />
                <p>
                    <select class="form-control" name="type" required>
                        <option selected="selected" value="">- Select Category -</option>
                        <?php 
                        $sql = "SELECT name FROM food_categories";
                        $stmt = mysqli_prepare($con, $sql);
                        if (mysqli_stmt_execute($stmt)) {
                            $sql2 = mysqli_stmt_get_result($stmt);
                            while ($row = mysqli_fetch_array($sql2)) {
                                echo '<option value="' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</option>';
                            }
                            mysqli_free_result($sql2);
                        }
                        mysqli_stmt_close($stmt);
                        ?>
                    </select>
                </p>
                <p><input type="file" name="file" class="form-control" id="customFile"></p>
                <input type='submit' name='add' value='Register' class='btn btn-primary'>
            </form>	
        </div>
    </div>
</div>

<?php 
include "footer.php";
ob_end_flush();
?>