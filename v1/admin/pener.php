<?php 

if(isset($_POST['car'])){

$ca=$_POST['car'];
$ti=$_POST['tit'];
$dec=$_POST['de'];
$dea=$_POST['dat'];
$dear=$_POST['datr'];







include "connect_to_mysqli.php";







	$ran = substr(md5(mt_rand()), 0, 3);	
			
					   $submit = mysqli_query($con,"insert into pense(id,amount,title,des,date,nam,tim) values ('$ran','$ca','$ti','$dec','$dea','SM','$dear')") or die ('Could not connect: ' .mysqli_error($con));
					 
 		
		 
		  echo 'Expense Successfully Logged!';	
		  
	
 	

}
					
?>