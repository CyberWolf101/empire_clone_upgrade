<?php
	include "connect_to_mysqli.php";
    $koy=$_POST['ordid'];
    $insert = mysqli_query($con,"UPDATE cart SET status= 'UnPaid' where id='$koy'") or die ('Could not connect: ' .mysqli_error($con)); 
					
	$insert = mysqli_query($con,"UPDATE cart SET app= 'None' where id='$koy'") or die ('Could not connect: ' .mysqli_error($con));  
session_destroy();
echo header ("location:dasher.php");
 
?>