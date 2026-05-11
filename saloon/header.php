<?php ob_start();
// session_save_path("/tmp");
// session_start();
session_save_path("../sessions");


// Only start session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Release session lock early if you don't need to write anymore
session_write_close();

// ✅ Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "../connect.php";
date_default_timezone_set("Africa/Lagos");

if (isset($_COOKIE['saloonID'])) {
  $saloon = $_COOKIE['saloonID'];
} else {
  $saloon = substr(md5(mt_rand()), 0, 6);
  $date = date("Y-m-d");

  // ✅ FIXED INSERT (columns now match values count)
$submit = mysqli_query($con, "INSERT INTO saloon_orders(
    id,
    name,
    email,
    phone,
    bookingtype,
    method,
    pay_status,
    status,
    date,
    saloonkit,
    total_amount,
    card_amount,
    cash_amount,
    transfer_amount,
    pos_amount,
    type,
    section,
    preorder,
    preorder_date,
    giftcard,
    gift_amount
) VALUES (
    '$saloon',
    '',
    '',
    '',
    0,          -- bookingtype
    '',
    '',
    '',
    '$date',
    0,          -- saloonkit
    0,          -- total_amount
    0,          -- card_amount
    0,          -- cash_amount
    0,          -- transfer_amount
    0,          -- pos_amount
    'online',
    'spa',
    0,          -- preorder
    '$date',    -- preorder_date now set to today's date
    0,          -- giftcard
    0           -- gift_amount
)") or die('Could not connect: ' . mysqli_error($con));

  setcookie("saloonID", $saloon, time() + (10 * 365 * 24 * 60 * 60));
}

// ✅ Debug check
// if (!$submit) {
//   die("Query failed: " . mysqli_error($con) . " | SQL: " . $sql);
// }

$sql = "SELECT * from site_settings";
$sql2 = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($sql2)) {
  $apikey = $row["apikey"];
  $sitemail = $row["sitemail"];
  $sitename = $row["sitename"];
  $kitprice = $row["pedicurekit"];
  $rentprice = $row["rental"];
  $late_fee = $row["latefee"];
  $siteimg = $row["site_img"];
  $walkinIncrease = $row["walk_in_fee"];
}

$sql = "SELECT * from saloon_orders where id='$saloon' ";
$sql2 = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($sql2)) {
  $type = $row["bookingtype"];
  $kit = $row["saloonkit"] ?? '';
  $username = $row["name"];
  $c_phone = $row["phone"];
  $c_email = $row["email"];
  $status = $row["status"];
}

//delete
$sql = "SELECT * FROM appointments WHERE id='$saloon' AND (start_time='' OR end_time='') ORDER BY s ASC";
$sql2 = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($sql2)) {
  $rowdelete = $row['s'];
  $del = mysqli_query($con, "DELETE from appointments where s='$rowdelete'") or die('Could not connect: ' . mysqli_error($con));
}

//services
$som = "SELECT sum(price) from appointments where id='$saloon'";
$som2 = mysqli_query($con, $som);
while ($ros = mysqli_fetch_array($som2))
  $total_services = $ros[0];

//refreshments
$sam = "SELECT sum(totalprice) from refreshments where orderid='$saloon' ";
$sam2 = mysqli_query($con, $sam);
while ($row = mysqli_fetch_array($sam2))
  $total_items = $row[0];

//total services booked
$extrac = mysqli_query($con, "SELECT * from appointments where id='$saloon'");
$count_services = mysqli_num_rows($extrac);

//Grand Total
$kit = (float)($row['saloonkit'] ?? 0);

$total_all = (float)$total_services + (float)$total_items + $kit;
// $total_all = $total_services + $total_items + $kit;
$insert = mysqli_query($con, "UPDATE saloon_orders SET total_amount= '$total_all' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Saloon & Spa - CHBLUXURYEMPIRE</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">


  <!-- include the jQuery library -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.js"></script>


  <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/vendor/jquery/jquery.min.js"></script>





  <!-- =======================================================
  * Template Name: Knight - v4.3.0
  * Template URL: https://bootstrapmade.com/knight-free-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
  <style>
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
      font-weight: 500;

    }

    .img {
      width: 30%;
      height: auto;
      border-radius: 50%;
    }

    .submitn {
      background: #FFC700;
      color: #fff;
      border-radius: 5px;
      padding: 10px;
      font-size: 10px;
      font-weight: 600;
      outline: none;
      border: none;

    }

    .submitn:hover {
      background: #000000;
      color: #fff;
      outline: none;
      border: none;
    }

    .btn-buya {
      display: inline-block;
      padding: 10px;
      border: none;
      color: #fff;
      text-align: center;
      font-size: 14px;
      text-transform: uppercase;
      font-family: 'Poppins', Open sans;
      font-weight: 800;
      background: #FFC700;
      margin-bottom: 20px;
      width: 300px;

    }

    .btn-buya:hover {
      display: inline-block;
      padding: 10px;
      border: none;
      color: #fff;
      text-align: center;
      font-size: 14px;
      text-transform: uppercase;
      font-family: 'Poppins', Open sans;
      font-weight: 800;
      background: #000000;
      margin-bottom: 20px;
      width: 300px;

    }

    .advert {
      background: #FFC700;
      width: 100%;
      height: 40px;
      font-weight: 800;
      font-size: 14px;
      color: #fff;
      padding: 10px;
    }

    #clocs {
      display: none;
    }

    #cloch {
      display: none;
    }
  </style>
  <div class="advert">
    <marquee direction="left">24hours booking and 24/7 service</marquee>
  </div>