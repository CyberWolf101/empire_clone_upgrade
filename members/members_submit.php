<?php
if(isset($_POST['submitdetails']))
{

  $quantity = $_POST["quantity"];
  $startdate=date("Y-m-d");
  $type = $_POST["type"];

 if($type=="Monthly Membership")
{   $day=31; }
else if($type=="Quarterly Membership")
{   $day=31*4;}
else if($type=="Yearly Membership")
{    $day=31*12;}


// Loop through the member data based on the selected quantity
       for ($i = 0; $i < $quantity; $i++) {
        $name = $_POST["name"][$i];
        $email = $_POST["email"][$i];
        $mobile = $_POST["mobile"][$i];
        $file = $_FILES["file"]["name"][$i]; 



//file
$statusMsg = '';

// File upload path
$targetDir = "../membership/";
$fileName = basename($_FILES["file"]["name"][$i]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submitdetails"]) && !empty($_FILES["file"]["name"][$i])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"][$i], $targetFilePath)){
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



$code = "CHB".substr(md5(mt_rand()), 0, 5);
$enddate = date('Y-m-d', strtotime("$startdate +$day days"));

   					
 $submit = mysqli_query($con,"insert into members(`cardno`, `name`, `email`, `phone`, `picture`, `type`, `start_date`, `end_date`,`total_amount`, `status`, `paystatus`) 
 values ('$code','$name','$email','$mobile','".$fileName."','$type','$startdate','$enddate','','processing','')") or die ('Could not connect: ' .mysqli_error($con));
                    
}
					 
					 
echo header("location:memberpackages.php?card=$code");
}					 

?>