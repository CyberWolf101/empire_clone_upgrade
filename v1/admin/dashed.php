<?php include"admin_checker1.php";
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
</head>

<body>

  <!-- ======= Mobile nav toggle button ======= -->
  <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>

  <!-- ======= Header ======= -->
  <header id="header">
  
      <nav class="nav-menu">
        <ul>
          <li class="active"><a href="dasher.php"><i class="bx bx-home"></i> <span>Home</span></a></li>
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
          <h2>All Bookings</h2>
          <p>Here are all the logs of everypaid item.Print receipts here</p>
        </div>
  	<?php 
		     include "connect_to_mysqli.php";
		     if (isset($_POST['submitt']))
{

$idee =  $_POST['idn']; 
$fa="Delivered";
$insert = mysqli_query($con,"UPDATE foods SET app= '$fa' where id='$idee' ") or die ('Could not connect: ' .mysqli_error($con)); 


					echo'<p style="color:#FFC700; text-align:center;">Delivered!</p>';
					}
					 
					 
					 ?>	
		 
<p><center><form action="" method="post"><input type="text" Placeholder="Booking ID" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p>

	   <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'  width='200px'>No</th>
					<th data-column-id='employee_name'  width='200px'>Booking ID</th>
					<th data-column-id='employee_name'  width='200px'>Client</th>
					<th data-column-id='employee_name'  width='200px'></th>
					<th data-column-id='employee_name'  width='200px'></th>
					
				
					
				</tr>
			</thead>
		  <?php
		  
	  
	include "connect_to_mysqli.php";
	  $sa =$_POST['kayd'];
	 
	 
	    if((!$sa))
	  {
		   
			 $sql = "SELECT DISTINCT id,name,app from foods where status='Paid' ORDER BY date DESC";
		  $sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {

$ap=$row['app'];
   if($ap=='Confirmed')
 {
   $coke="<a href='#delete" . $row['id'] . "' data-toggle='modal'  ><button class='submitn'>Delivered</button></a>";
}
else
{
$coke='';

}
echo"

<tr bgcolor='#fff'><td width='200px' >" . $i++ . "</td><td width='200px'>" . $row['id'] . "</td>
<td width='200px' >" . $row['name'] . "</td>
<td>".$coke."</td>	
<td><form action='recep.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='submit' name='submin' value='Print Receipt' class='submitn' ></form></td>	
</tr>
</tr>

	<div class='modal fade' id='delete". $row['id'] ."' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete Category?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Ready to Deliver to (" . $row['name'] . ")?!</p>
	    <p><form action='' method='post' >
        <input type='text' name='idn' value='" . $row['id'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin'>Yes</button></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
          </div>
      </div>
    </div>
  </div>
 </div> 
</div>
               ";  
				
}}

else
{
	$sql = "SELECT DISTINCT id,name from horder where id='$sa' && status ='Paid'";
		  $sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo"

<tr bgcolor='#fff'><td width='200px' >" . $i++ . "</td><td width='200px'>" . $row['id'] . "</td>
<td width='200px' >" . $row['name'] . "</td>
<td>".$coke."</td>	
<td><form action='recep.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='submit' name='submin' value='Print Receipt' class='submitn' ></form></td>	
</tr>
</tr>
	<div class='modal fade' id='delete". $row['id'] ."' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete Category?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Ready to Deliver to (" . $row['name'] . ")?!</p>
	    <p><form action='' method='post' >
        <input type='text' name='idn' value='" . $row['id'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin'>Yes</button></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
          </div>
      </div>
    </div>
  </div>
 </div> 
</div>
               ";  
				
}
}
				   
					
	
	  
	 
		 
	
	  ?> 
	  </table></div>
	  </p>
      

      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

 <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/typed.js/typed.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>
</html>