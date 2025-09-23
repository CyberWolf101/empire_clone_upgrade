<?php 
include "header.php";


//check if cookie exists
if (isset($_COOKIE['bookingID'])){
        $ran = $_COOKIE['bookingID']; 
        
$sql = "SELECT * from saloon_orders where id='$ran' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$type=$row["bookingtype"]; 
 $statuspay=$row["pay_status"];
}

if($statuspay==""){
        $_SESSION['booking']=$ran;
        header("location:booking.php");
}
else{
$ran=substr(md5(mt_rand()), 0, 6);
$date=date("Y-m-d");


$submit = mysqli_query($con,"insert into saloon_orders(id,name,email,phone,bookingtype,method,pay_status,status,date,saloonkit,total_amount,card_amount,cash_amount,transfer_amount,pos_amount,type,section,preorder,preorder_date)
values ('$ran','','','','','','','','$date','','','','','','','store','spa','0','')") or die ('Could not connect: ' .mysqli_error($con));

//setcookie
setcookie("bookingID", $ran, time() + (10 * 365 * 24 * 60 * 60));


$_SESSION['booking']=$ran;
header("location:booking.php");
}}
    
else{
$ran=substr(md5(mt_rand()), 0, 6);
$date=date("Y-m-d");


$submit = mysqli_query($con,"insert into saloon_orders(id,name,email,phone,bookingtype,method,pay_status,status,date,saloonkit,total_amount,card_amount,cash_amount,transfer_amount,pos_amount,type,section,preorder,preorder_date,giftcard, gift_amount)
values ('$ran','','','',0,'','','','$date',0,0,0,0,0,0,'store','spa','0','',0,0)") or die ('Could not connect: ' .mysqli_error($con));

//setcookie
setcookie("bookingID", $ran, time() + (10 * 365 * 24 * 60 * 60));


$_SESSION['booking']=$ran;
header("location:booking.php");
}




?>

