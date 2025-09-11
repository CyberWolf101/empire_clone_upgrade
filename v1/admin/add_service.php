<?php 
include "connect_to_mysqli.php";
if(isset($_POST['far'])){

$force=$_POST['far'];
$tim=$_POST['th'];
$mob=$_POST['ph'];
$cat=$_POST['cater'];







 $sql = "SELECT all* from baby";
 $sql2 = mysqli_query($con,$sql);
 while($row = mysqli_fetch_array($sql2))
				    
					{
										  $id = $row["s"];
										  $n =$row["id"]; 
					
					  }

$ran = $n + 01;	

			
					
					
					
					
					
$submit = mysqli_query($con,"insert into baby(id, gen, name, price, time) values ('$ran','$cat','$force','$mob','$tim')") or die ('Could not connect: ' .mysqli_error($con));
$nom='<p style="color:blue;">Addition successful!</p>';
	
 	


}
					
?>