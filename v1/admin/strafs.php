<?php include "connect_to_mysqli.php";
		  $on=$_POST['oddd'];
		  
$ema=$_POST['em'];
$da=$_POST['add'];
$var=$_POST['vem'];
$dar=$_POST['add'];
$pard=$_POST['pasd'];
$role=$_POST['role'];
$cart=$_POST['cart'];
$fileName = basename($_FILES["file"]["name"]);


			  if (strlen($dar) == 0)
						{
						$rw = '<p style="color:red;">Upload Details Correctly</p>';
						}
						else{
						


			
					
					
										
						
						
						
						
						
						
						
						
		$statusMsg = '';

// File upload path
$targetDir = "../staff/";
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
				
						
						
						
						
						
						
			             
						  $insert = mysqli_query($con,"UPDATE staff SET name= '$on' where email='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
						
							
							 $insert = mysqli_query($con,"UPDATE staff SET phone= '$dar' where email='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
							  $insert = mysqli_query($con,"UPDATE staff SET id= '$var' where email='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
							 $insert = mysqli_query($con,"UPDATE staff SET services= '$var' where email='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
							  $insert = mysqli_query($con,"UPDATE staff SET status= '$role' where email='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
							   $insert = mysqli_query($con,"UPDATE staff SET section= '$cart' where email='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
							 	 $insert = mysqli_query($con,"UPDATE staff SET pass= '$pard' where email='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
			
	
if($fileName!="")

{

 $insert = mysqli_query($con,"UPDATE staff SET file_name='".$fileName."' where email='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
 
 }			
		echo"<script>alert('Staff Details Updated Successfully!');
window.location.assign('staffs.php')</script>";			
			
							 	
			         
	  
	  
	  }
	  
	  ?>