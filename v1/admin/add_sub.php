<?php 
include "connect_to_mysqli.php";
if(isset($_POST['far'])){

$force=$_POST['far'];
$email=$_POST['em'];
$cat=$_POST['cater'];
$add=$_POST['add'];
$price=$_POST['sub_price'];
$fileName = basename($_FILES["file"]["name"]);



$extract_user = mysqli_query($con,"SELECT * FROM sub WHERE name='$force'");
		$count = mysqli_num_rows($extract_user);
		
		 if ($count > 0) {
				$chk = '<font color="red">This sub category exists.</font>';
		                 }
		                 else
		                 {


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



 $sql = "SELECT all* from sub ORDER by s ASC";
		  $sql2 = mysqli_query($con,$sql);
			  while($row = mysqli_fetch_array($sql2))
				    
					{
										  $id = $row["s"];
										  $n =$row["id"]; 
					
					  }

$ran = $n + 10;				
					
					
					
					
					
					   $submit = mysqli_query($con,"insert into sub (id, gen, name, descrip, file_name,addmeal,sub_price) values ('0$ran','$cat','$force','$email','".$fileName."','$add','$price')") or die ('Could not connect: ' .mysqli_error($con));
					   $nom='<p style="color:blue;" >Registered Successfully</p>';
	
 	
}

}
					
?>