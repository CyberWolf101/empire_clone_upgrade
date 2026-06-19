<?php include "header.php";
$unread_sql = "SELECT COUNT(*) AS unread_count FROM inventory_log WHERE read_status = 0";
$unread_inv_log = mysqli_fetch_assoc(mysqli_query($con, $unread_sql))['unread_count'];

$unread_sql = "SELECT COUNT(*) AS unread_count FROM inventory_log WHERE read_status = 0";
$unread_inv_log = mysqli_fetch_assoc(mysqli_query($con, $unread_sql))['unread_count'];
$createCustomerTableSQL = "
CREATE TABLE IF NOT EXISTS customers(
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
phone VARCHAR(255) NOT NULL,
unique_id VARCHAR(255) NOT NULL DEFAULT (
    CONCAT(
      'CUSTOMER-',
      UPPER(SUBSTRING(MD5(UUID()), 1, 8))
    )
  ),
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
$refreshmentAlter = "ALTER TABLE
  refreshments
ADD
  COLUMN discount_added VARCHAR(255) NOT NULL DEFAULT '0'";
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
$customerDiscountsAlterSQL = "ALTER TABLE customers 
  ADD COLUMN credit_sales_eligibility VARCHAR(255) NOT NULL DEFAULT 'false';";
$creditSalesAlterSQL = "ALTER TABLE credit_sales 
  ADD COLUMN customer VARCHAR(255) NOT NULL";
$correction = "ALTER TABLE customers_discounts
  DROP COLUMN IF EXISTS credit_sales_eligibility";
$foodMenuAlter = "ALTER TABLE food_menu
  ADD COLUMN visibility VARCHAR(255) NOT NULL DEFAULT 'visible'
  ";
$foodMenuAlter2 = "ALTER TABLE food_menu
  ADD COLUMN special_item VARCHAR(255) NOT NULL DEFAULT 'false'
  ";
$createSpecialItemsTableSQL = "CREATE TABLE IF NOT EXISTS special_items(
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  item VARCHAR(255) NOT NULL,
  category VARCHAR(255) NOT NULL,
  ingredient_id VARCHAR(255) NOT NULL,
  ingredient_name VARCHAR(255) NOT NULL,
  added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
  )";
$alterSpecialItemsSQL = "ALTER TABLE special_items
  ADD COLUMN item_id VARCHAR(255) NOT NULL";
$alterSpecialItemsSQL2 = "ALTER TABLE special_items
  ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'active'";
$alterSpecialItemsSQL3 = "ALTER TABLE special_items
  ADD COLUMN ingredient_quantity VARCHAR(255) NOT NULL DEFAULT '1'";
$alterRefreshmentSQL = "ALTER TABLE refreshments
  ADD COLUMN amount_paid VARCHAR(255) NOT NULL";
$createCreditSalesTransfers = "CREATE TABLE IF NOT EXISTS credit_sales_transfers(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    orderid VARCHAR(255) NOT NULL,
    fileUrl VARCHAR(255) NOT NULL,
transfer_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
amount_paid VARCHAR(255) NOT NULL DEFAULT '0',
method VARCHAR(255) NOT NULL
  )";
$alterCreditSalesTransfers = "ALTER TABLE credit_sales_transfers
  ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'pending'";
$alterCreditSalesTransfers2 = "ALTER TABLE credit_sales_transfers
  ADD COLUMN bank VARCHAR(255) NOT NULL DEFAULT ''";
$trainingItemsTableSQL = "CREATE TABLE IF NOT EXISTS training_items(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    item_id VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    price VARCHAR(255) NOT NULL,
    training_id VARCHAR(255) NOT NULL,
    added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
  )";
$academyCarttrainingItemsTableSQL = "CREATE TABLE IF NOT EXISTS academy_cart_training_items(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    training_item_id VARCHAR(255) NOT NULL,
    training_id VARCHAR(255) NOT NULL,
    added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
  )";
$alterAg2 = "ALTER TABLE training_items
  DROP COLUMN IF EXISTS image";
$alterAgg = "ALTER TABLE training
  ADD COLUMN discount_added VARCHAR(255) NOT NULL DEFAULT '0'";
$alterAAg = "ALTER TABLE academy_cart_training_items
  ADD COLUMN item_for VARCHAR(255) NOT NULL DEFAULT ''";
$academyCartAlter = "ALTER TABLE academy_cart
  ADD COLUMN discount_applied VARCHAR(255) NOT NULL DEFAULT 'false'";
$alterDuration = "ALTER TABLE durations
  ADD COLUMN duration_unit VARCHAR(255) NOT NULL";
$createListOfItemsToBring = "CREATE TABLE IF NOT EXISTS training_items_to_bring(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    training_id VARCHAR(255) NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    required VARCHAR(255) NOT NULL DEFAULT 'true'
  )";
$createTrainingStartAndEndDates = "CREATE TABLE IF NOT EXISTS training_dates(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    training_id_from_saloon_orders VARCHAR(255) NOT NULL,
    start_date VARCHAR(255) NOT NULL,
    reminder_interval VARCHAR(255) NOT NULL,
    reminder_unit VARCHAR(255) NOT NULL
  )";
$alterTrainingDates = "ALTER TABLE saloon_orders
  ADD COLUMN added_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP";
$alterTrainingDates2 = "ALTER TABLE training_dates
  ADD COLUMN reminder_unit VARCHAR(255) NOT NULL";
$log = "CREATE TABLE IF NOT EXISTS `reminder_logs` (
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

    status ENUM('pending', 'approved', 'rejected', 'completed')
        NOT NULL DEFAULT 'pending',

    requested_by VARCHAR(255) DEFAULT NULL,

    notes TEXT DEFAULT NULL,

    approved_by VARCHAR(255) DEFAULT NULL,

    requested_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_on DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX (guide_id),
    INDEX (status),
    INDEX (request_code)
)";
$bakersRequestItems = "CREATE TABLE IF NOT EXISTS bakers_request_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    guide_id VARCHAR(50) NOT NULL,
    item_id VARCHAR(50) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    added_on DATETIME DEFAULT CURRENT_TIMESTAMP
);";
mysqli_query($con, $createListOfItemsToBring);
mysqli_query($con, $createBakersGuide);
mysqli_query($con, $bakersRequestItems);
mysqli_query($con, $createNeededItemsTable);
mysqli_query($con, $createBakersRequests);
mysqli_query($con, $log);
mysqli_query($con, $createTrainingStartAndEndDates);
mysqli_query($con, $trainingItemsTableSQL);
mysqli_query($con, $academyCarttrainingItemsTableSQL);
mysqli_query($con, $createBakersGuide);
mysqli_query($con, $createCreditSalesTransfers);
mysqli_query($con, $createSpecialItemsTableSQL);
mysqli_query($con, $creditSalesTableSQL);
mysqli_query($con, $createCustomerDiscountTable);
mysqli_query($con, $createCustomerTableSQL);
// Alters
$alterArray = [
    [
        "table" => "academy_cart_training_items",
        "column" => "item_for",
        "query" => $alterAAg
    ],
    [
        "table" => "training",
        "column" => "discount_added",
        "query" => $alterAgg
    ],
    [
        "table" => "training_items",
        "column" => "image",
        // "query" => $alterAg2
        "query" => ""
    ],
    [
        "table" => "refreshments",
        "column" => "amount_paid",
        "query" => $alterRefreshmentSQL
    ],
    [
        "table" => "special_items",
        "column" => "ingredient_quantity",
        "query" => $alterSpecialItemsSQL3
    ],
    [
        "table" => "special_items",
        "column" => "status",
        "query" => $alterSpecialItemsSQL2
    ],
    [
        "table" => "special_items",
        "column" => "item_id",
        "query" => $alterSpecialItemsSQL
    ],
    [
        "table" => "credit_sales_transfers",
        "column" => "bank",
        "query" => $alterCreditSalesTransfers2
    ],
    [
        "table" => "credit_sales_transfers",
        "column" => "status",
        "query" => $alterCreditSalesTransfers
    ],
    [
        "table" => "food_menu",
        "column" => "visibility",
        "query" => $foodMenuAlter
    ],
    [
        "table" => "food_menu",
        "column" => "special_item",
        "query" => $foodMenuAlter2
    ],
    [
        "table" => "credit_sales",
        "column" => "customer",
        "query" => $creditSalesAlterSQL
    ],
    [
        "table" => "academy_cart",
        "column" => "discount_applied",
        "query" => $academyCartAlter
    ],
    [
        "table" => "refreshments",
        "column" => "discount_added",
        "query" => $refreshmentAlter
    ],
    [
        "table" => "customers",
        "column" => "credit_sales_eligibility",
        "query" => $customerDiscountsAlterSQL
    ],
    [
        "table" => "customer_discounts",
        "column" => "credit_sales_eligibility",
        // "query" => $correction
        "query" => ""
    ],
    [
        "table" => "durations",
        "column" => "duration_unit",
        "query" => $alterDuration
    ],
    [
        "table" => "saloon_orders",
        "column" => "added_on",
        "query" => $alterTrainingDates
    ],
    [
        "table" => "chb_inventory",
        "column" => "countable",
        "query" => "ALTER TABLE chb_inventory ADD COLUMN countable VARCHAR(255) NOT NULL DEFAULT 'true'"
    ],
    [
        "table"=> "bakers_request_items",
        "column"=> "collected_quantity",
        "query"=> "ALTER TABLE bakers_request_items
ADD collected_quantity DECIMAL(10,2) DEFAULT 0;",
    ],
    [
        "table"=> "bakers_request_items",
        "column"=> "collected_quantity",
        "query"=> "ALTER TABLE bakers_request_items
ADD collected_quantity DECIMAL(10,2) NOT NULL DEFAULT 0;",
    ],
    [
        "table"=> "bakers_requests",
        "column"=> "status",
        "query"=> "ALTER TABLE bakers_requests
ADD status VARCHAR(50) NOT NULL DEFAULT 'Pending';",
    ]
];
// if(!columnExists($con,'academy_cart_training_items','item_for')){
//     mysqli_query($con, $alterAAg);
// }
function tableExists($conn, $table)
{
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    return $result && mysqli_num_rows($result) > 0;
}
function columnExists($conn, $table, $column)
{
    $table = mysqli_real_escape_string($conn, $table);
    $column = mysqli_real_escape_string($conn, $column);

    $result = mysqli_query(
        $conn,
        "SHOW COLUMNS FROM `$table` LIKE '$column'"
    );

    return $result && mysqli_num_rows($result) > 0;
}
foreach ($alterArray as $oneAlter) {

    if (empty($oneAlter["query"]))
        continue;

    $table = $oneAlter["table"];
    $column = $oneAlter["column"];

    // 🚨 prevent crash if table doesn't exist
    if (!tableExists($con, $table))
        continue;

    if (!columnExists($con, $table, $column)) {
        mysqli_query($con, $oneAlter["query"]);
    }
}
// mysqli_query($con, $alterAgg);
// mysqli_query($con, $alterAg2);
// mysqli_query($con, $alterRefreshmentSQL);
// mysqli_query($con, $alterSpecialItemsSQL3);
// mysqli_query($con, $alterSpecialItemsSQL2);
// mysqli_query($con, $alterSpecialItemsSQL);
// mysqli_query($con, $alterCreditSalesTransfers2);
// mysqli_query($con, $alterCreditSalesTransfers);
// mysqli_query($con, $foodMenuAlter2);
// mysqli_query($con, $foodMenuAlter);
// mysqli_query($con, $creditSalesAlterSQL);
// mysqli_query($con, $academyCartAlter);
// mysqli_query($con, $refreshmentAlter);
// mysqli_query($con, $customerDiscountsAlterSQL);
// mysqli_query($con, $correction);
// mysqli_query($con, $alterDuration);
// mysqli_query($con, $alterTrainingDates);
// mysqli_query($con, $alterTrainingDates2);
$newAlterArray = [
    [
        "table" => "staff",
        "column" => "staff_code",
        "query" => "ALTER TABLE
  staff
ADD
  COLUMN staff_code VARCHAR(255) DEFAULT (
    CONCAT(
      'STAFF-',
      UPPER(SUBSTRING(MD5(UUID()), 1, 8))
    )
  );"
    ],
    [
        "table" => "staff",
        "column" => "code_status",
        "query" => "ALTER TABLE
  staff
ADD
  COLUMN code_status VARCHAR(255) DEFAULT 'Inactive'"
    ],
    [
        "table" => "event_orders",
        "column" => "referral_code",
        "query" => "ALTER TABLE
  event_orders
ADD
  COLUMN referral_code VARCHAR(255) DEFAULT ''"
    ],
    [
        "table" => "staff",
        "column" => "staff_code",
        "query" => "ALTER TABLE staff
                ADD COLUMN staff_code VARCHAR(255) NULL"
    ],
    [
        "table" => "admin",
        "column" => "staff_code",
        "query" => "ALTER TABLE admin
                ADD COLUMN staff_code VARCHAR(255) NULL"
    ],
    [
        "table" => "admin",
        "column" => "code_status",
        "query" => "ALTER TABLE admin
                ADD COLUMN code_status VARCHAR(255) NULL"
    ],
    [
        "table" => "bank_accounts",
        "column" => "service_type",
        "query" => "ALTER TABLE
  bank_accounts
ADD
  COLUMN service_type VARCHAR(255) NOT NULL DEFAULT '';
"
    ],
    [
        "table" => "event_orders",
        "column" => "referral_code",
        "query" => "ALTER TABLE
  event_orders
ADD
  COLUMN referral_code VARCHAR(255) NOT NULL DEFAULT '';
"
    ],

    [
        "table" => "refreshments",
        "column" => "item_category",
        "query" => "ALTER TABLE
  refreshments
ADD
  COLUMN item_category VARCHAR(255) NOT NULL DEFAULT '';
"
    ],
    [
        "table" => "refreshments",
        "column" => "discount_added",
        "query" => "ALTER TABLE
  refreshments
ADD
  COLUMN discount_added VARCHAR(255) NOT NULL DEFAULT '0';"
    ]
];
foreach ($newAlterArray as $NewOneAlter) {

    if (empty($NewOneAlter["query"]))
        continue;

    $table = $NewOneAlter["table"];
    $column = $NewOneAlter["column"];

    // 🚨 prevent crash if table doesn't exist
    if (!tableExists($con, $table))
        continue;

    if (!columnExists($con, $table, $column)) {
        mysqli_query($con, $NewOneAlter["query"]);
    }
}
// if (
//     tableExists($con, 'food_categories') &&
//     columnExists($con, 'food_categories', 'discounts')
// ) {
//     mysqli_query(
//         $con,
//         "ALTER TABLE food_categories DROP COLUMN discount"
//     );
// }
// include "../cron_reminder.php";
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
</div>

<?php if ($status == "superadmin") { ?>
    <div class="row mb-3">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Earnings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">&#8358; <?php echo $grandTotal; ?></div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-success mr-2"><a href="salesreport.php">View Sales Report</a></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- New User Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Royal Members</div>
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $count_mem; ?></div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-success mr-2"><a href="members.php">View All Members</a></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Repair Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $count_repairs; ?></div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-danger mr-2"><a href="repaircenter">Go To Repair Center</a></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Saloon Appointments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $count_services; ?></div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-danger mr-2"><a href="onlinebookings.php">View online bookings</a></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Inventory updates</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $unread_inv_log; ?></div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-danger mr-2"><a href="inventory_log_details.php">View logs</a></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Invoice Example -->
        <div class="col-xl-12 col-lg-12 mb-4">
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
                    <a class="m-0 float-right btn btn-secondary btn-sm" href="onlinebookings.php">View More <i
                            class="fas fa-chevron-right"></i></a>
                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>SN</th>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>View</th>
                                <th>Reciept</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            $sql = "SELECT  * FROM saloon_orders where section='spa' AND  pay_status='paid'  ORDER BY s DESC";
                            $sql2 = mysqli_query($con, $sql);
                            $i = 1;
                            while ($row = mysqli_fetch_array($sql2)) {

                                $status = $row['status'];

                                //color
                                if ($status == "no") {
                                    $bg = "badge-warning";
                                    $status = "booking";
                                } else if ($status == "processing") {
                                    $bg = "badge-primary";
                                } else if ($status == "cancelled") {
                                    $bg = "badge-danger";
                                } else if ($status == "processed" || $status == "completed") {
                                    $bg = "badge-success";
                                }


                                echo " 
               <tr>
                        <td> " . $i++ . " </td>
                        <td>" . $row['id'] . " </td>
                        <td>" . $row['name'] . " </td>
                        <td>&#8358; " . $row['total_amount'] . " </td>
                        <td><span class='badge $bg' style='text-transform:capitalize;'>$status</span></td>
                        <td><a href='viewbooking.php?order=" . $row['id'] . "' class='btn btn-sm btn-primary'> View Booking</a></td>
                        <td><a href='saloonreciept.php?order=" . $row['id'] . "' class='btn btn-sm btn-primary'> Print Receipt </a></td>	
                      </tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>

    <?php } else {

    $dateString = date("Y-m-d"); // Replace this with your date string
    $timestamp = strtotime($dateString);
    $dateInWords = date("F j, Y", $timestamp); // Example format: September 20, 2023



    ?>



        <!-- Invoice Example -->
        <div class="col-xl-12 col-lg-12 mb-4">
            <div class="card">
                <div class="card-header py-3 align-items-center justify-content-between" style="text-align:center;">
                    <h5>Hello there,<?php echo $name; ?> &#x1F60A; !</h5>
                    <p>Welcome to your dashboard or welcome abroad rather,whichever rocks your boat hehe<br>
                        Well,get to work!</p>
                    <div class="card-footer">Today is <?php echo $dateInWords; ?> </div>
                </div>
            </div>
        </div>




    <?php } ?>


    <?php include "latest_deductions.php"; ?>


    <?php
    include "footer.php";

    // mysqli_multi_query($con, file_get_contents("../alter.sql"));
    ?>