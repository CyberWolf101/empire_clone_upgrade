<?php include "header.php";
if (isset($_GET['category'])){
$category=$_GET['category'];
}?>

 
        <div style="margin-top:100px; color:#FFFFFF;">
		<div class="justify-content-center" align="center">
        <form action="" method="post">
		<p><b>BOOKING TYPE</b></p>
		<p>Select Your Prefered Booking Type</p>
        <div class="col-lg-12"><p> <button type="submit" name="submit" value="1" class="btn-buya">SINGLE BOOKING (1 Individual)</button></p></div>
	    <div class="col-lg-12"><p> <button type="submit" name="submit" value="2" class="btn-buya">COUPLE BOOKING (2 Individuals)</button></p></div></form>
		<div class="col-lg-12"><p> <button type="button"  data-bs-toggle="modal" data-bs-target="#myModal" class="btn-buya">FAMILY BOOKING (3-5 Individuals)</button></p></div>
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
      <p style="font-weight:600;">How many individuals would you like to book for?(maximum of 5)</p>
	  <p style="color:black;" style="margin-bottom:30px;"> <form action="" method="post" >
	  <select class="form-control" name="people" required>
	 <option selected="selected" value="">Select No of People</option>
	 <?php for ($i=3; $i<=5; $i++){?>
     <option value="<?php echo $i;?>"><?php echo $i;?></option><?php } ?></select>
     <br><input type="submit" name="submit" value="Proceed" class="submitn"> </form></p>
     </div>
    </div>
  </div>
</div></div></div>


<?php
if (isset($_POST['submit'])){
$sum=$_POST['submit'];
$people=$_POST['people'];

if($sum==""){ $sum=$people;}
$insert = mysqli_query($con,"UPDATE saloon_orders SET bookingtype='$sum' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
echo "<script>alert('Booking type set successfully!');</script>";
header("refresh:0; url=services.php?category=$category");

}






include"footer.php";  ?>