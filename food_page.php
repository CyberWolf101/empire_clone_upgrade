<?php 
include "header.php";


//check if cookie exists
if     (isset($_COOKIE['foodID'])){
        $ran = $_COOKIE['foodID'];  
        $_SESSION['order']=$ran;
        header("location:foodpage.php");
}   
    
else{
$ran=substr(md5(mt_rand()), 0, 6);
$date=date("Y-m-d");


$submit = mysqli_query($con,"INSERT INTO saloon_orders(
    id,name,email,phone,bookingtype,method,pay_status,status,date,
    saloonkit,total_amount,card_amount,cash_amount,transfer_amount,pos_amount,
    type,section,preorder,preorder_date,giftcard,gift_amount
) VALUES (
    '$ran','','',0,0,'','','','$date',0,0,0,0,0,0,
    'online','refreshments',0,'',0,0
)") or die('Insert failed: ' . mysqli_error($con));

//setcookie
setcookie("foodID", $ran, time() + (10 * 365 * 24 * 60 * 60));


$_SESSION['order']=$ran;
header("location:foodpage.php");
}




?>

