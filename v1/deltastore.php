<?php session_start();

	 
$id=substr(md5(mt_rand()), 0, 4);
$_SESSION['order']=$id;
header("location:delta_store.php");

?>
