<?php 
if(isset($_POST['submit'])){

 $service=$_POST['service'];
 $time=$_POST['time'];
 $date=$_POST['date'];
 $staff=$_POST['staff'];
 $ran=$_POST['id'];

//service details
$sqk = "SELECT all* from baby where id='$service'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
				 {
				 $see = $rowe['name'];
			   	 $per = $rowe['price'];
				 $ter = $rowe['time'];
				 }
				
				
				
				
				
				
$newtime = strtotime($time) + ($ter * 60);
$tam= date('H:i', $newtime);

$submit = mysqli_query($con,"insert into cart(id, service, price, timef, timet, date, staff, status, time, nom, name, email, phone,app) 
values ('$ran','$see','$per','$time','$tam','$date','$staff','','$ter','','','','','')") or die ('Could not connect: ' .mysqli_error($con));



echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
       Service added successfully!
       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';




}
 ?>
