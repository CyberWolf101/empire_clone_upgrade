<?php include "header.php";
if($username!=""){echo header("location:cart.php");} 
$tomorrow = date("Y-m-d", strtotime("+1 day"));
?>




<style>
.btn-buya {
  display: inline-block;
  padding:6px !important;
  border:none;
  color: #fff;
  font-size: 12px !important;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
  font-weight: 700;
  transition: 0.3s;
  background:#FEBF01;
  width:100px;
  
}


.btn-buya:hover {
  display: inline-block;
  padding:6px !important;
  border:none;
  color: #fff;
  font-size: 12px !important;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
  font-weight: 800;
  transition: 0.3s;
  background:#FEBF01;
  width:100px;
  
}
</style>



<div style="margin-top:100px; color:#FFFFFF;">
<div class="justify-content-center" align="center">
<form action="" method="post">
<p><b>PERSONAL DETAILS</b></p>
<p> Submit your details to proceed</p>
<div class="col-lg-4">
	<p><input type="text" class="form-control" name="name" placeholder="Your Name.." required/></p>
	<p><input type="email" class="form-control" name="email" placeholder="Your Email.."  required/></p>
	<p><input type="number" class="form-control" name="mobile" placeholder="Your Mobile Number.."  required /></p>
	<?php if($preorder=="1"){ ?>
	<p style="text-align:center;"><span id="preorderdate"><label>Choose Delivery Date</label>
	<input type="date" class="form-control" min="<?php echo $tomorrow; ?>" name="preorderdate"  id="preorderdates" /></span></p><?php } ?>
    <p><button type="submit" name="submitdetails" value="1" class="btn-buya w-100">SUBMIT</button>  </p>	</div>
</form></div>


















<?php

$prd="";
$prddate="";
if (isset($_POST['submitdetails'])){
		 $name=$_POST['name'];
		 $mail=$_POST['email'];
		 $mob=$_POST['mobile'];  
		 $prddate=$_POST['preorderdate'];
		 

$insert = mysqli_query($con,"UPDATE saloon_orders SET name='$name' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE saloon_orders SET email='$mail' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE saloon_orders SET phone='$mob' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE saloon_orders SET preorder_date='$prddate' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con));

echo"<script>alert('Personal details uploaded successfully!');</script>";
header("Refresh: 0; url=cart.php");
}
include "footer.php"; ?>