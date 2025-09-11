<?php include "header.php";
if($username!=""){echo header("location:cart.php");} 
?>

<div style="margin-top:100px; color:#FFFFFF;">
<div class="justify-content-center" align="center">
<form action="" method="post">
<p><b>PERSONAL DETAILS</b></p>
<p> Submit your details to proceed</p>
<div class="col-lg-4">
	<p><input type="text" class="form-control" name="name" placeholder="Your Name.." required/></p>
	<p><input type="email" class="form-control" name="email" placeholder="Your Email.."  required/></p>
	<p><input type="number" class="form-control" name="mobile" placeholder="Your Mobile Number.."  required /></p>
	</div>
<div class="col-lg-12"> <p> <button type="submit" name="submitdetails" value="1" class="btn-buya">SUBMIT</button>  </p> </div>
</form></div>




















<?php


if (isset($_POST['submitdetails'])){
		 $name=$_POST['name'];
		 $mail=$_POST['email'];
		 $mob=$_POST['mobile'];  
		 

$insert = mysqli_query($con,"UPDATE saloon_orders SET name='$name' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE saloon_orders SET email='$mail' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE saloon_orders SET phone='$mob' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con));

echo"<script>alert('Personal details uploaded successfully!');</script>";
header("Refresh: 0; url=cart.php");
}
include "footer.php"; ?>