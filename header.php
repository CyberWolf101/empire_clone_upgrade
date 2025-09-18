<?php ob_start();
session_save_path("/tmp");
session_start();
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
$key = 'AIzaSyD6MS4bUOjkP0fYUklsVzIKYmGmb_MheGQ'

  // echo "Step 9 SUCCESS: Site settings loaded<br>";
?>



<?php include "headerContents.php"; ?>