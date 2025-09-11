<?php
	include "connect_to_mysqli.php";
    $koy=$_POST['ordid'];
    $insert = mysqli_query($con,"UPDATE foods SET status= 'UnPaid' where id='$koy'") or die ('Could not connect: ' .mysqli_error($con)); 
	$insert = mysqli_query($con,"UPDATE foods SET app= 'None' where id='$koy'") or die ('Could not connect: ' .mysqli_error($con));  
session_destroy();
echo header ("location:items.php");
 
?>