<?php
include "header.php";
$repair=$_GET['repair_id'];
$_SESSION['repair_id']=$repair;
header("location: repairdetails2.php");
?>
