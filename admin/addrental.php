<?php 
		 
if (isset($_POST['register'])){
$name=$_POST['name'];
$des= mysqli_real_escape_string($con, $_POST['details']);
$fileName = basename($_FILES["file"]["name"]);


$extract_user = mysqli_query($con,"SELECT * FROM rental_subcategories WHERE name='$name'");
$count = mysqli_num_rows($extract_user);
if ($count > 0) {
echo '<font color="red">This subcategory already exists.</font>';
 }



else{

$statusMsg = '';

// File upload path
$targetDir = "../gallery/";
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


          $sql = "SELECT all* from rental_subcategories";
		  $sql2 = mysqli_query($con,$sql);
		  while($row = mysqli_fetch_array($sql2)){
		  $id = $row["s"];
		  $n =$row["id"];
		  }

if($n==""){$n=0; }
$ran = $n + 10;	

$submit = mysqli_query($con,"insert into rental_subcategories(id,name,description,picture) values ('0$ran','$name','$des','".$fileName."')") or die ('Could not connect: ' .mysqli_error($con));

echo'<p style="color:blue">Subcategory  added successfully!</p>';
header('Refresh:0; url=rental_subcategory.php');



}}?>