<?php  ob_start(); session_start(); 
$ran=$_SESSION['ider'];
?>
 
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>ChbLuxuryEmpire - Personal Details</title>
        <meta content="" name="description">
        <meta
        content="" name="keywords">

        <!-- Favicons -->
        <link href="../assets/img/favicon.png" rel="icon">
        <link
        href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="../assets/vendor/animate.css/animate.min.css" rel="stylesheet">
        <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
        <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
        <link
        href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

        <!-- Template Main CSS File -->
        <link href="../assets/css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/index.css">
        <!-- <link rel="stylesheet" href="styling.css"> -->
    </head>

    <body>
       
        <main>
         
		  <style>
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
  
}</style>
		
		  
		  <div style="margin-top:100px; color:#FFFFFF;">
		<div class="justify-content-center" align="center">
        <form action="" method="post">
		<p><b>PERSONAL DETAILS</b></p>
		<p> Submit your details to proceed</p>
<div class="col-lg-4">
	<p><input type="text" class="form-control" name="name" placeholder="Your Name.." required/></p>
	<p><input type="email" class="form-control" name="mail" placeholder="Your Email.."  required/></p>
	<p><input type="number" class="form-control" name="mob" placeholder="Your Mobile Number.."  required /></p>
	</div>
			<div class="col-lg-12"> <p> <button type="submit" name="submit" value="1" class="btn-buya">SUBMIT</button>  </p> </div>

			 </form>
                   </div>
		  
<?php
include "connect_to_mysqli.php";

	     if (isset($_POST['name'])){
		 $name=$_POST['name'];
		 $mail=$_POST['mail'];
		 $mob=$_POST['mob'];


	$got = "SELECT all* from trep where id='$ran'";
		$got2 = mysqli_query($con,$got);
			 while($gat = mysqli_fetch_array($got2))
				  {
				 $sum=$gat['no'];
				 }	 
$insert = mysqli_query($con,"UPDATE cart SET nom='$sum' where id='$ran'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE cart SET name='$name' where id='$ran'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE cart SET email='$mail' where id='$ran'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE cart SET phone='$mob' where id='$ran'") or die ('Could not connect: ' .mysqli_error($con));
 session_start(); 
 $_SESSION['idea']=$ran;
echo header("location:payss.php");


}



?>
 		  
		  
	  
		  
		  
		  
		  
		  
		  </div>
		   
        </main>
        <!-- End #main -->

        <!-- Vendor JS Files -->
        <script src="../assets/vendor/aos/aos.js"></script>
        <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
        <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
        <script src="../assets/vendor/waypoints/noframework.waypoints.js"></script>
        <script src="../assets/vendor/php-email-form/validate.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


        <!-- header.js -->
        <script src="../assets/js/header.js"></script>

        <!-- Type.js -->
        <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
        <script>
            var typed = new Typed('.type', {
                strings: [
                    "Tell someone the difference ^1500", "Experience Luxurious Services, Second To None ^1500", "Ever Been At CHB Luxury Empire ^1500"
                ],
                backspeed: 60,
                typespeed: 60,
                loop: true
            });
        </script>

        <!-- Particle.js -->
        <script src="../assets/js/particles.js"></script>
        <script src="../assets/js/app.js"></script>

        <!-- Template Main JS File -->
        <script src="../assets/js/main.js"></script>

    </body>

</html>
