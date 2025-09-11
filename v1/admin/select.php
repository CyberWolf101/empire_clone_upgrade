    <?php
    include "connect_to_mysqli.php";
    
    if(isset($_post['submin'])){
$name=$_POST['names'];

$price=$_POST['price'];
$submit = mysqli_query($con,"insert into text(name,price) 
values ('$name','$price')") or die ('Could not connect: ' .mysqli_error($con));

if($submit){
    
    echo'submitted';
}
				  }
				  ?>