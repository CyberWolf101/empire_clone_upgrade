<?php 
      include "connect_to_mysqli.php";
  
     if(isset($_POST['submit']))
	  {
	  $cname=  $_POST['name'];
	  $cmail =  $_POST['email'];
	  $cmob = $_POST['phone'];
	  $ran = $_POST['ran'];

//Update
$insert = mysqli_query($con,"UPDATE academy_cart SET name ='$cname' where id='$ran' ") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE academy_cart SET phone ='$cmob' where id='$ran' ") or die ('Could not connect: ' .mysqli_error($con)); 			
$insert = mysqli_query($con,"UPDATE academy_cart SET email ='$cmail' where id='$ran'") or die ('Could not connect: ' .mysqli_error($con));



session_start();
$_SESSION['idea']=$ran;
header("location:checkout.php");

}



?>