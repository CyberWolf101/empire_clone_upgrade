<?php 
if(isset($_POST['upsubmit'])){
$id=$_POST['rack'];
$name=$_POST['name'];
$price=$_POST['price'];
$discount=$_POST['discount'];
$bio=$_POST['bio'];
$category=$_POST['category'];
$fileName = basename($_FILES["file"]["name"]);


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
        
        
        
        
        
 
$insert = mysqli_query($con,"UPDATE delta_kitchen SET name='$name' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE delta_kitchen SET price='$price' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE delta_kitchen SET discount='$discount' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 	
$insert = mysqli_query($con,"UPDATE delta_kitchen SET category='$category' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE delta_kitchen SET bio='$bio' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
	
if($fileName!="")

{ $insert = mysqli_query($con,"UPDATE delta_kitchen SET picture='".$fileName."' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); }
					
$norm='<p style="color:blue;">Update Successful!</p>';
header('Refresh:1; url=kitchen.php');
echo $norm;        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
}       
        
        ?>