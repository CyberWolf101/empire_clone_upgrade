<?php  ob_start(); session_start(); 
$ran=$_SESSION['ider'];
$orid=$_SESSION['hord'];
?>
<?php 
 	     if (isset($_POST['qua']))
{
 include "connect_to_mysqli.php";
 $see=$_POST['qua'];
 $insert = mysqli_query($con,"UPDATE trep SET no='$see' where id='$ran'") or die ('Could not connect: ' .mysqli_error($con));

session_start(); 
 $_SESSION['idea']=$ran;
 $_SESSION['hord']=$orid;
echo header("location:baby.php");
 }?>
 
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>ChbLuxuryEmpire - Booking Type</title>
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
		<p><b>BOOKING TYPE</b></p>
		<p>Select Your Prefered Booking Type</p>

			<div class="col-lg-12"> <p> <button type="submit" name="submit" value="1" class="btn-buya">SINGLE BOOKING</button>  </p> </div>
			   
			  <div class="col-lg-12">  <p> <button type="submit" name="submit" value="2" class="btn-buya">COUPLE BOOKING</button>  </p> </div>
			   
			<div class="col-lg-12">  <p> <button type="submit" name="submit" value="3" class="btn-buya">FAMILY BOOKING</button>  </p> </div>
			 </form>
                   </div>
		  

 		  
		  
		
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Hello there!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:#FFFFFF;"></button>
      </div>
      <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
        <p style="font-weight:600;">How many individuals would you like to book for?</p>
			<p style="color:black;" style="margin-bottom:30px;"> <form action="" method="post" >
			  <select class="form-control" name="qua">
			  <option selected="selected" value="">Select No of People</option>
			  <?php
    for ($i=3; $i<=100; $i++)
    {
        ?>
            <option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php
    }
?></select>
                <br><input type="submit" name="submit" value="Proceed" class="submitn"> </form></p>
            
      </div>
    </div>
  </div>
</div></div>		  
		  
		  
		  
		  
		  
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
<?php
include "connect_to_mysqli.php";
	     if (isset($_POST['submit']))
		 $sum=$_POST['submit'];
		 $name=$_POST['name'];
		  $mail=$_POST['mail'];
		   $mob=$_POST['mob'];
{

if($sum=="1")
{
    $submit = mysqli_query($con,"insert into trep(id,no) values ('$ran','1')") or die ('Could not connect: ' .mysqli_error($con));
 session_start(); 
 $_SESSION['idea']=$ran;
 $_SESSION['hord']=$orid;
echo header("location:baby.php");
}
else if($sum=="2")
{
 $submit = mysqli_query($con,"insert into trep(id,no) values ('$ran','2')") or die ('Could not connect: ' .mysqli_error($con));
session_start(); 
 $_SESSION['idea']=$ran;
 $_SESSION['hord']=$orid;
echo header("location:baby.php");
}

if($sum=="3")
{
 $submit = mysqli_query($con,"insert into trep(id,no) values ('$ran','')") or die ('Could not connect: ' .mysqli_error($con));
	echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#myModal").modal("show");
			});
		</script>';





}
}

?>
    </body>

</html>