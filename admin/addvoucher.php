 <?php 
		 
if (isset($_POST['register'])){
$name=$_POST['name'];
$price=$_POST['price'];
$fileName = basename($_FILES["file"]["name"]);
// Get selected values from the 'sections' field
$selectedSections = $_POST['sections'];
$commaSeparatedSections = implode(',', $selectedSections);



$statusMsg = '';

// File upload path
$targetDir = "../giftpackage/";
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


          $sql = "SELECT all* from gift_packages";
		  $sql2 = mysqli_query($con,$sql);
		  while($row = mysqli_fetch_array($sql2)){
		  $id = $row["s"];
		  $n =$row["id"];
		  }

if($n==""){$n=50; }
$ran = $n + 10;	

$submit = mysqli_query($con,"insert into gift_packages(id,name,price,services,picture) values ('0$ran','$name','$price','$commaSeparatedSections','".$fileName."')") or die ('Could not connect: ' .mysqli_error($con));


echo'<p style="color:blue">Packaged added successfully!</p>';
header('Refresh:0; url=createpackages.php');



}?>