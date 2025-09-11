<?php ob_start(); session_start(); include "connect_to_mysqli.php"; ?>
<!DOCTYPE html>
<html lang="en">

        <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Chbluxuryempire</title>
        <meta content="" name="description">
        <meta
        content="" name="keywords">

        <!-- Favicons -->
        <link href="assets/img/favicon.png" rel="icon">
        <link
        href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
        <link href="assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
        <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
        <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
         <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <!-- Template Main CSS File -->
        <link href="assets/css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/index.css">
        <!-- <link rel="stylesheet" href="styling.css"> -->
       </head>

    <body>
             <header class="header">
            <div class="d-flex justify-content-center" style="height:80vh;">
                <div class="menu" style="position: absolute; top: 10%; left: 50%; cursor: pointer;">
                    <i class='bx bx-x-circle' style="color: white;"></i>
                     </div>
                    <div style="position: absolute; top: 20%; height: 80vh;" class="soft">
                    <ul style="list-style:none; font-weight:600;">
				<style>
				    
				.menu_links{
				    color:#FFC700;
				    text-transform:uppercase !important;
				    
				}
				
					.menu_links:hover{
				    color:#fff;
				    text-transform:uppercase !important;
				    
				}
				#list{
				    display:none;
				}
				
				
#list li {
  margin-bottom: 0;
  line-height:0.3 !important;
font-family: 'Poppins';
}

				</style>	
                 <script>
                   function myFunctionlist() {
var myLists = document.getElementById('list');
var displaySettings = myLists.style.display;

   if (displaySettings == 'block') {
     
      myLists.style.display = 'none';
   
    }
    else {
  
      myLists.style.display = 'block';

    }
}
  </script>  
                     
                     
                 </script>
				<li><a href="#" onclick="myFunctionlist()" class="menu_links">Saloon and spa</a></li> <br>
				<div id="list">	<?php
        include "connect_to_mysqli.php";
        $sql = "SELECT all* from cater  where id!='0015' AND id!='0017' AND id!='0016' AND id!='0012'";
		$sql2 = mysqli_query($con,$sql);
	   while($row = mysqli_fetch_array($sql2))
				  {
     	echo'
      <li><form action="main/sub.php" method="post"><input type="hidden" value="'.$row['id'].'" name="cate" />
      <button type="submit" name="submit" value="submit" style="background:none; outline:none; border:none; color: #FFC700; font-size:12px;  text-transform:capitalize; font-weight:500;">'.$row['name'].'</button>
      </li></form><br>';
	}
	
	?></div>
				<li><a href="delta/index.php" class="menu_links">Delta Kitchen</a></li> <br>  
				<li><a href="food_page.php"   class="menu_links">ORISHIRISHI</a></li> <br>
			   <li><a href="academy/index.php"   class="menu_links">CHB LUXURY ACADEMY</a></li> <br>  
			  <li><a href="repaircenter.php"   class="menu_links">REPAIR CENTER</a></li> <br>  
               <li><a href="#"                class="menu_links">Ram suya Academy</a></li><br>
               <li><a href="https://chblogistics.com" class="menu_links">Chb Logistics</a></li><br>  
               <li><a href="https://chbluxuries.com"  class="menu_links">Chb Nailshop</a></li><br> 
               <li><a href="https://oshofree.ng"       class="menu_links">Oshofree</a></li><br> 
               
                 </ul>
                </div>
    
            </div>
        </header>