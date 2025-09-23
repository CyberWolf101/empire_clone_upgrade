<?php 
		 
if (isset($_POST['register'])){
$name=$_POST['name'];
$email=$_POST['email'];
$password=$_POST['password'];
$phone=$_POST['phone'];
$type=$_POST['type'];
$category=$_POST['service'];
$fileName = basename($_FILES["file"]["name"]);
$date= date('Y-m-d H:i:s');





                              $insert = mysqli_query($con,"UPDATE staff SET name= '$name' where s='$id'") or die ('Could not connect: ' .mysqli_error($con));
							  $insert = mysqli_query($con,"UPDATE staff SET email= '$email'  where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
							  $insert = mysqli_query($con,"UPDATE staff SET phone= '$phone'  where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
							  $insert = mysqli_query($con,"UPDATE staff SET password= '$password'  where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
							  $insert = mysqli_query($con,"UPDATE staff SET section= '$category'  where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
                              $insert = mysqli_query($con,"UPDATE staff SET gender= '$type'  where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
							
							 
			
	

$statusMsg = '';

// File upload path
$targetDir = "../staff/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["register"]) && !empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = mysqli_query($con,"INSERT into images (file_name,input,uploaded_on) VALUES ('".$fileName."','staff', NOW())");
             $insert = mysqli_query($con,"UPDATE staff SET file_name='".$fileName."' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
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


 
echo '<p style="color:blue;">Staff Details Updated Successfully!</p>';
header('Refresh:1; url=staff.php');   



}?>