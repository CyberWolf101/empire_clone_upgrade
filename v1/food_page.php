<?php session_start();
$ran=substr(md5(mt_rand()), 0, 4);
$_SESSION['ider']=$ran;
header("location:foods.php");

?>
