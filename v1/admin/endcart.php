<?php
	include "header.php";
    $id=$_POST['ordid'];
    $insert = mysqli_query($con,"UPDATE cart SET status= 'UnPaid' where id='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
	$insert = mysqli_query($con,"UPDATE cart SET app= 'None' where id='$id'") or die ('Could not connect: ' .mysqli_error($con));  
session_destroy();
echo header ("location:dashboard.php");
 
?>