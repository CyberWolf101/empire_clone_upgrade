<?php 
include "connect_to_mysqli.php";
 $dear=$_POST['dear'];
 $baby=$_POST['baby'];
 $ran=$_POST['idea'];
 $staff=$_POST['staff'];
 $raff=$_POST['rad'];
 $gene=$_POST['gene'];
 $sum=$_POST['submit'];


$sqk = "SELECT all* from baby where id='$baby'";
		$sqlp = mysqli_query($con,$sqk);
			 while($rowe = mysqli_fetch_array($sqlp))
				  {
				 $see = $rowe['name'];
			   	 $per = $rowe['price'];
				 $ter = $rowe['time'];
				}
				
$newtime = strtotime($gene) + ($ter * 60);
$tam= date('H:i', $newtime);

$submit = mysqli_query($con,"insert into cart(id, service, price, timef, timet, date, staff, status, time, nom, name, email, phone,app) 
values ('$ran','$see','$per','$gene','$tam','$dear','Random','','$ter','','','','','')") or die ('Could not connect: ' .mysqli_error($con));

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

if ($sum=="next")
{
session_start();
$_SESSION['ider']=$ran;
header('Refresh:3; url=types.php');
echo '<p style="color:green;font-size:13px; text-align:center;">Service added Successfully<br><i style="color:black;">you are now being redirected</i></p>';
}

else if ($sum=="addc"){
session_start();
$_SESSION['ider']=$ran;
header('Refresh:3; url=mores.php');
echo '<p style="color:green;font-size:13px; text-align:center;">Service added Successfully<br><i style="color:black;">you are now being redirected</i></p>';
}


 ?>
