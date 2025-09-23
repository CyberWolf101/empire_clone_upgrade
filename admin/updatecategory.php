<?php 
if(isset($_POST["submit"])) {
$name=$_POST['name'];
$des= mysqli_real_escape_string($con, $_POST['described']);
$fileName = basename($_FILES["file"]["name"]);


// File upload path
$targetDir = "../category/";
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
            $insert = mysqli_query($con,"UPDATE category SET file_name='".$fileName."' where id='$category'") or die ('Could not connect: ' .mysqli_error($con)); 
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
			
					
$insert = mysqli_query($con,"UPDATE category SET name= '$name' where id='$category'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE category SET description='$des' where id='$category'") or die ('Could not connect: ' .mysqli_error($con)); 
							 
echo'<p style="color:blue;">Category Updated Successfully!</p>';
header('Refresh:1; url=categories.php');   
}
	  
	  ?>