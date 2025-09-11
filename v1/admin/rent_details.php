<?php
include "header.php";
$rent=$_POST['rent_id'];
$_SESSION['rent_id']=$rent;
header("location: rentdetails.php");
?>
