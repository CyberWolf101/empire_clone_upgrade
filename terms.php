<?php
include "header.php"; // Include your site header

// Fetch Terms & Conditions
$sql = "SELECT terms FROM site_settings LIMIT 1";
$result = mysqli_query($con, $sql);

$terms = '';
if ($row = mysqli_fetch_assoc($result)) {
    $terms = $row['terms'] ?? '';
}
?>

<div class="container my-5">
    <div class="card shadow-sm p-4">
        <h2 class="mb-3">Terms & Conditions</h2>
        <hr>
        <?php 
        if (!empty($terms)) {
            // Display formatted terms safely
            echo nl2br(htmlspecialchars($terms));
        } else {
            echo "<p>No terms and conditions have been set yet.</p>";
        }
        ?>
    </div>
</div>

<?php include "footer.php"; ?>