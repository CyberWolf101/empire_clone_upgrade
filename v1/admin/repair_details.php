<?php
include "header.php";
$repair=$_POST['repair_id'];
$_SESSION['repair_id']=$repair;
header("location: repairdetails.php");df
?>
