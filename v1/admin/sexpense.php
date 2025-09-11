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

  <title>Stock - Chbluxuryempire</title>
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
</head>
<body>
<style>
.dropdown-menu a
{
 color:black;   
}
.btn
{
    color:white;
    font-size:14px;
}
</style>
  <!-- ======= Mobile nav toggle button ======= -->
  <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>

  <!-- ======= Header ======= -->
  <header id="header">
  
      <nav class="nav-menu">
        <ul>
          <li class="active"><a href="ford.php"><i class="bx bx-home"></i> <span>Home</span></a></li>
          <li><a href="sexpense.php"><i class="bx bx-home"></i> <span>Log Expense</span></a></li>
          <li><a href="logout.php"><i class="bx bx-user"></i> <span>Log Out</span></a></li>
          
        </ul>
      </nav><!-- .nav-menu -->
      <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>

    </div>
  </header><!-- End Header -->
 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Log Expense</h2>
        </div>
        
        
<form method="post" enctype="multipart/form-data" style="text-align:left; text-transform:uppercase;">
    <p style="color:blue;"><?php include"pener.php"; ?></p>
<div class="col-lg-12" style="color:#FF339A;"><?php include"fordds.php" ?></div>
<p><input type="text" class="form-control"  placeholder="*Title" name="tit" required  /></p>
<p><input type="number" class="form-control"  placeholder="*Amount" name="car" required  /></p>
<p><textarea class="form-control"    placeholder="*Description" name="de" required></textarea></p>
	    <input type="text" class="form-control" name="dat" id="currentDate"  readonly hidden  required />
	<script>
	var now = new Date(); 
    mo = now.getMonth() + 1;
    if (mo < 10) {
        mo = "0" + mo;
    }
    date = now.getDate();
    if (date < 10) {
            date = "0" + date;
        }
 
  var date = now.getFullYear() + '-' + mo + '-' + date;
  document.getElementById("currentDate").value = date;
</script>
	
  <input type="text" class="form-control" name="datr" id="currentDateTime"  readonly  hidden required />	
	
	<script>
  var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
var dateTime = time;
  document.getElementById("currentDateTime").value = dateTime;
</script>

			 <center> <input  type="submit" value="UPDATE" class="submitn" name="submit" /></center>
			  </form><br>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>