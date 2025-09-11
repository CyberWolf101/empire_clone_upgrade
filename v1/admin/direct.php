<?php
session_start();
$_SESSION['godid'] =$ran;
echo header("location: payback.php");
?>
