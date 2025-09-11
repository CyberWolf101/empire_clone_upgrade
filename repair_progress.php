<?php include"header.php";
$_SESSION['repair_id']=$_GET['repairid'];
header("location: repairprogress.php");
?>