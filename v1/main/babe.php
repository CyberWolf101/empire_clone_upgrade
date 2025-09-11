<?php 
 $orid=$_POST['cate'];
 $ran=$_POST['idea'];
  $gift=$_POST['gift'];

session_start();
$_SESSION['hord']=$orid;
$_SESSION['iron']=$ran;
$_SESSION['gift']=$gift;

echo header("location:baby.php");

 ?>