<?phpob_start();
// session_save_path("/tmp");
// session_start();
session_save_path("sessions");

// Only start session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Release session lock early if you don't need to write anymore
session_write_close();
// <?php  ob_start(); session_start(); 




include "../connect.php"; 
date_default_timezone_set("Africa/Lagos");

if(isset($_COOKIE['rentalID'])) {
$saloon=$_COOKIE['rentalID'];
}

else{
$saloon=substr(md5(mt_rand()), 0, 6);
$date=date("Y-m-d");

$submit = mysqli_query($con,"insert into rentals(orderid,name,email,phone,days,dates,total_amount,status,paystatus,date)
values ('$saloon','','','','','','','','','$date')") or die ('Could not connect: ' .mysqli_error($con));
setcookie("rentalID", $saloon, time() + (10 * 365 * 24 * 60 * 60));
}


$sql = "SELECT * from rentals where orderid='$saloon' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
 $username=$row["name"];
 $c_phone=$row["phone"];
 $c_email=$row["email"];
 $status=$row["status"];
 $days=$row["days"];
 $alldates=$row["dates"];
 $paid=$row["paystatus"];
}




$sql = "SELECT * from site_settings";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
$apikey = $row["apikey"]; 
$sitemail = $row["sitemail"]; 
$sitename = $row["sitename"]; 
$kitprice = $row["pedicurekit"];
$rentprice = $row["rental"]; 
}



//services                         
$som = "SELECT sum(total) from rental_cart where id='$saloon'";
$som2 = mysqli_query($con,$som);
while($ros = mysqli_fetch_array($som2))
$total_cart=$ros[0]?: 0; 


//total services booked
$extrac= mysqli_query($con,"SELECT * from rental_cart where item='0' AND id='$saloon'");
$counts = mysqli_num_rows($extrac);
		            
$dateupdate=date("Y-m-d");		            
//Grand Total
$total_all=$total_cart;
$insert = mysqli_query($con,"UPDATE rentals SET total_amount='$total_all' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE rentals SET date='$dateupdate' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con)); 			

?>
<html lang="en">

  <head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>RENTALS - CHBLUXURYEMPIRE</title>
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




