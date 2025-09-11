<?php

$ran = substr(mt_rand(), 0, 4);
session_start();
$_SESSION['godid'] ='DTK'.$ran;
echo header("location: deltakitchen.php");

?>