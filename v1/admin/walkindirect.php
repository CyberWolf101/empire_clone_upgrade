<?php

$ran = substr(mt_rand(), 0, 8);
session_start();
$_SESSION['godid'] =$ran;
echo header("location:walkindata.php");

?>