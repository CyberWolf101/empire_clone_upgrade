<?php include "header.php";

/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $del = mysqli_query($con,"DELETE from appointments where s='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script> window.location.href = 'booking.php';</script>";
    exit(); // Make sure to exit the script after the alert
}

if (isset($_COOKIE['bookingID'])){
$saloon=$_COOKIE['bookingID'];
$sql = "SELECT * from saloon_orders where id='$saloon' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$type=$row["bookingtype"]; 
 $kit=$row["saloonkit"];
 $username=$row["name"];
}}

else{header("location:bookings.php");}
$today=date("Y-m-d");

?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
             <h1 class="h5 mb-0 text-gray-800">Booking ID #<?php echo $saloon;?></h1>
             <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Start Booking</li>
            </ol>
          </div>
          
           <!-- Row -->
          <div class="row">          
    
    

<?php include "servicecart.php"; ?>    
          
<div align="center" class="col-lg-12">
<form  method="post" style="width:100%; margin:auto; text-align:left;">
<div class="form-group">
<select class="select2-single-placeholder form-control" name="service" style="width:100%;" id="select2SinglePlaceholder">
<option value="">- Select Service -</option>
<?php 
$sql = "select id,name,duration from services";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
echo'<option value="'.$row['id'].'">'.$row['name'].'('.$row['duration'].'mins)</option>';
}
?>                     
</select></div>


<div class="form-group">
<select class="select2-single-placeholder form-control" style="width:100%;" name="staff" >
<option value="">- Select Staff -</option>
<?php 
$sql = "SELECT * from staff ORDER By name";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2)){
echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
}
?>                     
</select></div>  


<p>
<input type="date" class="form-control" name="date" value="<?php echo $today; ?>" required /><br>
<input type="time" class="form-control" name="time" required />
</p>

<input type='submit' name='save' value='Book Service' class='btn btn-primary' ></form>	
</div>
          
          
          
<?php 

if(isset($_POST['save'])){
$service=$_POST['service'];
$date=$_POST['date'];
$staff=$_POST['staff'];
$inputTime=$_POST['time'];

//service details
$sqk = "SELECT * from services where id='$service'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
{
$servicename = $rowe['name'];
$price = $rowe['price'];
$duration = $rowe['duration'];
}




// Convert input time to a timestamp
$startTime = strtotime($inputTime);
$endTime = $startTime + ($duration * 60);
$formattedStartTime = date('h:i A', $startTime);
$formattedEndTime = date('h:i A', $endTime);


//service details
$sqk = "SELECT * from staff where id='$staff'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
{
$staffname = $rowe['name'];
}

//random staff				
if ($staff=="random"){
$sqk = "SELECT * from staff where section='$category' && status='available'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
{ $staff= $rowe['name'];}}



$submit = mysqli_query($con,"insert into appointments(id, service,servicename, price,duration ,start_time, end_time, date,staff,staffname,lateservice,latefee,status) values 
('$saloon','$service','$servicename','$price','$duration','$formattedStartTime','$formattedEndTime','$date','$staff','$staffname','0','0','processing')") or die ('Could not connect: ' .mysqli_error($con));
header("location:booking.php");
}


include "footer.php"; ?>