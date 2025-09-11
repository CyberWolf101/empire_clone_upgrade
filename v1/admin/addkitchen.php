<?php 
if(isset($_POST['submit'])){

$name=$_POST['name'];
$price=$_POST['price'];
$discount=$_POST['discount'];
$bio=$_POST['bio'];
$category=$_POST['category'];
$fileName = basename($_FILES["file"]["name"]);



$extract_user = mysqli_query($con,"SELECT * FROM delta_kitchen WHERE name='$name'");
$count = mysqli_num_rows($extract_user);
if ($count > 0) {
echo '<font color="red">This item already exists on the menu!</font>';
 }
else
{




// File upload path
$targetDir = "../food/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = mysqli_query($con,"INSERT into images (file_name,input,uploaded_on) VALUES ('".$fileName."','food', NOW())");
        }}}
        
        
        
			
					
					
					
					
					
$submit = mysqli_query($con,"insert into delta_kitchen(name, price, discount,category,picture,status,bio) values ('$name','$price','$discount','$category','".$fileName."','available','$bio')") or die ('Could not connect: ' .mysqli_error($con));
echo 'Meal Added Successfully!';
header("refresh:1;url=kitchen.php");	


}
}					
?>