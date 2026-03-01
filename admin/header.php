<?php
ob_start();
session_save_path("/tmp");
session_start();

// ENABLE ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "../connect.php";

// Set PHP timezone
date_default_timezone_set('Africa/Lagos');

// Set MySQL session timezone
mysqli_query($con, "SET time_zone = '+01:00'") or die("Cannot set timezone: " . mysqli_error($con));

// --- SAFE LOGIN CHECK ---
$code = $_SESSION['adminid'] ?? ($_COOKIE['adminID'] ?? null);

if (!$code) {
  // No login found, redirect
  $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
  header("Location: index.php");
  exit;
}

// --- SITE SETTINGS ---
$sql = "SELECT * FROM site_settings";
$sql2 = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($sql2)) {
  $sitemail = $row["sitemail"];
  $sitename = $row["sitename"];
}

// --- CHECK ADMIN ---
$check = "SELECT * FROM admin WHERE email = '$code' ";
$query = mysqli_query($con, $check);
$username = '';

if (mysqli_num_rows($query) == 0) {
  $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
  header("Location: index.php");
  exit;
} else {
  $sql = "SELECT * FROM admin WHERE email = '$code' ";
  $sql2 = mysqli_query($con, $sql);
  while ($row = mysqli_fetch_array($sql2)) {
    $id = $row["s"] ?? null;
    $name = $row['name'] ?? '';
    $email = $row['email'] ?? '';
    $status = $row['status'] ?? '';
    $pass = $row['password'] ?? '';
    $admin_media = $row['picture'] ?? 'default.png'; // safe default
    $staff_office = $row['office'] ?? 'N/A';        // safe default
    $admincategorie = $row['sections'] ?? '';
    $username = $name;

  }
}

$admincategories = explode(",", $admincategorie);

// --- TOTAL PAYMENTS ---
$query = "
SELECT 'saloon_orders' AS source, COALESCE(SUM(total_amount), 0) AS total_paid_amount FROM saloon_orders WHERE pay_status = 'paid'
UNION ALL
SELECT 'voucher_orders' AS source, COALESCE(SUM(total_amount), 0) AS total_paid_amount FROM voucher_orders WHERE pay_status = 'paid'
UNION ALL
SELECT 'giftcard' AS source, COALESCE(SUM(amount), 0) AS total_paid_amount FROM giftcard WHERE status = 'paid'
UNION ALL
SELECT 'rentals' AS source, COALESCE(SUM(total_amount), 0) AS total_paid_amount FROM rentals WHERE paystatus = 'paid'
UNION ALL
SELECT 'members' AS source, COALESCE(SUM(total_amount), 0) AS total_paid_amount FROM members WHERE paystatus = 'paid'
";

$result = mysqli_query($con, $query);
$totalPaidAmounts = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $totalPaidAmounts[$row['source']] = $row['total_paid_amount'];
  }
}

// Grand total
$grandTotal = array_sum($totalPaidAmounts);

// --- KIT PRICE ---
$sql = "SELECT * FROM sitesettings"; // Corrected table name
$sql2 = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($sql2)) {
  $kit_price = $row["pedicurekit"] ?? 0; // safe default
}

// --- TOTAL COUNTS ---
$extrac = mysqli_query($con, "SELECT * FROM saloon_orders WHERE section='spa' AND pay_status='paid'");
$count_services = mysqli_num_rows($extrac);

$extrac = mysqli_query($con, "SELECT * FROM repair_center WHERE status='pending'");
$count_repairs = mysqli_num_rows($extrac);

$extrac = mysqli_query($con, "SELECT * FROM members WHERE paystatus='paid'");
$count_mem = mysqli_num_rows($extrac);
// $isAdmin = isset($_SESSION['user']) && $_SESSION['user'] === 'superadmin';
$isAdmin = $status == "superadmin" || $status == "subadmin";
$isSuperAdmin = $status === "superadmin";


$pending_transfers = 0;

$stmt = $con->prepare("SELECT COUNT(*) AS pending_count FROM bank_transfers");
if (!$stmt) {
  error_log("Failed to prepare statement in header.php: " . mysqli_error($con));
  $pending_transfers = 0;
} else {
  $stmt->execute();
  $result = $stmt->get_result();
  $pending_transfers = $result->fetch_assoc()['pending_count'];
  $stmt->close();
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="favicon.png" rel="icon">
  <title>Control - CHBLUXURYEMPIRE</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css">
  <!-- Bootstrap 5 JS Bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- include the jQuery library -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.js"></script>

</head>

<body id="page-top">
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon">
          <img src="favicon.png">
        </div>
        <div class="sidebar-brand-text mx-3" style="text-transform:uppercase;"><?php echo $status; ?></div>
      </a>
      <?php if ($status == "superadmin" || $status == "subadmin") { ?>
        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
          <a class="nav-link" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
        </li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
          Other Features
        </div>
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSaloon" aria-expanded="true"
            aria-controls="collapseBootstrap">
            <i class="fas fa-fw fa-home"></i>
            <span>Saloon and Spa</span>
          </a>
          <div id="collapseSaloon" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Saloon and Spa</h6>
              <a class="collapse-item" href="categories.php">Categories</a>
              <a class="collapse-item" href="addsubcategory.php">Add New Subcategory</a>
              <a class="collapse-item" href="services.php">All Services</a>
              <a class="collapse-item" href="storebookings.php">Walk-in Bookings</a>
              <a class="collapse-item" href="onlinebookings.php">Online Bookings</a>
              <a class="collapse-item" href="bookings.php">Start An Appointment</a>
            </div>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSaloons"
            aria-expanded="true" aria-controls="collapseBootstrap">
            <i class="fas fa-fw fa-utensils"></i>
            <span>Orishirishi</span>
          </a>
          <div id="collapseSaloons" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Orishirishi</h6>
              <a class="collapse-item" href="foodmenu.php">All Orishirishi</a>
              <a class="collapse-item" href="addfood.php">Add New Orishirishi</a>
              <a class="collapse-item" href="storeorders.php">Walk-in Orders</a>
              <a class="collapse-item" href="onlineorders.php">Online Orders</a>
              <a class="collapse-item" href="viewpreorders.php">Pre-Orders</a>
              <a class="collapse-item" href="startorder.php">Start Transaction</a>
              <a class="collapse-item" href="pending_event_orders.php">Event orders</a>
              <a class="collapse-item" href="itemsreport.php">Stocks Report</a>
              <a class="collapse-item" href="customers.php">Customers</a>
            </div>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDelta" aria-expanded="true"
            aria-controls="collapseBootstrap">
            <i class="fas fa-fw fa-store"></i>
            <span>Delta Kitchen</span>
          </a>
          <div id="collapseDelta" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Delta Kitcheni</h6>
              <a class="collapse-item" href="delta_subcategory.php">Subcategories</a>
              <a class="collapse-item" href="delta_meals.php">Additional Categories</a>
              <a class="collapse-item" href="meals.php">Additional Meals</a>
              <a class="collapse-item" href="delta_protein.php">Proteins</a>
              <a class="collapse-item" href="deltaorders.php">All Orders</a>
              <!--- <a class="collapse-item" href="startorder.php">Start Transaction</a> --->
          </div>
        </div>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRepair" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-fw fa-tools"></i>
          <span>Repair Center</span>
        </a>
        <div id="collapseRepair" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Repair Center</h6>
            <a class="collapse-item" href="repaircenter.php">All Requests</a>
            <a class="collapse-item" href="repairhistory.php">Repairs History</a>
            <a class="collapse-item" href="startrepair.php">Submit Request</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAca" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-fw fa-graduation-cap"></i>
          <span>Academy</span>
        </a>
        <div id="collapseAca" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">CHB LUXURY ACADEMY</h6>
            <a class="collapse-item" href="training.php">Trainings</a>
            <a class="collapse-item" href="duration.php">Durations</a>
            <a class="collapse-item" href="academybooking.php">Bookings</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMember" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-fw fa-credit-card"></i>
          <span>Membership</span>
        </a>
        <div id="collapseMember" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Membership</h6>
            <a class="collapse-item" href="packages.php">Packages</a>
            <a class="collapse-item" href="members.php">All Members</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVoucher"
          aria-expanded="true" aria-controls="collapseBootstrap">
          <i class="fas fa-fw  fa-fax"></i>
          <span>Package Vouchers</span>
        </a>
        <div id="collapseVoucher" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Package Vouchers</h6>
            <a class="collapse-item" href="createpackages.php">Packages</a>
            <a class="collapse-item" href="vouchers.php">View Vouchers</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapserent" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-fw fa-chair"></i>
          <span>Rental Section</span>
        </a>
        <div id="collapserent" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Rental Section</h6>
            <a class="collapse-item" href="rental_subcategory.php">Subcategories</a>
            <a class="collapse-item" href="rentalitems.php">Items</a>
            <a class="collapse-item" href="rentbookings.php">All Bookings</a>
            <a class="collapse-item" href="gallery.php">Gallery</a>
          </div>
        </div>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStaff" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-fw fa-user"></i>
          <span>Staff</span>
        </a>
        <div id="collapseStaff" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Staff</h6>
            <a class="collapse-item" href="staff.php">All Staff</a>
            <a class="collapse-item" href="managers.php">All Managers</a>
            <a class="collapse-item" href="addstaff.php">Register Staff</a>
            <a class="collapse-item" href="addsaloonstaff.php">Register Saloon Staff</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVent" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-fw fa-graduation-cap"></i>
          <span>Inventory</span>
        </a>
        <div id="collapseVent" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Inventory</h6>
            <a class="collapse-item" href="inventory.php">Inventory</a>
            <a class="collapse-item" href="inventory_departments.php">Inventory Departments</a>
            <a class="collapse-item" href="inventory_system.php">Inventory System</a>
            <a class="collapse-item" href="inventory_log_details.php">Inventory logs</a>
          </div>
        </div>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEx" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-money-bill"></i>
          <span>Expenses</span>
        </a>
        <div id="collapseEx" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="expenses.php">View expenses</a>
            <a class="collapse-item" href="expense_title.php">Expense titles</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="delivery_rates.php">
          <i class="fas fa-truck"></i>
          <span>Delivery Rates</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="pendingtransfers.php">
          <i class="fas fa-money-bill-wave"></i>
          <span>Pending transfers</span>
        </a>
      </li>

      <li class="nav-item">
        <!-- <a class="nav-link" href="salesreport.php"> -->
          <a class="nav-link" href="foodsalesreport.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Sales Report</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="admin_bank_accounts.php">
            <i class="fas fa-money-bill-wave"></i>
            <span>Bank accounts</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="giftcard.php">
            <i class="fas fa-fw fa-id-card"></i>
            <span>Giftcard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="reviews.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Reviews</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="settings.php">
            <i class="fas fa-fw fa-tools"></i>
            <span>Settings</span>
          </a>
        </li>

      <?php } else { ?>



        <hr class="sidebar-divider my-0">



        <?php if (in_array("saloon", $admincategories)) { ?>
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSaloon" aria-expanded="true"
              aria-controls="collapseBootstrap">
              <i class="fas fa-fw fa-home"></i>
              <span>Saloon and Spa</span>
            </a>
            <div id="collapseSaloon" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Saloon and Spa</h6>
                <?php if ($status == "manager") { ?>
                  <a class="collapse-item" href="categories.php">Categories</a>
                  <a class="collapse-item" href="addsubcategory.php">Add New Subcategory</a>
                  <a class="collapse-item" href="services.php">All Services</a>
                  <a class="collapse-item" href="storebookings.php">Walk-in Bookings</a>
                  <a class="collapse-item" href="onlinebookings.php">Online Bookings</a>
                  <a class="collapse-item" href="bookings.php">Start An Appointment</a>
                <?php } ?>

                <?php if ($status == "cashier") { ?>
                  <a class="collapse-item" href="storebookings.php">Walk-in Bookings</a>
                  <a class="collapse-item" href="onlinebookings.php">Online Bookings</a>
                  <a class="collapse-item" href="bookings.php">Start An Appointment</a>
                <?php } ?>

              </div>
            </div>
          </li>


          <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVent" aria-expanded="true"
              aria-controls="collapseBootstrap">
              <i class="fas fa-fw fa-graduation-cap"></i>
              <span>Inventory</span>
            </a>
            <div id="collapseVent" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Inventory</h6>
                <a class="collapse-item" href="inventory.php">Inventory</a>
                <a class="collapse-item" href="inventory_system.php">Inventory System</a>
              </div>
            </div>
          </li> -->


        <?php } ?>

        <?php if (in_array("orishirishi", $admincategories)) { ?>
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSaloons"
              aria-expanded="true" aria-controls="collapseBootstrap">
              <i class="fas fa-fw fa-utensils"></i>
              <span>Orishirishi</span>
            </a>
            <div id="collapseSaloons" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Orishirishi</h6>
                <?php if ($status == "manager") { ?>
                  <a class="collapse-item" href="foodmenu.php">All Orishirishi</a>
                  <a class="collapse-item" href="addfood.php">Add New Orishirishi</a>
                  <a class="collapse-item" href="storeorders.php">Walk-in Orders</a>
                  <a class="collapse-item" href="onlineorders.php">Online Orders</a>
                  <a class="collapse-item" href="startorder.php">Start Transaction</a>
                  <a class="collapse-item" href="itemsreport.php">Stocks Report</a><?php } ?>


                <?php if ($status == "cashier") { ?>
                  <a class="collapse-item" href="storeorders.php">Walk-in Orders</a>
                  <a class="collapse-item" href="onlineorders.php">Online Orders</a>
                  <a class="collapse-item" href="startorder.php">Start Transaction</a><?php } ?>

                <?php if ($status == "storekeeper") { ?>
                  <a class="collapse-item" href="itemsreport.php">Stocks Report</a><?php } ?>

              </div>
            </div>
          </li><?php } ?>


        <?php if ($status == "cashier") { ?>
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEx" aria-expanded="true"
              aria-controls="collapseBootstrap">
              <i class="fas fa-money-bill"></i>
              <span>Expenses</span>
            </a>
            <div id="collapseEx" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="expenses.php">View expenses</a>
                <a class="collapse-item" href="expense_title.php">Expense titles</a>
              </div>
            </div>
          </li>
        <?php } ?>

        <?php if ($status == "storekeeper") { ?>
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVent" aria-expanded="true"
              aria-controls="collapseBootstrap">
              <i class="fas fa-fw fa-graduation-cap"></i>
              <span>Inventory</span>
            </a>
            <div id="collapseVent" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Inventory</h6>
                <a class="collapse-item" href="inventory.php">Inventory</a>
                <a class="collapse-item" href="inventory_departments.php">Inventory Departments</a>
                <a class="collapse-item" href="inventory_system.php">Inventory System</a>
              </div>
            </div>
          </li>
        <?php } ?>

        <?php if (in_array("kitchen", $admincategories)) { ?>
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDelta" aria-expanded="true"
              aria-controls="collapseBootstrap">
              <i class="fas fa-fw fa-store"></i>
              <span>Delta Kitchen</span>
            </a>
            <div id="collapseDelta" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Delta Kitcheni</h6>
                <?php if ($status == "manager") { ?>
                  <a class="collapse-item" href="delta_subcategory.php">Subcategories</a>
                  <a class="collapse-item" href="delta_meals.php">Additional Categories</a>
                  <a class="collapse-item" href="meals.php">Additional Meals</a>
                  <a class="collapse-item" href="delta_protein.php">Proteins</a>
                  <a class="collapse-item" href="deltaorders.php">All Orders</a><?php } ?>

                <?php if ($status == "cashier") { ?>
                  <a class="collapse-item" href="deltaorders.php">All Orders</a><?php } ?>

                <!--- <a class="collapse-item" href="startorder.php">Start Transaction</a> --->
          </div>
        </div>
      </li>
      <?php } ?>


      <?php if (in_array("repair", $admincategories)) { ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRepair" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-fw fa-tools"></i>
          <span>Repair Center</span>
        </a>
        <div id="collapseRepair" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Repair Center</h6>
            <a class="collapse-item" href="repaircenter.php">All Requests</a>
            <a class="collapse-item" href="repairhistory.php">Repairs History</a>
            <a class="collapse-item" href="startrepair.php">Submit Request</a>
          </div>
        </div>
      </li>
      <?php } ?>

      <?php if (in_array("academy", $admincategories)) { ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAca" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-fw fa-graduation-cap"></i>
          <span>Academy</span>
        </a>
        <div id="collapseAca" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">CHB LUXURY ACADEMY</h6>
            <a class="collapse-item" href="training.php">Trainings</a>
            <a class="collapse-item" href="duration.php">Durations</a>
            <a class="collapse-item" href="academybooking.php">Bookings</a>
          </div>
        </div>
      </li>
      <?php } ?>

      <?php if (in_array("members", $admincategories)) { ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMember" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-fw fa-credit-card"></i>
          <span>Membership</span>
        </a>
        <div id="collapseMember" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Membership</h6>
            <a class="collapse-item" href="packages.php">Packages</a>
            <a class="collapse-item" href="members.php">All Members</a>
          </div>
        </div>
      </li>
      <?php } ?>

      <?php if (in_array("vouchers", $admincategories)) { ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVoucher"
          aria-expanded="true" aria-controls="collapseBootstrap">
          <i class="fas fa-fw  fa-fax"></i>
          <span>Package Vouchers</span>
        </a>
        <div id="collapseVoucher" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Package Vouchers</h6>
            <a class="collapse-item" href="createpackages.php">Packages</a>
            <a class="collapse-item" href="vouchers.php">View Vouchers</a>
          </div>
        </div>
      </li>
      <?php } ?>

      <?php if (in_array("rental", $admincategories)) { ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapserent" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-fw fa-chair"></i>
          <span>Rental Section</span>
        </a>
        <div id="collapserent" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Rental Section</h6>
            <a class="collapse-item" href="rental_subcategory.php">Subcategories</a>
            <a class="collapse-item" href="rentalitems.php">Items</a>
            <a class="collapse-item" href="rentbookings.php">All Bookings</a>
            <a class="collapse-item" href="gallery.php">Gallery</a>
          </div>
        </div>
      </li>
      <?php } ?>


      <?php if (in_array("staff", $admincategories)) { ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStaff" aria-expanded="true"
          aria-controls="collapseBootstrap">
          <i class="fas fa-fw fa-user"></i>
          <span>Staff</span>
        </a>
        <div id="collapseStaff" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Staff</h6>
            <a class="collapse-item" href="staff.php">All Staff</a>
            <a class="collapse-item" href="managers.php">All Managers</a>
            <a class="collapse-item" href="addstaff.php">Register Staff</a>
            <a class="collapse-item" href="addsaloonstaff.php">Register Saloon Staff</a>
          </div>
        </div>
      </li>
      <?php } ?>

      <?php if (in_array("giftcard", $admincategories)) { ?>
      <li class="nav-item">
        <a class="nav-link" href="giftcard.php">
          <i class="fas fa-fw fa-id-card"></i>
          <span>Giftcard</span>
        </a>
      </li>
      <?php } ?>


      <?php if (in_array("giftcard", $admincategories)) { ?>
      <li class="nav-item">
        <a class="nav-link" href="giftcard.php">
          <i class="fas fa-fw fa-id-card"></i>
          <span>Giftcard</span>
        </a>
      </li>
      <?php } ?>

      <?php if (in_array("review", $admincategories)) { ?>
      <li class="nav-item">
        <a class="nav-link" href="reviews.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Reviews</span>
        </a>
      </li>


      <?php }
      } ?>


      <?php include 'dedicatedRoutes.php' ?>
    </ul>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->

        <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
          <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <ul class="navbar-nav ml-auto">

            <div class="topbar-divider d-none d-sm-block"></div>

            <li class="nav-item dropdown no-arrow">
              <div style="display: flex; justify-content: center; align-items: center;">
                <div class="text-white">
                  <?php
                  //  echo $status
                  ?>
                </div>
                <?php if ($status === 'superadmin' || $status === 'cashier'): ?>
                  <a href="pendingtransfers.php">
                    <div class="text-white p-3">
                      <div style="position: relative;">

                        <div class='notification-count'>
                          <div class="ripple-container">
                            <div class="circle-text"><?php echo $pending_transfers ?></div>
                            <?php if ($pending_transfers > 0): ?>
                              <div>
                                <div class='ripple-circle'></div>
                                <div class='ripple-circle'></div>
                                <div class='ripple-circle'></div>
                              </div>
                            <?php endif; ?>
                          </div>
                        </div>

                      </div>
                      <i class="fas fa-money-bill-wave"></i>
                    </div>
                  </a>
                <?php endif; ?>

                <!-- <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
                  <img class="img-profile rounded-circle" src="img/boy.png" style="max-width: 60px">
                  <span class="ml-2 d-none d-lg-inline text-white small"><?php echo $name; ?></span>
                </a>
              </div>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div> -->
                <!-- Profile + Dropdown -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" src="img/boy.png" style="max-width: 60px">
                <span class="ml-2 d-none d-lg-inline text-white small">
                  <?php echo $name; ?>
                </span>
              </a>
              <!-- Dropdown Menu -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php"
                  onclick="return confirm('Are you sure you want to log out?');">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>


            </li>
          </ul>
        </nav>
        <!-- Topbar -->



        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">

          <style>
            .text-primary {
              color: #000 !important;
            }

            .btn-primary {
              background: #000 !important;
              border: none !important;
            }

            a {
              color: #FFC700;
            }

            a:hover {
              color: #000;
            }

            body {
              color: #000 !important;
              font-family: 'Poppins';
            }

            .page-item.active .page-link {
              z-index: 1;
              color: #fff;
              background-color: #FFC700;
              border-color: #FFC700;
            }

            .page-link {
              position: relative;
              display: block;
              padding: 0.5rem 0.75rem;
              margin-left: -1px;
              line-height: 1.25;
              color: #FFC700;
              background-color: #fff;
              border: 1px solid #FFC700;
              -webkit-box-shadow: 0 .125rem .25rem 0 rgba(58, 59, 69, .2) !important;
              box-shadow: 0 .125rem .25rem 0 rgba(58, 59, 69, .2) !important;
            }

            .form-control {
              font-size: 13px;
              border-radius: 0;
              font-weight: 500;
              color: black;
            }

            .file_url {
              height: 50px;
            }

            .pre-order-div {
              background-color: #FFC700;
              width: 70px;
              font-size: 11px;
              justify-content: center;
              align-items: center;
              display: flex;
              padding: 4px;
              border-radius: 4px;
              margin-top: 3px;
            }

            .small {
              font-size: 12px;
            }

            .notification-count {
              font-size: 10px;
              position: absolute;
              top: -7px;
              left: -6px;

              justify-content: center;
              align-items: center;
              display: flex;
              border-radius: 100%;
              color: black;
            }


            .ripple-container {
              position: relative;
              width: 18px;
              height: 18px;
            }


            .circle-text {
              position: absolute;
              top: 50%;
              left: 50%;
              width: 100%;
              height: 100%;
              background-color: #FFC700;
              border-radius: 50%;
              color: #fff;
              /* Text color */
              font-size: 8px;
              /* Adjust size as needed */
              font-weight: bold;
              display: flex;
              justify-content: center;
              align-items: center;
              transform: translate(-50%, -50%);
              z-index: 2;
              /* Ensure it stays above the ripples */
            }

            /* Ripple animation */
            .ripple-circle {
              position: absolute;
              top: 50%;
              left: 50%;
              width: 100%;
              height: 100%;
              border: 4px solid #FFC700;
              border-radius: 50%;
              transform: translate(-50%, -50%) scale(0);
              opacity: 0;
              animation: ripple-animation 3s infinite;
            }


            .ripple-circle:nth-child(2) {
              animation-delay: 1s;
            }

            .ripple-circle:nth-child(3) {
              animation-delay: 2s;
            }

            /* Keyframes for the ripple effect */
            @keyframes ripple-animation {
              0% {
                transform: translate(-50%, -50%) scale(0.5);
                opacity: 1;
              }

              100% {
                transform: translate(-50%, -50%) scale(3.5);
                opacity: 0;
              }
            }

            .pre_order_con {
              display: flex;
            }

            /* .green {
              color: teal;
            }

            .red {
              color: red;
            } */


            .red-btn {
              background-color: red;
              width: 70px;
              font-size: 11px;
              justify-content: center;
              align-items: center;
              display: flex;
              padding: 4px;
              border-radius: 4px;
              margin-top: 3px;
              color: white;
              margin-left: 4px
            }

            .green-btn {
              background-color: teal;
              width: 70px;
              font-size: 11px;
              justify-content: center;
              align-items: center;
              display: flex;
              padding: 4px;
              border-radius: 4px;
              margin-top: 3px;
              color: white;
              margin-left: 4px
            }





            .ter {
              background-color: #fff;
              padding: 0 10px;
            }

            .check {
              padding: 2%;
              font-size: 12px;
              width: 25%;
            }

            .check span {
              font-size: 13px;
              font-weight: 600;
            }

            .btn-buya {
              display: inline-block;
              padding: 6px !important;
              border: none;
              color: #fff;
              font-size: 10px !important;
              text-transform: uppercase;
              font-family: "Poppins", sans-serif;
              font-weight: 600;
              transition: 0.3s;
              background: #FEBF01;
              margin: 4px;
            }

            .btn-buya:hover {
              font-size: 12px !important;
              font-weight: 800;
              background: #000;
            }

            .form-control {
              height: 40px;
              border-radius: none !important;
            }

            .section-title h2::after {
              content: "";
              position: absolute;
              display: block;
              width: 80px;
              background: none;
              bottom: 0;
              left: calc(2% - 25px);
            }

            .box {
              border-radius: 0px;
            }

            .pricing .box {
              padding: 20px 0 0;
              background: #f8f8f8;
              text-align: center;
              box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.12);
              border-radius: 0px;
              position: relative;
              overflow: hidden;
            }

            .nav-tabs .nav-link.active {
              background-color: #FEBF01;
              color: #fff;
            }

            .nav-tabs .nav-link {
              color: #000;
            }

            /* Search Bar and Suggestions Styles */
            .search-container {
              position: relative;
              margin-bottom: 20px;
              width: 100%;
              max-width: 500px;
              display: flex;
              align-items: center;
              gap: 10px;
            }

            .search-input {
              flex-grow: 1;
              padding: 10px;
              font-size: 14px;
              border: 2px solid #FEBF01;
              border-radius: 5px;
              outline: none;
            }

            .search-suggestions {
              position: absolute;
              top: 100%;
              left: 0;
              right: 0;
              background: #fff;
              border: 1px solid #ddd;
              border-radius: 5px;
              max-height: 200px;
              overflow-y: auto;
              z-index: 1000;
              display: none;
            }

            .search-suggestions div {
              padding: 10px;
              cursor: pointer;
              border-bottom: 1px solid #eee;
            }

            .search-suggestions div:hover {
              background: #f0f0f0;
            }

            .search-suggestions div:last-child {
              border-bottom: none;
            }

            .nowrap {
              white-space: nowrap;
            }



            /* ___________orderfood___________ */
            .ter {
              background-color: #fff;
              padding: 0 5px;
            }

            .check {
              padding: 2%;
              font-size: 12px;
              width: 25%;
            }

            .check span {
              font-size: 13px;
              font-weight: 700;
            }

            .img {
              max-width: 30%;
              max-height: 30%;
              border-radius: 50%;
            }

            .btn-buya {
              display: inline-block;
              padding: 6px;
              border: none;
              color: #fff;
              font-size: 10px;
              text-transform: uppercase;
              font-family: "Montserrat", sans-serif;
              font-weight: 800;
              transition: 0.3s;
              background: #FEBF01;
            }

            #clocs {
              display: none;
            }

            #cloch {
              display: none;
            }

            .koy {
              color: #FFC700;
            }

            .submita {
              background: #FFC700;
              color: #fff;
              border-radius: 5px;
              padding: 10px;
              font-size: 14px;
              font-weight: 600;
              outline: none;
              border: none;
              float: right;
              margin-bottom: 10%;
            }

            .submita:hover {
              background: #000000;
              color: #fff;
              outline: none;
              border: none;
              margin-bottom: 10%;
            }

            .input-group {
              display: flex;
              justify-content: center;
              align-items: center;
            }

            /* ___________orderfood___________ */



            .awaiting-box {
              display: inline-block;
              position: relative;
              margin-top: 10px;
              font-size: 20px;
              color: #333;

            }

            .awaiting-box.active_awaiting {
              color: #e74c3c;
              animation: dance 4s linear infinite;
              font-size: 14px;
            }

            .awaiting-box .badge {
              position: absolute;
              top: -8px;
              right: -12px;
              font-size: 9px;
            }

            .flex_spaced {
              display: flex;
              justify-content: space-between;
              align-items: center;
            }

            @keyframes dance {
              0% {
                transform: rotate(0deg);
              }

              50% {
                transform: rotate(-15deg);

              }

              75% {
                transform: rotate(12deg);

              }

              100% {
                transform: rotate(0deg);

              }
            }




            /* __________ inventory.php ______________ */

            .alert {
              border: 1px solid #ffc107;
              background-color: #fff;
              color: #000;
              margin: 20px auto;
              padding: 15px;
              width: 50%;
              max-width: 600px;
              border-radius: 5px;
            }

            .alert-success {
              background-color: #68e986ff;
              border-color: #c3e6cb;
              color: #155724;
            }

            .alert-danger {
              background-color: #f8d7da;
              border-color: #f5c6cb;
              color: #721c24;
            }

            .custom-modal {
              display: none;
              position: fixed;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              background-color: rgba(0, 0, 0, 0.5);
              z-index: 1055;
              overflow-y: auto;
            }

            .custom-modal-dialog {
              max-width: 500px;
              margin: 1.75rem auto;
              min-height: calc(100% - 3.5rem);
              display: flex;
              align-items: center;
            }

            .custom-modal-content {
              background-color: #fff;
              border-radius: 0.3rem;
              box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
              width: 100%;
              max-height: 80vh;
              overflow-y: auto;
            }

            .custom-modal-header {
              display: flex;
              justify-content: space-between;
              align-items: center;
              padding: 1rem;
              border-bottom: 1px solid #dee2e6;
              background-color: #000;
              color: #fff;
            }

            .custom-modal-header h6 {
              margin: 0;
              font-size: 1.25rem;
            }

            .custom-modal-close {
              font-size: 1.5rem;
              line-height: 1;
              color: #fff;
              background: none;
              border: none;
              cursor: pointer;
            }

            .custom-modal-body {
              padding: 1rem;
            }

            @media (max-width: 576px) {
              .custom-modal-dialog {
                margin: 0.5rem;
                max-height: 90vh;
              }

              .custom-modal-content {
                max-height: 80vh;
                overflow-y: auto;
              }
            }

            .bold-num{
              color: teal;
              font-weight: 900;
              font-size: 20px;
            }

            /* __________ inventory.php ______________ */
          </style>