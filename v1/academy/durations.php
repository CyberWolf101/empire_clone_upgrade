<?php 
 $orid=$_POST['cate'];
 $ran=$_POST['idea'];

session_start();
$_SESSION['cate']=$orid;
$_SESSION['idea']=$ran;

echo header("location:duration.php");

 ?>