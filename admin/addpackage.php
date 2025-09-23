 <?php 
		 
if (isset($_POST['register'])){
$service=$_POST['service'];
$price=$_POST['price'];





          $sql = "SELECT * from services where id='$service'";
		  $sql2 = mysqli_query($con,$sql);
		  while($row = mysqli_fetch_array($sql2)){
		  $name =$row["name"];
		  }



$submit = mysqli_query($con,"insert into packages(id,service,price) values ('$service','$name','$price')") or die ('Could not connect: ' .mysqli_error($con));


echo'<p style="color:blue">Service added to packages successfully!</p>';
header('Refresh:0; url=packages.php');



}?>