<?php
include "connect_to_mysqli.php";
    if (isset($_POST['submit']))
	 {
	 
	 $nam = $_POST['name']; 
	 $loc =  $_POST['state'];
	 $des= $_POST['mob']; 
	 $fileName = basename($_FILES["file"]["name"]);
	 


// File upload path
$targetDir = "review/";
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
            $insert = mysqli_query($con,"INSERT into images (file_name,input,uploaded_on) VALUES ('".$fileName."','staff', NOW())");
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





 $submit = mysqli_query($con,"insert into rew(name, locat, vew, file_name) values ('$nam','$loc','$des','".$fileName."')") or die ('Could not connect: ' .mysqli_error($con));
echo header("location:index.php#review");
					
					
					
					}
					 
					 
					 




























?>