<?php 
include "header.php";
$ran=$_POST['idea'];
$duration=$_POST['duration'];
$date=date('Y-m-d');

$sql = "SELECT sub.name,baby.id,baby.price,baby.name as durations from baby JOIN sub ON baby.gen = sub.id
JOIN cater ON sub.gen = cater.id where baby.id='$duration' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
$name=$row["name"];
$price=$row["price"];
$time=$row["durations"];
						}

 $submit = mysqli_query($con,"insert into academy_cart(`id`,`training`, `duration`, `price`, `name`, `email`, `phone`, `date`, `status`) values 
 ('$ran','$name', '$time', '$price', '', '', '', '$date', 'unpaid')") or die ('Could not connect: ' .mysqli_error($con));




session_start();
$_SESSION['idea']=$ran;
echo header("location:checkout.php");

 ?>