<?php 
		 
if (isset($_POST['register'])){
$name=$_POST['name'];
$email=$_POST['email'];
$password=$_POST['password'];
$category=$_POST['role'];
$selectedSections = $_POST['sections'];
$commaSeparatedSections = implode(',', $selectedSections);





                              $insert = mysqli_query($con,"UPDATE admin SET name= '$name' where s='$id'") or die ('Could not connect: ' .mysqli_error($con));
							  $insert = mysqli_query($con,"UPDATE admin SET email= '$email'  where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
							  $insert = mysqli_query($con,"UPDATE admin SET password= '$password'  where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
							  $insert = mysqli_query($con,"UPDATE admin SET status= '$category'  where s='$id'") or die ('Could not connect: ' .mysqli_error($con));
							  $insert = mysqli_query($con,"UPDATE admin SET sections='$commaSeparatedSections' where s='$id'") or die ('Could not connect: ' .mysqli_error($con));
							
							 

 
echo '<p style="color:blue;">Manager Details Updated Successfully!</p>';
header('Refresh:1; url=managers.php');   



}?>