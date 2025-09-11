<?php 
include "connect_to_mysqli.php";
 $dear=$_POST['dear'];
 $baby=$_POST['baby'];
  $gift=$_POST['gift'];
 $ran=$_POST['idea'];
 $staff=$_POST['staff'];
  $raff=$_POST['rad'];
  $gene=$_POST['gene'];



$sqk = "SELECT all* from baby where id='$baby'";
		$sqlp = mysqli_query($con,$sqk);
			 while($rowe = mysqli_fetch_array($sqlp))
				  {
				 $see = $rowe['name'];
			   	 $per = $rowe['price'];
				 $ter = $rowe['time'];
				}
				
if ($staff=="random")
{
    $sqk = "SELECT all* from staff where id='$gene' && status='available'";
		$sqlp = mysqli_query($con,$sqk);
			 while($rowe = mysqli_fetch_array($sqlp))
				  {
				 $staff= $rowe['name'];}
}
else
{
    $staff=$staff;
}

 $submit = mysqli_query($con,"insert into cart(id, service, price, timef, timet, date, staff, status, time, nom, name, email, phone,app,meth) values 
 ('$ran','$see','$per','','','$dear','$staff','','$ter','','','','','','Card')") or die ('Could not connect: ' .mysqli_error($con));

// ped kit //
if($raff=="Yes")
{
$sql = "SELECT all* from kit where id='$ran' && name='$see' && date='$dear'";
		$sql2 = mysqli_query($con,$sql);
			 if (mysqli_affected_rows($con) == 0)
			 {
   $submit = mysqli_query($con,"insert into kit(id, name,date) values ('$ran','$see','$dear')") or die ('Could not connect: ' .mysqli_error($con));
}
else
{
echo "";
}
}


session_start();
$_SESSION['gift']=$gift;
$_SESSION['iron']=$ran;
$_SESSION['staff']=$staff;
$_SESSION['dear']=$dear;
$_SESSION['duration']=$ter;
$_SESSION['gene']=$gene;
$_SESSION['seen']=$see;
echo header("location:time.php");

 ?>
