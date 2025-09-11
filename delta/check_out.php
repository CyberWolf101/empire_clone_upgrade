<?php 

$ran=$_POST['idea'];

session_start();
$_SESSION['idea']=$ran;

echo header("location:checkout.php");

 ?>