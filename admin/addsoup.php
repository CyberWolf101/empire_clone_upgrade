<?php 
		 
if (isset($_POST['register'])){
$name=$_POST['name'];
$price=$_POST['price'];
$category=$_POST['category'];
$pre=$_POST['preorder'];
$des= mysqli_real_escape_string($con, $_POST['details']);
$fileName = basename($_FILES["file"]["name"]);


if($pre==""){$pre=0; }


$extract_user = mysqli_query($con,"SELECT * FROM delta_soups WHERE name='$name'");
$count = mysqli_num_rows($extract_user);
if ($count > 0) {
echo '<font color="red">This item already exists on the menu.</font>';
 }



else{

$statusMsg = '';

// File upload path
$targetDir = "../kitchen/";
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


          $sql = "SELECT all* from delta_soups";
		  $sql2 = mysqli_query($con,$sql);
		  while($row = mysqli_fetch_array($sql2)){
		  $id = $row["s"];
		  $n =$row["id"];
		  }

if($n==""){$n=0; }
$ran = $n + 10;	

$submit = mysqli_query($con,"insert into delta_soups(id,name,description,price,picture,additional,preorder) values ('0$ran','$name','$des','$price','".$fileName."','$category','$pre')") or die ('Could not connect: ' .mysqli_error($con));

echo'<p style="color:blue">Item added to menu successfully!</p>';
header('Refresh:0; url=delta_subcategory.php');



}}?>