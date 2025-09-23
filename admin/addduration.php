 <?php 
		 
if (isset($_POST['register'])){
$service=$_POST['name'];
$price=$_POST['price'];
$category =  $_POST['category'];




          $sql = "SELECT * from durations";
		  $sql2 = mysqli_query($con,$sql);
		  while($row = mysqli_fetch_array($sql2)){
		  $id = $row["s"];
		  $n =$row["id"];
		  }

if($n==""){$n=5; }
$ran = $n + 5;	

$submit = mysqli_query($con,"insert into durations(id,category,duration,price) values ('$ran','$category','$service','$price')") or die ('Could not connect: ' .mysqli_error($con));

echo'<p style="color:blue">Duration added successfully!</p>';
header('Refresh:0; url=duration.php');



}?>