<?php include"header.php";
$_SESSION['rent_id']=$_GET['rentid'];
header("location: rentprogress.php");
?>