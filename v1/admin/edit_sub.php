<?php 
include "connect_to_mysqli.php";
$on=$_POST['oddd'];
$ema=$_POST['em'];
$var=$_POST['vem'];
$pard=$_POST['id'];
$price=$_POST['price'];
$fileName = basename($_FILES["file"]["name"]);


			            if (strlen($var) == 0)
						{
						$rw = '<p style="color:red;">Upload Details Correctly</p>';
						}
						else{
						


if(isset($_POST['add'])){
$add=$_POST['add'];
$insert = mysqli_query($con,"UPDATE sub SET addmeal='$add' where id='$pard'") or die ('Could not connect: ' .mysqli_error($con)); 

}		             
						      
						      
						      
						      
						      $insert = mysqli_query($con,"UPDATE sub SET name= '$on' where id='$pard'") or die ('Could not connect: ' .mysqli_error($con));
						      $insert = mysqli_query($con,"UPDATE sub SET sub_price= '$price' where id='$pard'") or die ('Could not connect: ' .mysqli_error($con)); 
							  $insert = mysqli_query($con,"UPDATE sub SET gen= '$var'  where id='$pard'") or die ('Could not connect: ' .mysqli_error($con)); 
							  $insert = mysqli_query($con,"UPDATE sub SET descrip= '$ema'  where id='$pard'") or die ('Could not connect: ' .mysqli_error($con)); 
							
							 
			
	

$statusMsg = '';

// File upload path
$targetDir = "../sub/";
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
             $insert = mysqli_query($con,"UPDATE sub SET file_name='".$fileName."' where id='$pard'") or die ('Could not connect: ' .mysqli_error($con)); 
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


 
$norm='<p style="color:blue;">Update Successful!</p>';
if($var=="0015"){
 header('Refresh:1; url=delta_kitchen.php');   
}
else if($var=="0012"){
 header('Refresh:1; url=academy.php');   
}
else{
 header('Refresh:1; url=sub.php');   
}
					
							 	
			         
	  
	  
	  }
	  
	  ?>