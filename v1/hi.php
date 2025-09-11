<?php 

$ran=$_POST['idea'];
	$tot=$_POST['tot'];
	$email=$_POST['emails'];
$cname=$_POST['first'];

session_start();
$_SESSION['hord']=$ran;
$_SESSION['tot']=$tot;
$_SESSION['emails']=$email;
$_SESSION['first']=$cname;
echo header("location:gift.php");


	?>
	
