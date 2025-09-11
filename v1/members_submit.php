<?php
if (isset($_POST['submit']))
	 {

$nam=$_POST['name'];
$las=$_POST['last'];
$mail=$_POST['email'];
$mob=$_POST['mob'];
$cat=$_POST['cater'];
$dear=$_POST['dat'];
$no=$_POST['qua'];
$fileName = basename($_FILES["file"]["name"]);
$filaName = basename($_FILES["fila"]["name"]);
$filoName = basename($_FILES["filo"]["name"]);
$filiName = basename($_FILES["fili"]["name"]);
$filuName = basename($_FILES["filu"]["name"]);


if($cat=="Monthly Membership")
{
    $day=31;
}
else if($cat=="Quarterly Membership")
{
    $day=31*3;
}
else if($cat=="Yearly Membership")
{
    $day=31*12;
}


//file
$statusMsg = '';

// File upload path
$targetDir = "member/";
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


//fila
$statusMsg = '';

// File upload path
$targetDir = "member/";
$filaName = basename($_FILES["fila"]["name"]);
$targetFilePath = $targetDir . $filaName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["fila"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["fila"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = mysqli_query($con,"INSERT into images (file_name,input,uploaded_on) VALUES ('".$filaName."','staff', NOW())");
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



//filo
$statusMsg = '';

// File upload path
$targetDir = "member/";
$filoName = basename($_FILES["filo"]["name"]);
$targetFilePath = $targetDir . $filoName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["filo"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["filo"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = mysqli_query($con,"INSERT into images (file_name,input,uploaded_on) VALUES ('".$filoName."','staff', NOW())");
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




//fili
$statusMsg = '';

// File upload path
$targetDir = "member/";
$filiName = basename($_FILES["fili"]["name"]);
$targetFilePath = $targetDir . $filiName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["fili"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["fili"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = mysqli_query($con,"INSERT into images (file_name,input,uploaded_on) VALUES ('".$filiName."','staff', NOW())");
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





//filu
$statusMsg = '';

// File upload path
$targetDir = "member/";
$filuName = basename($_FILES["filu"]["name"]);
$targetFilePath = $targetDir . $filuName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["filu"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["filu"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = mysqli_query($con,"INSERT into images (file_name,input,uploaded_on) VALUES ('".$filuName."','staff', NOW())");
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


















$ran = substr(md5(mt_rand()), 0, 5);
$data= date('Y-m-d', strtotime("$dear +$day days"));

   					
 $submit = mysqli_query($con,"insert into member(id, first, last, phone,email,type,start,ende,app,tot,nom, file_name, fila_name, filo_name, fili_name, filu_name) 
 values ('$ran','$nam','$las','$mob','$mail','$cat','$dear','$data','','','$no','".$fileName."','".$filaName."','".$filoName."','".$filiName."','".$filuName."')") or die ('Could not connect: ' .mysqli_error($con));
                    
                    session_start();
                    $_SESSION['idea']=$ran;
					echo header("location:membercart.php");
					
					
					
					}
					 
					 
					 




























?>