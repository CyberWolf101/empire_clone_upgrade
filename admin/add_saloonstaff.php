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


          $sql = "SELECT all* from staff";
		  $sql2 = mysqli_query($con,$sql);
		  while($row = mysqli_fetch_array($sql2)){
		  $id = $row["s"];
		  $n =$row["id"]; }


$ran = $n + 1;	
$submit = mysqli_query($con,"insert into staff(id,name,email,phone,gender,file_name,password,status,section,regdate,logdate,wallet) values ('0$ran','$name','$email','$phone','$type','".$fileName."','$password','available','$category','$date','$date','0')") or die ('Could not connect: ' .mysqli_error($con));
echo'<p style="color:blue">Staff Registered successfully!</p>';


header('Refresh:1; url=addsaloonstaff.php');



}?>