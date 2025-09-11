<?php

$ran = substr(mt_rand(), 0, 4);
session_start();
$_SESSION['godid'] ='ACA'.$ran;
echo header("location: academy.php");

?>