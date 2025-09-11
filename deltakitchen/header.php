<?php 
ob_start(); 
session_save_path("/tmp"); 
session_start();

// Always show errors while debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- DATABASE CONNECTION ---
if (file_exists(__DIR__ . "/../connect.php")) {
    include __DIR__ . "/../connect.php";   // if script is in subfolder
} elseif (file_exists(__DIR__ . "/connect.php")) {
    include __DIR__ . "/connect.php";      // if script is in root
} else {
    die("❌ Database connection file not found");
}

// Set timezone
date_default_timezone_set("Africa/Lagos");

// --- COOKIE HANDLING ---
if (isset($_COOKIE['deltaID'])) {
    $saloon = $_COOKIE['deltaID'];
} else {
    $saloon = substr(md5(mt_rand()), 0, 6);
    $date = date("Y-m-d");

    // $query = "INSERT INTO saloon_orders 
    //     (id, name, email, phone, bookingtype, method, pay_status, status, date, saloonkit, total_amount, card_amount, cash_amount, transfer_amount, pos_amount, type, section, preorder, preorder_date) 
    //     VALUES ('$saloon','','','','','','','','$date','','','','','','','online','kitchen','0','')";
$query = "INSERT INTO saloon_orders 
(id, name, email, phone, bookingtype, method, pay_status, status, date, saloonkit, total_amount, card_amount, cash_amount, transfer_amount, pos_amount, type, section, preorder, preorder_date, giftcard) 
VALUES ('$saloon','','','','','','','','$date','','','','','','','online','kitchen','0','', 0)";

    // $submit = mysqli_query($con, $query);
    // if (!$submit) {
    //     die("❌ Insert failed: " . mysqli_error($con));
    // }
    $query = "INSERT INTO saloon_orders 
(id, name, email, phone, bookingtype, method, pay_status, status, date, saloonkit, total_amount, card_amount, cash_amount, transfer_amount, pos_amount, type, section, preorder, preorder_date, giftcard, gift_amount) 
VALUES ('$saloon','','','','','','','','$date','','','','','','','online','kitchen','0','', 0, 0)";


    setcookie("deltaID", $saloon, time() + (10 * 365 * 24 * 60 * 60));
}

// --- FETCH CURRENT SALOON ORDER ---
$sql = "SELECT * FROM saloon_orders WHERE id='$saloon'";
$sql2 = mysqli_query($con, $sql) or die("❌ Error fetching saloon order: " . mysqli_error($con));
$row = mysqli_fetch_assoc($sql2);

$type      = $row["bookingtype"] ?? '';
$kit       = $row["saloonkit"] ?? '';
$username  = $row["name"] ?? '';
$c_phone   = $row["phone"] ?? '';
$c_email   = $row["email"] ?? '';
$status    = $row["status"] ?? '';
$preorder  = $row["preorder"] ?? '';

// --- SITE SETTINGS ---
$sql = "SELECT * FROM site_settings LIMIT 1";
$sql2 = mysqli_query($con, $sql) or die("❌ Error fetching site settings: " . mysqli_error($con));
$row = mysqli_fetch_assoc($sql2);

$apikey   = $row["apikey"] ?? '';
$sitemail = $row["sitemail"] ?? '';
$sitename = $row["sitename"] ?? '';
$siteimg  = $row["site_img"] ?? '';
$kitprice = $row["pedicurekit"] ?? '';

// --- CART TOTAL ---
$som = "SELECT SUM(totalprice) as total FROM delta_cart WHERE id='$saloon'";
$som2 = mysqli_query($con, $som) or die("❌ Error fetching cart sum: " . mysqli_error($con));
$ros = mysqli_fetch_assoc($som2);
$total_cart = $ros["total"] ?? 0;

// --- COUNT CART ITEMS ---
$extrac = mysqli_query($con, "SELECT * FROM delta_cart WHERE id='$saloon'") or die("❌ Error fetching cart items: " . mysqli_error($con));
$count_all = mysqli_num_rows($extrac);

// --- GRAND TOTAL UPDATE ---
$total_all = $total_cart;
$update = mysqli_query($con, "UPDATE saloon_orders SET total_amount='$total_all' WHERE id='$saloon'");
if (!$update) {
    die("❌ Update failed: " . mysqli_error($con));
}
?>
<html lang="en">

  <head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Delta KItchen - CHBLUXURYEMPIRE</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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
.ter{
background-color:#fff;
padding:0 10px;
}
.check{
padding:2%;
font-size:12px;
width:25%;
}
.check span{

font-size:13px;
font-weight:500;

}
.img{
width:30%;
height:auto;
border-radius:50%;
}
.submitn{
  background: #FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px;
  font-size: 10px;
  font-weight: 600;
  outline:none;
  border:none;
 
}

.submitn:hover{
  background: #000000;
  color: #fff;
  outline:none;
  border:none;
}

		  .btn-buya {
  display: inline-block;
  padding:10px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#FFC700;
  margin-bottom:20px;
  width:300px;
  
}
	  .btn-buya:hover {
  display: inline-block;
  padding:10px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#000000;
  margin-bottom:20px;
  width:300px;
  
}

.advert{
background:#FFC700;
width:100%;
height:40px;
font-weight: 800;
font-size:14px;
color:#fff;
padding:10px;
}
#clocs
{
display:none;}

#cloch{
display:none;
}
</style>




