<?php include "header.php";?>




<style>
.btn-buya {
  display: inline-block;
  padding:6px !important;
  border:none;
  color: #fff;
  font-size: 12px !important;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
  font-weight: 700;
  transition: 0.3s;
  background:#FEBF01;
  width:100px;
  
}


.btn-buya:hover {
  display: inline-block;
  padding:6px !important;
  border:none;
  color: #fff;
  font-size: 12px !important;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
  font-weight: 800;
  transition: 0.3s;
  background:#FEBF01;
  width:100px;
  
}
</style>



<div style="margin-top:100px; color:#FFFFFF;">
<div class="justify-content-center" align="center">
<form action="" method="post">
<p><b>SELECT ORDER TYPE</b></p>
<p>If you want your meals to be prepared instantly so you can pick up today or book for a party,event by making a preorder (bowls and litres quantity)</p>
<div class="col-lg-4">
<p><button type="submit" name="submitdetails" value="0" class="btn-buya w-100">INSTANT ORDER</button><br>
<button type="submit" name="submitdetails" value="1" class="btn-buya w-100">PREORDER (PARTY,EVENTS AND MUCH MORE)</button></p>
</div>
</form></div>




  















<?php

$prd="";
if (isset($_POST['submitdetails'])){
$prd=$_POST['submitdetails'];
$insert = mysqli_query($con,"UPDATE saloon_orders SET preorder='$prd' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
header("location: soups.php");
}
include "footer.php"; ?>