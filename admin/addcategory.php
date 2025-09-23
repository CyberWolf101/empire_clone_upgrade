<?php 
		 
if (isset($_POST['register'])){
$name=$_POST['name'];
$des= mysqli_real_escape_string($con, $_POST['described']);
$fileName = basename($_FILES["file"]["name"]);
	  	  
		  $sql = "SELECT all* from category";
		  $sql2 = mysqli_query($con,$sql);
		  while($row = mysqli_fetch_array($sql2)){
		 $id = $row["s"];
		 $n =$row["id"]; 
		 $an =$row["order_no"]; 
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
$ord=$an+1;
$submit = mysqli_query($con,"insert into category(id,name,description,file_name,order_no) values ('00$ran','$name','$des','".$fileName."','$ord')") or die ('Could not connect: ' .mysqli_error($con));
echo'<p style="color:blue">Category added successfully!</p>';


header('Refresh:1; url=categories.php');

				}
					 ?>
