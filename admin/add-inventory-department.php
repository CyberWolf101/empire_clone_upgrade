<?php 
if (isset($_POST['register'])){
$name=$_POST['name'];


$extract_user = mysqli_query($con,"SELECT * FROM chb_inventory_department WHERE name='$name'");
$count = mysqli_num_rows($extract_user);
if ($count > 0) {
echo '<font color="red">This department already exists.</font>'; }

else{
$submit = mysqli_query($con,"insert into chb_inventory_department(name) values ('$name')") or die ('Could not connect: ' .mysqli_error($con));
echo'<p style="color:blue">Department added successfully!</p>';
header('Refresh:0; url=inventory_departments.php');



}}?>