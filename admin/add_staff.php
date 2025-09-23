<?php 
		 
if (isset($_POST['register'])){
$name=$_POST['name'];
$email=$_POST['email'];
$password=$_POST['password'];
$category=$_POST['role'];
$selectedSections = $_POST['sections'];
$commaSeparatedSections = implode(',', $selectedSections);




	
$submit = mysqli_query($con,"insert into admin(name,email,password,sections,status) values ('$name','$email','$password','$commaSeparatedSections','$category')") or die ('Could not connect: ' .mysqli_error($con));
echo'<p style="color:blue">Staff Registered successfully!</p>';


header('Refresh:1; url=addstaff.php');



}?>