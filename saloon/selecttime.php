<?php include "header.php"; 

$sql = "SELECT * from appointments where id='$saloon' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{ $selectedStaff=$row["staff"]; 
  $selectedStaffName=$row["staffname"]; 
  $selectedDate=$row["date"];
  $serviceDuration=$row["duration"];
  $thisservice=$row["s"];
  $serviceID=$row["service"];
  $originalPrice =$row["price"];
}

// First, retrieve the main_category and sub_category for the specified service
$sql = "SELECT s.sub_category, sc.main_category
        FROM services s
        INNER JOIN sub_category sc ON s.sub_category = sc.id
        WHERE s.id = '$serviceID'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $subCategory = $row['sub_category'];
    $mainCategory = $row['main_category'];
}

?>







<!-- ======= Pricing Section ======= -->
<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
<div class="container" style="width:100%; margin:auto; ">
<div class="row">
<div class="col-lg-12 col-md-12">
<div class="box" data-aos="zoom-in" data-aos-delay="100">
<p>
<form action="" method="post">
<table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
<thead><th>TIME</th><th></th></thead>
<tbody>
 
<tr class="ter mx-3" >
<td class="check">Available time</td>
<td class="check" colspan="2"><i>Click to select time slot</i><span>
<select name="time" class="form-control" required>
<?php include "fetchtime.php"; ?>
</select></td>
</tr>
</tbody>
</table></p>
	
	
		
			
			  
<div class="btn-wrap" style="text-align:center;">
<p style="color:red; font-size:15px; font-weight:500">Kindly note the following: *Same day bookings are available from 8am-6pm daily  
*for late night services such bookings must be done between booking hours so the staff can be notify before the time.
*services from 6:01pm-7.59.am has an extra service charge of <?php echo $late_fee; ?>%. *we offer 24/7 service but only availble when you book in advance. </p>
<p><button type="submit" name="submit" value="addcart" class="submitn">SELECT TIME AND PROCEED</button></p>
<p><button type="submit" name="submit" value="addcategory" class="submitn">SELECT AND ADD MORE FROM THIS CATEGORY</button></p>
<p><button type="submit" name="submit" value="addmore" class="submitn">SELECT AND ADD MORE FROM OTHER CATEGORY</button></p>
</form></div>




<?php
if (isset($_POST['submit'])){
$action=$_POST['submit']; 
$startTime=$_POST['time'];
$endTime = date('h:i A', strtotime($startTime) + ($serviceDuration * 60));


// Convert start and end times to timestamps
$startTimeStamp = strtotime($startTime);
$endTimeStamp = strtotime($endTime);

// Define time ranges
$range1Start = strtotime('12:00 AM');
$range1End = strtotime('7:59 AM');
$range2Start = strtotime('6:01 PM');
$range2End = strtotime('11:59 PM');



$late=0;
$latePrice=0;
$percentageIncrease=0;
if (($startTimeStamp >= $range1Start && $startTimeStamp <= $range1End) || ($endTimeStamp >= $range1Start && $endTimeStamp <= $range1End) || ($startTimeStamp >= $range2Start && $startTimeStamp <= $range2End) || ($endTimeStamp >= $range2Start && $endTimeStamp <= $range2End)) {
$late=1;
$percentageIncrease=$late_fee;
} 



// Calculate the new price after the percentage increase
$latePrice=$originalPrice * ($percentageIncrease / 100);
$newPrice = $originalPrice + ($originalPrice * ($percentageIncrease / 100));




$insert = mysqli_query($con,"UPDATE appointments SET start_time='$startTime' where s='$thisservice'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE appointments SET end_time='$endTime' where s='$thisservice'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE appointments SET lateservice='$late' where s='$thisservice'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE appointments SET latefee='$latePrice' where s='$thisservice'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE appointments SET price='$newPrice' where s='$thisservice'") or die ('Could not connect: ' .mysqli_error($con)); 
 			 

	
if($action=="addcart")
{
session_start();
echo"<script>alert('Service added successfully!');</script>";
header("Refresh: 0; url=user_details.php");
}

else if ($action=="addcategory"){
echo"<script>alert('Service added successfully!');</script>";
header("Refresh: 0; url=subcategory.php?category=$mainCategory");
}

else if ($action=="addmore"){
echo"<script>alert('Service added successfully!');</script>";
header("Refresh: 0; url=category.php");
}

}?> 






<!-- Modal 
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Hello there!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:#FFFFFF;"></button>
      </div>
      <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
      <p style="font-weight:600;">Sorry,<?php //echo $selectedStaffName; ?> is all booked for that day </p>
	  <p style="color:black;" style="margin-bottom:30px;"> <form action="" method="post" >
	  <select class="form-control" name="people" required>
	 <option selected="selected" value="">Select No of People</option>
	 <?php //for ($i=3; $i<=5; $i++){?>
     <option value="<?php // echo $i;?>"><?php // echo $i;?></option><?php // } ?></select>
     <br><input type="submit" name="submit" value="Proceed" class="submitn"> </form></p>
     </div>
    </div>
  </div>
</div></div></div>-->









<?php include "footer.php"; ?>