<?php 
 $orid=$_POST['cate'];
 $ran=$_POST['idea'];

session_start();
$_SESSION['hord']=$orid;
$_SESSION['iron']=$ran;

echo header("location:menu.php");

 ?>