<?php
session_start();
$meal=$_POST['ordid'];

$_SESSION['meal']=$meal;
header("location: addmeals.php");
?>