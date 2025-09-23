 <?php 
if (isset($_POST['register'])){
$name=$_POST['name'];
$price=$_POST['price'];
$selectedSections = $_POST['category'];
$commaSeparatedSections = implode(',', $selectedSections);


$submit = mysqli_query($con,"insert into rental_items(name,price,category) values ('$name','$price','$commaSeparatedSections')") or die ('Could not connect: ' .mysqli_error($con));


echo'<p style="color:blue">Item added successfully!</p>';
header('Refresh:0; url=rentalitems.php');



}?>