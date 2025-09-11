<?php include "connect_to_mysqli.php";

$on=$_POST['oddd'];
$ema=$_POST['rack'];
$dar=$_POST['des'];
$fileName = basename($_FILES["file"]["name"]);


			  if (strlen($dar) == 0)
						{
						$rw = '<p style="color:red;">Upload Details Correctly</p>';
						}
						else{
						

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
            $insert = mysqli_query($con,"UPDATE cater SET file_name='".$fileName."' where id='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
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
			
					
$insert = mysqli_query($con,"UPDATE cater SET name= '$on' where id='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE cater SET des= '$dar' where id='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
							 
$norm='<p style="color:blue;">Update Successful!</p>';
if($ema=="0015"){
 header('Refresh:1; url=delta_kitchen.php');   
}
else{
 header('Refresh:1; url=category.php');   
}
							 	
			         
	  
	  
	  }
	  
	  ?>