<?php 
 include 'connect_to_mysqli.php';
if(isset($_post['submin'])){
$first=$_POST['names'];
$first=$_POST['price'];

 $submit = mysqli_query($con,"insert into texting(id,names,prices,money) values ('','$first','$per','')") or die ('Could not connect: ' .mysqli_error($con));
    
  echo"right";
}
?>