<?php
$re=$_POST['sub'];
include "connect_to_mysqli.php";
$sql = "SELECT * from foods where id='$re'";
$sql2 = mysqli_query($con,$sql);
if (mysqli_affected_rows($con) == 0)
			  {	echo "";} 
				
else{
session_start();
$_SESSION['ider']=$re;
echo header("location:pay.php");
}


?>