<?php 
 include 'header.php';


if(isset($_POST['submit'])){



$price=$_POST['Cost'];
 $service=$_POST['services'];
 $time=$_POST['time'];
 $date=$_POST['date'];
 $staff=$_POST['staff'];
 $ran=$_POST['id'];
session_start();
$_SESSION['idea'] =$ran;
$qty=$_POST['qty'];
  

$see=$_POST['serv'];
 $ter= $_POST['ter'];
 
 $i=0;

//service details
foreach ($see as $key => $value) {
    
 

$total=$price[$key] * $qty[$key];
$newtime = strtotime($time[$key]) + ($ter[$key] * 60);
$tam= date('H:i', $newtime);
$submit = mysqli_query($con,"insert into cart(id, service, price, timef, timet, date, staff, status, time, nom, name, email, phone,app,meth) 
values ('".$_SESSION['idea']."','".$value."','".$total."','".$time[$key]."','".$tam."','".$date[$key]."','".$staff[$key]."','','".$ter[$key]."','','','','','','')") or die ('Could not connect: ' .mysqli_error($con));
echo header("location: payback.php");

}

    




//echo $orid;


if($submit)
{
    
      $insert = mysqli_query($con,"UPDATE entry SET status='yes' where idno='$ran'") or die ('Could not connect: ' .mysqli_error($con)); 
}




}

 ?>
 