<?php 
include "connect_to_mysqli.php";
if(isset($_POST['far'])){

$force=$_POST['far'];
$lass=$_POST['pa'];
$email=$_POST['em'];
$mob=$_POST['ph'];
$cat=$_POST['cater'];
$gen=$_POST['gend'];
$sect=$_POST['section'];
$role=$_POST['role'];
$fileName = basename($_FILES["file"]["name"]);



$extract_user = mysqli_query($con,"SELECT * FROM staff WHERE email='$email'");
		$count = mysqli_num_rows($extract_user);
		
		 if ($count > 0) {
				$chk = '<font color="red">This email already exists on our site,Please register another</font>';
		                 }
		                 else
		                 {


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



			$date=date('Y-m-d');
$code=substr(mt_rand(), 0, 4);
$id='ST'.$code;
					
					
					
					
					
					   $submit = mysqli_query($con,"insert into staff(id,name,email,phone,gen,file_name,pass,status,section,regdate,logdate,services) values ('$code','$force','$email','$mob','$gen','".$fileName."','$lass','$role','$sect','$date','$date','$cat')") or die ('Could not connect: ' .mysqli_error($con));
					 
 		
		 
		  $nom='<p style="color:blue;">Staff Registered Successfully!</p>';
	
 	
}

}
					
?>