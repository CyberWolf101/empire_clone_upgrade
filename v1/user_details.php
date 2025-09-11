<?php include "connect_to_mysqli.php";
  
   if (isset($_POST['submit']))
	 {
	  $nam =  $_POST['name'];
	  $ord =  $_POST['email'];
	  $val = $_POST['phone'];
	  $dear = $_POST['dear'];
	  $ran = $_POST['ran'];

$submit = mysqli_query($con,"insert into foods(name,email,phone,id,app,status,date,meth) values ('$nam','$ord','$val','$ran','','','$dear','')") or die ('Could not connect: ' .mysqli_error($con));
session_start();
$_SESSION['ider']=$ran;
echo header("location:pay.php");

}



?>