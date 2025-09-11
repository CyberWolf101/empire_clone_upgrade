<?php 
include "connect_to_mysqli.php";
if(isset($_POST['far'])){

$force=$_POST['far'];
$mob=$_POST['ph'];






$extract_user = mysqli_query($con,"SELECT * FROM see WHERE name='$force'");
		$count = mysqli_num_rows($extract_user);
		
		 if ($count > 0) {
				$chk = '<font color="red">This package already exists!</font>';
		                 }
		                 else
		                 {




					
					
					
					
					   $submit = mysqli_query($con,"insert into see(name, price) values ('$force','$mob')") or die ('Could not connect: ' .mysqli_error($con));
					 
 		
		 
		 $nom='<p style="color:blue;">Package added successfully</p>';
	
 	
}

}
					
?>