 <?php 
		 
if (isset($_POST['register'])){
$service=$_POST['name'];
$price=$_POST['price'];
$duration =  $_POST['duration'];
$category =  $_POST['category'];




          $sql = "SELECT * from services";
		  $sql2 = mysqli_query($con,$sql);
		  while($row = mysqli_fetch_array($sql2)){
		  $id = $row["s"];
		  $n =$row["id"];
		  }

if($n==""){$n=5; }
$ran = $n + 5;	

$submit = mysqli_query($con,"insert into services(id,sub_category,name,price,duration) values ('$ran','$category','$service','$price','$duration')") or die ('Could not connect: ' .mysqli_error($con));

echo'<p style="color:blue">Service added successfully!</p>';
header('Refresh:0; url=services.php');



}?>