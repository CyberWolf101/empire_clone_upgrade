<?php include "connect_to_mysqli.php";

$accpt_err = $er = $err1 = ""; $err1_ = $chk = $err2 = $err_msg = $err3 = $err3_ = $err4 = $err5 = $err6 = $err7 = $err8 = $err9 = $err10 = $err11 = $err12 = $err13 = $err4= $msg ="";
$accpt_err=""; $success = ""; $success1 = "";
if(isset($_POST['semi'])){


$nam=$_POST['name'];
$mail=$_POST['mail'];
$mob=$_POST['mob'];
$star=$_POST['staff'];
$dea=$_POST['dear'];
$tem=$_POST['team'];
$sem=$_POST['semi'];
$sum=$_POST['submit'];
$ide=$_POST['idea'];





if($ide=="")
{
$ide=substr(md5(mt_rand()), 0, 4);
}
else{

$ide=$ide;

}

if ($sem=="")
{
echo "";
}

else
{
$sqk = "SELECT all* from serve where id='$sem'";
		$sqlp = mysqli_query($con,$sqk);
			 while($rowe = mysqli_fetch_array($sqlp))
				  {
				  $see = $rowe['name'];
				$per = $rowe['price'];
				$trem = $rowe['time'];
				}
				
$newtime = strtotime($tem) + ($trem * 60);
$tam= date('H:i', $newtime);
				

					   $submit = mysqli_query($con,"insert into horder(id, name, email, phone, service, date, timef, timet, price, staff, status,quantity,cart,meth,app) values ('$ide','$nam','$mail','$mob','$see','$dea','$tem','$tam','$per','$star','unpaid','1','003','Card','Not')") or die ('Could not connect: ' .mysqli_error($con));






if ($sum=="Procced to Payment")
{
session_start();
$_SESSION['ider']=$ide;
echo header("location:pay.php");
}

else{
session_start();
$_SESSION['ider']=$ide;
echo header("location:more.php");
}






}


}




















?>