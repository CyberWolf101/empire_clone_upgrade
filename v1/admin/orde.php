<?php session_start();
 include "connect_to_mysqli.php";

	 
$ran=substr(md5(mt_rand()), 0, 4);

session_start();
$_SESSION['ider']=$ran;
header("location:order.php");





?>
