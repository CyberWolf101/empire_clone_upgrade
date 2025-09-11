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
          <h2>Food and Drinks</h2>
          <p>Delete and Edit items</p>
        </div>

<p><center><form action="" method="post"><input type="text" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p>
	   <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
					
					<th data-column-id='employee_name'  width='200px'>Name</th>
				   <th data-column-id='employee_salary'  width='200px'>In-Stock</th>
					<th data-column-id='employee_salary'  width='200px'></th>
				
					
				</tr>
			</thead>
		  <?php
		  
	 	include "connect_to_mysqli.php";
	  $sa =  $_POST['kayd'];
	 
	 
	    if((!$sa))
	  {
		   
			 $sql = "SELECT * from food ORDER BY name ASC LIMIT 1000";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {




echo "










<tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td><td width='200px'>" . $row['nom'] . "</td>
            
             <td><form action='fordd.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden/>  
                <input type='submit' name='submit' value='Add Stock' class='submitn'/  ></form></td>
		    
</tr>
";
				
}}
else
{
    
		$sql = "SELECT all* from food WHERE name LIKE '%".$sa."%' ORDER By name DESC LIMIT 1000";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {




echo "










<tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td><td width='200px'>" . $row['nom'] . "</td>
            
             <td><form action='fordd.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden/>  
                <input type='submit' name='submit' value='Add Stock' class='submitn'/  ></form></td>
		    
</tr>
";
    
    
    
}   
}




				   
					
	
	  
	 
		 
	
	  ?> 
	  </table></div>
	  </p>
      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>