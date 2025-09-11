<?php 
 $orid=$_POST['ord'];


session_start();
$_SESSION['ider']=$orid;


echo header("location:feeds.php");

 ?>