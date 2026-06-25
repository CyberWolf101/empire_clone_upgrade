<?php 
ob_start();
// session_save_path("/tmp");
// session_start();
session_save_path("sessions");

// Only start session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Release session lock early if you don't need to write anymore
session_write_close();

include "connect.php";

// Set PHP timezone
date_default_timezone_set('Africa/Lagos');

// Set MySQL session timezone
mysqli_query($con, "SET time_zone = '+01:00'") or die("Cannot set timezone: " . mysqli_error($con));



// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// echo "Step 1: Script started<br>";

$today = date("Y-m-d");
// echo "Step 2: Today is $today<br>";

// End memberships
$sql = "SELECT * FROM members WHERE paystatus='paid' AND status='valid' AND end_date <= '$today' ORDER BY s DESC";
// echo "Step 3: Running query → $sql<br>";

$sql2 = mysqli_query($con, $sql);
if (!$sql2) {
  die("Step 3 FAILED: " . mysqli_error($con));
}
// echo "Step 3 SUCCESS: Members query executed<br>";
$username = '';
$saloon = '';
$order_id = '';
while ($row = mysqli_fetch_array($sql2)) {
  $memberid = $row['cardno'];
  $type = $row["type"];
  $name = $row["name"];
  $email = $row["email"];
  $phone = $row["phone"];
  $startdate = $row["start_date"];
  $total_all = $row["total_amount"];
  $enddate = $row["end_date"];
  // echo "Processing member: $memberid ($email)<br>";

  // $insert = mysqli_query($con, "UPDATE members SET status='invalid' WHERE id='$memberid'");
  $insert = mysqli_query($con, "UPDATE members SET status='invalid' WHERE s='$memberid'");

  if (!$insert) {
    die("Step 4 FAILED: Could not update member $memberid → " . mysqli_error($con));
  }
  // echo "Step 4 SUCCESS: Member $memberid set to invalid<br>";

  // Mail Function
  $email_to = $email;
  $email_subject = "Membership Package Expired! - CHBLUXURYEMPIRE";
  $email_message = "
    <div style='background-color:#000000; color:#fff !important; padding:10px 20px; '>
        <p style='text-align:left;'>
            <img src='http://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='60px;' style='margin-top:13px;'>
            <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>Date: $today</font>
        </p>
        <h5>MEMBERSHIP DETAILS</h5>
        <p style='color:white;'>$name<br>$email<br>$phone<br>$type<br>Membership Expires: $enddate</p>
        <p style='color:white;'>Hello Dear Customer, your membership package ID $memberid which started on $startdate with a subscription of $type has expired today $today</p>
        <p style='color:#fff; font-size:13px;'>Thank you for your patronage and we hope to see you again soon!</p> 
        <br><br>
        <p style='text-align:center;'><a href='chbluxuryempire.com' style='color:#FFC700;'>CHBLUXURYEMPIRE</a></p>
    </div>";

  $header = 'From: "CHBLUXURYEMPIRE" <noreply@chbluxuryempire.com>' . "\r\n";
  $header .= "Reply-To: noreply@chbluxuryempire.com\r\n";
  $header .= "MIME-Version: 1.0\r\n";
  $header .= "Content-type: text/html; charset=UTF-8\r\n";

  if (mail($email_to, $email_subject, $email_message, $header)) {
    // echo "Step 5 SUCCESS: Mail sent to $email<br>";
  } else {
    // echo "Step 5 FAILED: Could not send mail to $email<br>";
  }
}

// Saloon cookie check
if (isset($_COOKIE['foodID'])) {
  // echo "Step 6: Found foodID cookie<br>";
  $saloon = $_COOKIE['foodID'];

  $sql = "SELECT * FROM saloon_orders WHERE id='$saloon'";
  $sql2 = mysqli_query($con, $sql);
  if (!$sql2) {
    die("Step 6 FAILED: Could not fetch saloon order → " . mysqli_error($con));
  }
  $row = mysqli_fetch_array($sql2);
  $type = isset($row["bookingtype"]) && $row["bookingtype"] !== ''
    ? (int) $row["bookingtype"]
    : 0;
  $kit = $row["saloonkit"] ?? null;
  $username = mysqli_real_escape_string($con, $_SESSION['username'] ?? '');
  $c_phone = mysqli_real_escape_string($con, $_SESSION['phone'] ?? '');
  $c_email = mysqli_real_escape_string($con, $_SESSION['email'] ?? '');
  $status = $row["status"] ?? null;


  // echo "Step 6 SUCCESS: Loaded saloon order $saloon<br>";

  // Refreshments
  $sam = "SELECT SUM(totalprice) FROM refreshments WHERE orderid='$saloon'";
  $sam2 = mysqli_query($con, $sam);
  if (!$sam2) {
    die("Step 7 FAILED: Refreshments query → " . mysqli_error($con));
  }
  $row = mysqli_fetch_array($sam2);
  $total_items = $row[0];

  // echo "Step 7 SUCCESS: Refreshments total = $total_items<br>";

  // $total_all = $total_items;
  $total_all = isset($row[0]) ? (int) $row[0] : 0;   // force integer

  $insert = mysqli_query($con, "UPDATE saloon_orders SET total_amount='$total_all' WHERE id='$saloon'");
  if (!$insert) {
    die("Step 8 FAILED: Could not update saloon order total → " . mysqli_error($con));
  }
  // echo "Step 8 SUCCESS: Updated saloon order total<br>";
}
/**
 * 
 * 
 * 
 */

// Site settings
$sql = "SELECT * FROM site_settings";
$sql2 = mysqli_query($con, $sql);
if (!$sql2) {
  die("Step 9 FAILED: Could not fetch site settings → " . mysqli_error($con));
}
$row = mysqli_fetch_array($sql2);
$apikey = $row["apikey"];
$sitemail = $row["sitemail"];
$sitename = $row["sitename"];
$siteimg = $row["site_img"];
$kitprice = $row["pedicurekit"];
$rentprice = $row["rental"];
$late_fee = $row["latefee"];
$walkinIncrease = $row["walk_in_fee"];
$key = 'AIzaSyD6MS4bUOjkP0fYUklsVzIKYmGmb_MheGQ';

// echo "Step 9 SUCCESS: Site settings loaded<br>";
?>

<?php
$createCustomerTableSQL = "
CREATE TABLE IF NOT EXISTS customers(
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(255) NOT NULL,
  unique_id VARCHAR(255) NOT NULL DEFAULT '',
  added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total_spent VARCHAR(255) NOT NULL DEFAULT '0',
  order_count VARCHAR(255) NOT NULL DEFAULT '0',
  first_order_date VARCHAR(255) NOT NULL
);
";

$createCustomerDiscountTable = "
CREATE TABLE IF NOT EXISTS customers_discounts(
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer_unique_id VARCHAR(255) NOT NULL,
  discount_percentage VARCHAR(255) NOT NULL,
  product_category VARCHAR(255) NOT NULL,
  discount_status VARCHAR(255) NOT NULL DEFAULT 'Inactive'
)
";

$refreshmentAlter = "ALTER TABLE refreshments ADD COLUMN discount_added VARCHAR(255) NOT NULL DEFAULT '0'";

$creditSalesTableSQL = "
CREATE TABLE IF NOT EXISTS credit_sales(
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  orderid VARCHAR(255) NOT NULL,
  itemid VARCHAR(255) NOT NULL,
  item VARCHAR(255) NOT NULL,
  unitprice VARCHAR(255),
  amount_paid VARCHAR(255) NOT NULL DEFAULT '0',
  quantity VARCHAR(255) NOT NULL,
  totalprice VARCHAR(255) NOT NULL,
  status VARCHAR(255) NOT NULL DEFAULT 'pending',
  item_category VARCHAR(255) NOT NULL,
  added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)
";

$customerDiscountsAlterSQL = "ALTER TABLE customers ADD COLUMN credit_sales_eligibility VARCHAR(255) NOT NULL DEFAULT 'false';";
$creditSalesAlterSQL = "ALTER TABLE credit_sales ADD COLUMN customer VARCHAR(255) NOT NULL";
$correction = "ALTER TABLE customers_discounts DROP COLUMN IF EXISTS credit_sales_eligibility";
$foodMenuAlter = "ALTER TABLE food_menu ADD COLUMN visibility VARCHAR(255) NOT NULL DEFAULT 'visible'";
$foodMenuAlter2 = "ALTER TABLE food_menu ADD COLUMN special_item VARCHAR(255) NOT NULL DEFAULT 'false'";

$createSpecialItemsTableSQL = "
CREATE TABLE IF NOT EXISTS special_items(
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  item VARCHAR(255) NOT NULL,
  category VARCHAR(255) NOT NULL,
  ingredient_id VARCHAR(255) NOT NULL,
  ingredient_name VARCHAR(255) NOT NULL,
  added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)";

$alterSpecialItemsSQL = "ALTER TABLE special_items ADD COLUMN item_id VARCHAR(255) NOT NULL";
$alterSpecialItemsSQL2 = "ALTER TABLE special_items ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'active'";
$alterSpecialItemsSQL3 = "ALTER TABLE special_items ADD COLUMN ingredient_quantity VARCHAR(255) NOT NULL DEFAULT '1'";
$alterRefreshmentSQL = "ALTER TABLE refreshments ADD COLUMN amount_paid VARCHAR(255) NOT NULL";

$createCreditSalesTransfers = "
CREATE TABLE IF NOT EXISTS credit_sales_transfers(
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  orderid VARCHAR(255) NOT NULL,
  fileUrl VARCHAR(255) NOT NULL,
  transfer_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  amount_paid VARCHAR(255) NOT NULL DEFAULT '0',
  method VARCHAR(255) NOT NULL
)";

$alterCreditSalesTransfers = "ALTER TABLE credit_sales_transfers ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'pending'";
$alterCreditSalesTransfers2 = "ALTER TABLE credit_sales_transfers ADD COLUMN bank VARCHAR(255) NOT NULL DEFAULT ''";

$trainingItemsTableSQL = "
CREATE TABLE IF NOT EXISTS training_items(
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  item_id VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  image VARCHAR(255) NOT NULL,
  price VARCHAR(255) NOT NULL,
  training_id VARCHAR(255) NOT NULL,
  added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)";

$academyCarttrainingItemsTableSQL = "
CREATE TABLE IF NOT EXISTS academy_cart_training_items(
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  training_item_id VARCHAR(255) NOT NULL,
  training_id VARCHAR(255) NOT NULL,
  added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)";

$alterAg2 = "ALTER TABLE training_items DROP COLUMN IF EXISTS image";
$alterAgg = "ALTER TABLE training ADD COLUMN discount_added VARCHAR(255) NOT NULL DEFAULT '0'";
$alterAAg = "ALTER TABLE academy_cart_training_items ADD COLUMN item_for VARCHAR(255) NOT NULL DEFAULT ''";
$academyCartAlter = "ALTER TABLE academy_cart ADD COLUMN discount_applied VARCHAR(255) NOT NULL DEFAULT 'false'";
$alterDuration = "ALTER TABLE durations ADD COLUMN duration_unit VARCHAR(255) NOT NULL";

$createListOfItemsToBring = "
CREATE TABLE IF NOT EXISTS training_items_to_bring(
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  training_id VARCHAR(255) NOT NULL,
  item_name VARCHAR(255) NOT NULL,
  required VARCHAR(255) NOT NULL DEFAULT 'true'
)";

$createTrainingStartAndEndDates = "
CREATE TABLE IF NOT EXISTS training_dates(
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  training_id_from_saloon_orders VARCHAR(255) NOT NULL,
  start_date VARCHAR(255) NOT NULL,
  reminder_interval VARCHAR(255) NOT NULL,
  reminder_unit VARCHAR(255) NOT NULL
)";

$alterTrainingDates = "ALTER TABLE saloon_orders ADD COLUMN added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP";
$alterTrainingDates2 = "ALTER TABLE training_dates ADD COLUMN reminder_unit VARCHAR(255) NOT NULL";

$log = "
CREATE TABLE IF NOT EXISTS `reminder_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` VARCHAR(50) NOT NULL,
  `milestone_sent` VARCHAR(100) NOT NULL,
  `sent_at` DATETIME NOT NULL,
  UNIQUE KEY `unique_reminder` (`booking_id`, `milestone_sent`)
);";

$createBakersGuide = "
CREATE TABLE IF NOT EXISTS bakers_guide (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  item VARCHAR(255) NOT NULL,
  guide_id VARCHAR(255) NOT NULL UNIQUE,
  added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)";

$createNeededItemsTable = "
CREATE TABLE IF NOT EXISTS guides_needed_items (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  guide_id VARCHAR(255) NOT NULL,
  item_id VARCHAR(255) NOT NULL,
  quantity VARCHAR(255) NOT NULL,
  added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)";

$createBakersRequests = "
CREATE TABLE IF NOT EXISTS bakers_requests (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  request_code VARCHAR(50) NOT NULL UNIQUE,
  guide_id VARCHAR(255) NOT NULL,
  quantity INT(11) NOT NULL DEFAULT 1,
  status VARCHAR(255) NOT NULL DEFAULT 'Pending',
  requested_by VARCHAR(255) DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  approved_by VARCHAR(255) DEFAULT NULL,
  requested_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_on DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (guide_id),
  INDEX (status),
  INDEX (request_code)
)";

$bakersRequestItems = "
CREATE TABLE IF NOT EXISTS bakers_request_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  guide_id VARCHAR(50) NOT NULL,
  item_id VARCHAR(50) NOT NULL,
  quantity VARCHAR(255) NOT NULL,
  added_on DATETIME DEFAULT CURRENT_TIMESTAMP
);";

// Execute table builders
mysqli_query($con, $createListOfItemsToBring);
mysqli_query($con, $createBakersGuide);
mysqli_query($con, $bakersRequestItems);
mysqli_query($con, $createNeededItemsTable);
mysqli_query($con, $createBakersRequests);
mysqli_query($con, $log);
mysqli_query($con, $createTrainingStartAndEndDates);
mysqli_query($con, $trainingItemsTableSQL);
mysqli_query($con, $academyCarttrainingItemsTableSQL);
mysqli_query($con, $createCreditSalesTransfers);
mysqli_query($con, $createSpecialItemsTableSQL);
mysqli_query($con, $creditSalesTableSQL);
mysqli_query($con, $createCustomerDiscountTable);
mysqli_query($con, $createCustomerTableSQL);

$alterArray = [
  ["table" => "academy_cart_training_items", "column" => "item_for", "query" => $alterAAg],
  ["table" => "training", "column" => "discount_added", "query" => $alterAgg],
  ["table" => "training_items", "column" => "image", "query" => ""],
  ["table" => "refreshments", "column" => "amount_paid", "query" => $alterRefreshmentSQL],
  ["table" => "special_items", "column" => "ingredient_quantity", "query" => $alterSpecialItemsSQL3],
  ["table" => "special_items", "column" => "status", "query" => $alterSpecialItemsSQL2],
  ["table" => "special_items", "column" => "item_id", "query" => $alterSpecialItemsSQL],
  ["table" => "credit_sales_transfers", "column" => "bank", "query" => $alterCreditSalesTransfers2],
  ["table" => "credit_sales_transfers", "column" => "status", "query" => $alterCreditSalesTransfers],
  ["table" => "food_menu", "column" => "visibility", "query" => $foodMenuAlter],
  ["table" => "food_menu", "column" => "special_item", "query" => $foodMenuAlter2],
  ["table" => "credit_sales", "column" => "customer", "query" => $creditSalesAlterSQL],
  ["table" => "academy_cart", "column" => "discount_applied", "query" => $academyCartAlter],
  ["table" => "refreshments", "column" => "discount_added", "query" => $refreshmentAlter],
  ["table" => "customers", "column" => "credit_sales_eligibility", "query" => $customerDiscountsAlterSQL],
  ["table" => "customer_discounts", "column" => "credit_sales_eligibility", "query" => ""],
  ["table" => "durations", "column" => "duration_unit", "query" => $alterDuration],
  ["table" => "saloon_orders", "column" => "added_on", "query" => $alterTrainingDates],
  ["table" => "chb_inventory", "column" => "countable", "query" => "ALTER TABLE chb_inventory ADD COLUMN countable VARCHAR(255) NOT NULL DEFAULT 'true'"],
  ["table" => "bakers_request_items", "column" => "collected_quantity", "query" => "ALTER TABLE bakers_request_items ADD collected_quantity VARCHAR(255) NOT NULL DEFAULT 0;"],
  ["table" => "bakers_requests", "column" => "status", "query" => "ALTER TABLE bakers_requests ADD status VARCHAR(50) NOT NULL DEFAULT 'Pending';"],
  ["table" => "bakers_requests", "column" => "approved_on", "query" => "ALTER TABLE bakers_requests ADD approved_on DATETIME NULL;"],
  ["table" => "bakers_requests", "column" => "approved_by", "query" => "ALTER TABLE bakers_requests ADD approved_by VARCHAR(255) NULL;"],
  ["table" => "bakers_requests", "column" => "approval_notes", "query" => "ALTER TABLE bakers_requests ADD approval_notes TEXT NULL;"],
  ["table" => "chb_inventory", "column" => "inventory_deducted", "query" => "ALTER TABLE chb_inventory ADD COLUMN inventory_deducted VARCHAR(255) NOT NULL DEFAULT 0;"],
  ["table" => "bakers_requests", "column" => "status", "query" => "ALTER TABLE bakers_requests MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Pending';"],
  ["table" => "bakers_requests", "column" => "approved_status", "query" => "ALTER TABLE bakers_requests ADD COLUMN approved_status VARCHAR(255) NOT NULL DEFAULT 'Pending';"],
  ["table" => "bakers_request_items", "column" => "status", "query" => "ALTER TABLE bakers_request_items ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'pending';"],
];

function tableExists($conn, $table) {
  $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
  return $result && mysqli_num_rows($result) > 0;
}

function columnExists($conn, $table, $column) {
  $table = mysqli_real_escape_string($conn, $table);
  $column = mysqli_real_escape_string($conn, $column);
  $result = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
  return $result && mysqli_num_rows($result) > 0;
}

foreach ($alterArray as $oneAlter) {
  if (empty($oneAlter["query"])) continue;
  $table = $oneAlter["table"];
  $column = $oneAlter["column"];

  if (!tableExists($con, $table)) continue;
  if (!columnExists($con, $table, $column)) {
    mysqli_query($con, $oneAlter["query"]);
  }
}

// Fixed non-deterministic UUID statements here
$newAlterArray = [
  [
    "table" => "staff",
    "column" => "staff_code",
    "query" => "ALTER TABLE staff ADD COLUMN staff_code VARCHAR(255) NOT NULL DEFAULT ''"
  ],
  [
    "table" => "staff",
    "column" => "code_status",
    "query" => "ALTER TABLE staff ADD COLUMN code_status VARCHAR(255) DEFAULT 'Inactive'"
  ],
  [
    "table" => "event_orders",
    "column" => "referral_code",
    "query" => "ALTER TABLE event_orders ADD COLUMN referral_code VARCHAR(255) DEFAULT ''"
  ],
  [
    "table" => "admin",
    "column" => "staff_code",
    "query" => "ALTER TABLE admin ADD COLUMN staff_code VARCHAR(255) NULL"
  ],
  [
    "table" => "admin",
    "column" => "code_status",
    "query" => "ALTER TABLE admin ADD COLUMN code_status VARCHAR(255) NULL"
  ],
  [
    "table" => "bank_accounts",
    "column" => "service_type",
    "query" => "ALTER TABLE bank_accounts ADD COLUMN service_type VARCHAR(255) NOT NULL DEFAULT ''"
  ],
  [
    "table" => "refreshments",
    "column" => "item_category",
    "query" => "ALTER TABLE refreshments ADD COLUMN item_category VARCHAR(255) NOT NULL DEFAULT ''"
  ],
  [
    "table" => "refreshments",
    "column" => "discount_added",
    "query" => "ALTER TABLE refreshments ADD COLUMN discount_added VARCHAR(255) NOT NULL DEFAULT '0'"
  ]
];

foreach ($newAlterArray as $NewOneAlter) {
  if (empty($NewOneAlter["query"])) continue;
  $table = $NewOneAlter["table"];
  $column = $NewOneAlter["column"];

  if (!tableExists($con, $table)) continue;
  if (!columnExists($con, $table, $column)) {
    mysqli_query($con, $NewOneAlter["query"]);
  }
}
?>

<?php include "headerContents.php"; ?>