<?php 
		 
if (isset($_POST['add'])){
$name=$_POST['name'];
$price=$_POST['price'];
$quantity=$_POST['quantity'];
$type=$_POST['type'];
$fileName = basename($_FILES["file"]["name"]);



 $insert = mysqli_query($con,"UPDATE food_menu SET item= '$name' where s='$id'") or die ('Could not connect: ' .mysqli_error($con));
 $insert = mysqli_query($con,"UPDATE food_menu SET price= '$price'  where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
 $insert = mysqli_query($con,"UPDATE food_menu SET quantity= '$quantity'  where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
 $insert = mysqli_query($con,"UPDATE food_menu SET type= '$type'  where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 




// File upload path
$targetDir = "../orishirishi/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["add"]) && !empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = mysqli_query($con,"INSERT into images (file_name,input,uploaded_on) VALUES ('".$fileName."','staff', NOW())");
             $insert = mysqli_query($con,"UPDATE food_menu SET file_name='".$fileName."' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
            if($insert){
                $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
            }else{
                $statusMsg = "File upload failed, please try again.";
            } 
        }else{
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    }else{
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
    }
}else{
    $statusMsg = 'Please select a file to upload.';
}










echo '<p style="color:blue;">Item Updated Successfully!</p>';
header('Refresh:1; url=foodmenu.php');   








} ?>