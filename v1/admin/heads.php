<?php ob_start(); session_start(); 
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
					                      $admin_status = $row['status'];
					  
					  
					  
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


	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" >
  
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
#sidebar {
  padding-top: 15px;
 
}

#sidebar * {
  margin: 0;
  padding: 10;
  list-style: none;
}
#sidebar > ul > li {
  position: relative;
  white-space: nowrap;
}
#sidebar a {
  display: block;
  align-items: center;
  color: #a8a9b4;
  padding: 4px;
  margin-bottom: 2px;
  transition: 0.3s;
  font-size: 14px;
}
#sidebar a i {
  font-size: 11px;
  padding-right: 8px;
  color: #6f7180;
}
#sidebar a:hover, #sidebar .active > a, #sidebar li:hover > a {
  text-decoration: none;
  color: #fff;
}

#sidebar a:hover i, #sidebar .active > a i, #sidebar li:hover > a i {
  color: #149ddd;
}





a[data-toggle="collapse"] {
    position: relative;
}




.collapse 
{
    background-color: #ffffff;
}
.btn
{
    color:white;
    font-size:14px;
}
.dropdown-container {
 
  background-color: #262626;
  padding-left: 8px;
}
</style>

  <!-- ======= Mobile nav toggle button ======= -->
  <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>

  <!-- ======= Header ======= -->
  <header id="header">
  
     <nav id="sidebar">
           
            <ul class="list-unstyled components">
              
                <li class="active">
                    <a href="dashboard.php" data-toggle="collapse" aria-expanded="false"><i class="bx bx-home"></i> <span>Home</span></a></li>
                </li>
                 <li>
                    <a href="#staffSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="bx bx-server"></i>Staff</a>
                    <ul class="collapse list-unstyled" id="staffSubmenu">
                       <li><a href="staffs.php"><i class="bx bx-user"></i><span>Staff</span></a></li>
       <li><a href="staff.php"><i class="bx bx-envelope"></i>Register Staff</a></li>
         <li><a href="subadmin.php"><i class="bx bx-envelope"></i>Sub-Admin</a></li>
                    </ul>
                </li>
                  <li>
                    <a href="#saloonSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="bx bx-server"></i>Saloon and spa</a>
                    <ul class="collapse list-unstyled" id="saloonSubmenu">
                      <li><a href="service.php"><i class="bx bx-server"></i>All Services</a></li>
	   <li><a href="services.php"><i class="bx bx-server"></i>Add Services</a></li>
	     <li><a href="category.php"><i class="bx bx-server"></i>Categories</a></li>
     	<li><a href="subs.php"><i class="bx bx-server"></i>Add Sub Categories</a></li> 
	   <li><a href="walkindirect.php"><i class="bx bx-server"></i>Walk-in Booking</a></li>  
                    </ul>
                </li>
                 <li>
                    <a href="#RepairSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="bx bx-server"></i>Repair center</a>
                    <ul class="collapse list-unstyled" id="RepairSubmenu">
                     <li><a href="repairs.php"><i class="bx bx-server"></i>All Requests</a></li>
       <li><a href="request_history.php"><i class="bx bx-server"></i>Requests History</a></li>
       
	   <li><a href="add_repairs.php"><i class="bx bx-server"></i>Submit Requests</a></li>
                    </ul>
                </li>
                 <li>
                    <a href="#RentalSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="bx bx-server"></i>Rental center</a>
                    <ul class="collapse list-unstyled" id="RentalSubmenu">
                     <li><a href="rents.php"><i class="bx bx-server"></i>All Rentals</a></li>
       <li><a href="renthistory.php"><i class="bx bx-server"></i>Rental History</a></li>
       <li><a href="rentrate.php"><i class="bx bx-server"></i>Rental Rate</a></li>
        <li><a href="submitrent.php"><i class="bx bx-server"></i>Submit Rents</a></li>
                    </ul>
                </li>
                 <li>
                    <a href="#CHBSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="bx bx-server"></i>CHB ACADEMY</a>
                    <ul class="collapse list-unstyled" id="CHBSubmenu">
                      <li><a><form action='editcategory.php' method='post'>
		<input type='text' name='ordid' value='0012' required hidden> 
		<button type="submit" name="submin" class="zero">
	    <i class="bx bx-server"></i>CHB Luxury Academy</button></form></a></li> 
	   <li><a href="academy.php"><i class="bx bx-server"></i>Sub Categories</a></li>
	   <li><a href="durations.php"><i class="bx bx-server"></i>Durations</a></li>
	   <li><a href="users_academy.php"><i class="bx bx-server"></i>All Registerations</a></li>
                    </ul>
                </li>
                   <li>
                    <a href="OrishirishiSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="bx bx-server"></i>Orishirishi </a>
                    <ul class="collapse list-unstyled" id="OrishirishiSubmenu">
                      <li><a href="foods.php"><i class="bx bx-server"></i>All Orishirishi</a></li>      
        <li><a href="food.php"><i class="bx bx-file-blank"></i> <span>Add Orishirishi</span></a></li>      
        <li><a href="fooder.php"><i class="bx bx-book-content"></i>Orishirishi Online Order</a></li>
        <li><a href="item.php"><i class="bx bx-server"></i>Orishirishi Store Order</a></li>
                    </ul>
                </li>
                   <li>
                    <a href="DeltaSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="bx bx-server"></i>Delta Kitchen</a>
                    <ul class="collapse list-unstyled" id="DeltaSubmenu">
                       <li><a><form action='editcategory.php' method='post'>
		<input type='text' name='ordid' value='0015' required hidden> 
		<button type="submit" name="submin" class="zero">
	    <i class="bx bx-server"></i>  Delta Kitchen</button></form></a></li> 
		
        <li><a href="delta_kitchen.php"><i class="bx bx-server"></i>Sub Categories</a></li>    
        <li><a href="delta_meals.php"><i class="bx bx-server"></i>Delta Kitchen Menu</a></li>     
        <li><a href="delta_sides.php"><i class="bx bx-server"></i>Additional Meals</a></li> 
        <li><a href="delta_addmeal.php"><i class="bx bx-file-blank"></i><span>Add Protein </span></a></li>
        <li><a href="deltaorders.php"><i class="bx bx-file-blank"></i><span>Delta Kitchen Orders</span></a></li> 
                    </ul>
                </li>
                <li>
                    <a href="MemberSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="bx bx-server"></i>Membership</a>
                    <ul class="collapse list-unstyled" id="MemberSubmenu">
                      <li><a href="pack.php"><i class="bx bx-server"></i>Packages</a></li>
	     <li><a href="mem.php"><i class="bx bx-server"></i>Membership</a></li>
                    </ul>
                </li>
                 
                	<li><a href="rew.php"><i class="bx bx-server"></i>Reviews</a></li>
     	<li><a href="custom.php"><i class="bx bx-server"></i>Customers List</a></li>
    	<li><a href="port.php"><i class="bx bx-server"></i>Sales Report</a></li>
    		<li><a href="portsen.php"><i class="bx bx-server"></i>Expense Report</a></li>
    	<li><a href="pass.php"><i class="bx bx-server"></i>Change Passsword</a></li>
  		<li><a href="logout.php"><i class="bx bx-user"></i> <span>Log Out</span></a></li>

            </ul>
        </nav>

      <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>

    </div>
  </header><!-- End Header -->
  
  
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
font-weight:700;

}
.img{
max-width:30%;
max-height:30%;
border-radius:50%;
}
.btn-buya {
  display: inline-block;
  padding:6px;
  border:none;
  color: #fff;
  font-size: 13px;
  text-transform:uppercase;
  font-family: "Poppins", sans-serif;
  font-weight: 800;
  transition: 0.3s;
  background:#FEBF01;
  
}

#clocs
{
display:none;}

#cloch{
display:none;
}


.big {
    display:block;
    width:100px;
    height:100px;
    background-color:red;
    cursor:pointer;
}

.hli {
    border:2px solid blue;   
}

.cart_div p{
margin-bottom:5px;    
}
</style>
