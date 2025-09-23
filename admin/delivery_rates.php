<?php
include "header.php";

// Create delivery_rates table if it doesn't exist
$createTableSQL = "
CREATE TABLE IF NOT EXISTS delivery_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    km_range VARCHAR(50) NOT NULL UNIQUE,
    rate INT NOT NULL DEFAULT 0
)";
if (!mysqli_query($con, $createTableSQL)) {
    error_log("Failed to create delivery_rates table: " . mysqli_error($con));
    $_SESSION['error_message'] = "Database error: Failed to initialize delivery rates table.";
    header("Location: delivery_rates.php");
    exit;
}

// Initialize default ranges if table is empty
$defaultRanges = [
    'Below 5km' => 0,
    '5 - 10km' => 0,
    '10 - 20km' => 0,
    '20 - 50km' => 0,
    'Above 50km' => 0
];

// Check if table is empty and insert default ranges
$result = mysqli_query($con, "SELECT COUNT(*) as count FROM delivery_rates");
if (!$result) {
    error_log("Failed to check delivery_rates table count: " . mysqli_error($con));
    $_SESSION['error_message'] = "Database error: Unable to verify table data.";
    header("Location: delivery_rates.php");
    exit;
}
$row = mysqli_fetch_assoc($result);
if ($row['count'] == 0) {
    foreach ($defaultRanges as $range => $rate) {
        $range = mysqli_real_escape_string($con, $range);
        $sql = "INSERT INTO delivery_rates (km_range, rate) VALUES ('$range', $rate)";
        if (!mysqli_query($con, $sql)) {
            error_log("Failed to insert default range '$range': " . mysqli_error($con));
            $_SESSION['error_message'] = "Database error: Failed to initialize default ranges.";
            header("Location: delivery_rates.php");
            exit;
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveRates'])) {
    $rates = $_POST['rates'] ?? [];
    $success = true;
    $errorMsg = '';

    // Begin transaction for atomic updates
    mysqli_begin_transaction($con);
    try {
        foreach ($rates as $range => $rate) {
            $rate = (int) $rate; // Ensure integer
            if ($rate < 0) {
                $success = false;
                $errorMsg = "Rates cannot be negative.";
                break;
            }
            $range = mysqli_real_escape_string($con, $range);
            $sql = "UPDATE delivery_rates SET rate = $rate WHERE km_range = '$range'";
            if (!mysqli_query($con, $sql)) {
                $success = false;
                $errorMsg = "Failed to update rate for $range: " . mysqli_error($con);
                error_log("Update failed for range '$range': " . mysqli_error($con));
                break;
            }
        }
        if ($success) {
            mysqli_commit($con);
            $_SESSION['success_message'] = "Delivery rates saved successfully!";
        } else {
            mysqli_rollback($con);
            $_SESSION['error_message'] = $errorMsg;
        }
    } catch (Exception $e) {
        mysqli_rollback($con);
        error_log("Transaction error: " . $e->getMessage());
        $_SESSION['error_message'] = "Server error: Failed to save rates.";
    }
    header("Location: delivery_rates.php");
    exit;
}

// Fetch current rates
$rates = [];
$result = mysqli_query($con, "SELECT km_range, rate FROM delivery_rates ORDER BY id");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rates[$row['km_range']] = $row['rate'];
    }
} else {
    error_log("Failed to fetch delivery rates: " . mysqli_error($con));
    $_SESSION['error_message'] = "Database error: Unable to fetch rates.";
    header("Location: delivery_rates.php");
    exit;
}
?>

<!-- Alert Display -->
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

<!-- Delivery Rates Section -->
<section id="delivery-rates" class="section-bg" style="margin-top:50px; background-color:none; border:none;">
    <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#000;">
            <h3 style="text-decoration:none; color:#000;">DELIVERY RATES<br><span style="font-size:14px;">Set delivery rates for various ranges</span></h3>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="box" data-aos="zoom-in" data-aos-delay="100">
                    <form id="deliveryRatesForm" method="post">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush" id="deliveryRatesTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Kilometer Range</th>
                                        <th>Rate (₦)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ranges = ['Below 5km', '5 - 10km', '10 - 20km', '20 - 50km', 'Above 50km'];
                                    foreach ($ranges as $range) {
                                        $rate = isset($rates[$range]) ? (int) $rates[$range] : 0;
                                        $escapedRange = htmlspecialchars($range, ENT_QUOTES, 'UTF-8');
                                        echo "
                                            <tr>
                                                <td>$escapedRange</td>
                                                <td>
                                                    <input type='number' class='form-control' name='rates[$escapedRange]' value='$rate' min='0' step='1' required>
                                                </td>
                                            </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" name="saveRates" class="btn-buya mt-3">Save Rates</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
#deliveryRatesTable input[type="number"] {
    width: 120px;
}
</style>

<?php include "footer.php"; ?>