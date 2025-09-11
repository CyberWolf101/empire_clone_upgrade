<?php 
		 
if (isset($_POST['submin']))
 {
		    $use =  $_POST['add'];
			$med =  $_POST['des'];
	     	$fileName = basename($_FILES["file"]["name"]);
	  	  
		  $sql = "SELECT all* from cater";
		  $sql2 = mysqli_query($con,$sql);
		  while($row = mysqli_fetch_array($sql2))
				    
					{
										  $id = $row["s"];
										  $n =$row["id"]; 
					
					  }
					  
// File upload path
$targetDir = "../category/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submin"]) && !empty($_FILES["file"]["name"])){
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
					  
					  
					  

$ran = $n + 1;	

					   $submit = mysqli_query($con,"insert into cater(id,name,des,file_name) values ('00$ran','$use','$med','".$fileName."')") or die ('Could not connect: ' .mysqli_error($con));
					   $suc='<p style="color:blue">Category added successfully!</p>';

session_start();
$_SESSION['suc']=$suc;
header('Location: cater.php');

				}
					 ?>
