<?php ob_start();
session_start();
 include"admin_checker1.php";
	  include "connect_to_mysqli.php";
	  $code = $password="" ;
	  $code = $_SESSION['user'];
	  $password =  $_SESSION['pass'];
	  $check = "select * from admob where email = '".$code."' && pass = '".$password."'";
	  $query = mysqli_query($con,$check);
	     if (mysqli_affected_rows($con) == 0)
		   {
		      echo "<div class='era'><center>Authentication failed</center><br><br>";
			  echo "<center><a href = '../index.php'><font color='red'>LOGIN AGAIN</font></a></center></div>";
		   }
	     else
		   {
		    $sql = "SELECT * from admob where email = '".$code."'  ";
			
		   $sql2 = mysqli_query($con,$sql);
			  
			   while($row = mysqli_fetch_array($sql2))
				    
					{
										  $id = $row["s"];   					
					  $name = $row['Name'];
					   $email = $row['email'];
					  
					  
					  
					  }
					  }
					  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin - Chbluxuryempire</title>
  <meta content="" name="description">
  <meta content="" name="kords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">


  <!-- =======================================================
  * Template Name: iPortfolio - v2.0.0
  * Template URL: https://bootstrapmade.com/iportfolio-bootstrap-portfolio-websites-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/vendor/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="datatables.min.css"/>



</head>

<body>

  <!-- ======= Mobile nav toggle button ======= -->
  <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>

  <!-- ======= Header ======= -->
  <header id="header">
  
   <nav class="nav-menu">
        <ul>
      <li class="active"><a href="dasher.php"><i class="bx bx-home"></i> <span>Home</span></a></li>
      <li><a href="orde.php"><i class="bx bx-home"></i> <span>Orisirishi Store Order </span></a></li>
       <li><a href="delta_orders.php"><i class="bx bx-home"></i> <span>Delta Kitchen Orders</span></a></li>
      <li><a href="items.php"><i class="bx bx-book-content"></i>Store Receipt</a></li>
      <li><a href="online.php"><i class="bx bx-book-content"></i> Orishirishi Online Order</a></li>
      <li><a href="mains.php"><i class="bx bx-server"></i>Walk-in Booking</a></li>
      <li><a href="cutom.php"><i class="bx bx-book-content"></i>Customer List</a></li>
      <li><a href="memb.php"><i class="bx bx-book-content"></i>Members</a></li>
      <li><a href="logout.php"><i class="bx bx-user"></i> <span>Log Out</span></a></li>
          

        </ul>
      </nav><!-- .nav-menu -->
      <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>

    </div>
  </header><!-- End Header -->
 
<script>
    $(document).ready(function() {
    $('#myTable').DataTable();
} );
</script>