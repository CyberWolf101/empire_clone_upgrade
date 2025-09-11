<?php include "connect_to_mysqli.php";
  
   if (isset($_POST['submit']))
   {
  $sub= $_POST['submit'];
  
   if ($sub=="new")
   
	 {
	 $ord =  $_POST['email']; $val = $_POST['phone'];
	 $dear = $_POST['dear'];
	 $ran = $_POST['ran'];
$nam =  $_POST['name'];
	   $submit = mysqli_query($con,"insert into foods(name,email,phone,id,app,status,date,meth) values ('$nam','$ord','$val','$ran','','','$dear','')") or die ('Could not connect: ' .mysqli_error($con));
  
session_start();
$_SESSION['ider']=$ran;
echo header("location:pay.php");

}

else
{
    	 $dear = $_POST['dear'];
	      $ran = $_POST['ran'];
	  $nam =  $_POST['name'];
	  
	  
  $sql = "SELECT * from foods where name = '".$nam."'  ";
 $sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
	{
										  $ord = $row["email"];  $val = $row["phone"];
	} 
	
 $submit = mysqli_query($con,"insert into foods(name,email,phone,id,app,status,date) values ('$nam','$ord','$val','$ran','Confirmed','Paid','$dear')") or die ('Could not connect: ' .mysqli_error($con));
  
session_start();
$_SESSION['ider']=$ran;
echo header("location:pay.php");	
	
	
	
	
	
}










}
?>