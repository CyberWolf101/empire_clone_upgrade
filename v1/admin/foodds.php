<?php include "connect_to_mysqli.php";
		  $on=$_POST['oddd'];
		  

$ema=$_POST['rack'];
$var=$_POST['vem'];
$dar=$_POST['add'];
$bah=$_POST['ad'];
$fileName = basename($_FILES["file"]["name"]);


			  if (strlen($dar) == 0)
						{
						$rw = '<p style="color:red;">Upload Details Correctly</p>';
						}
						else{
						
			$statusMsg = '';

// File upload path
$targetDir = "../food/";
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
					
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						


 $sql = "SELECT * from food where id  = '".$ema."'  ";
 $sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
	{
										  $me = $row["nom"];
	}
					
	if ($bah > $me)	
	{
   $kab=$bah-$me;
   $submit = mysqli_query($con,"insert into stock(id,des,nom) values ('$ema','add','$kab')") or die ('Could not connect: ' .mysqli_error($con));	    
	    
	}
	else
	{
	$kab=$me-$bah;
   $submit = mysqli_query($con,"insert into stock(id,des,nom) values ('$ema','minus','$kab')") or die ('Could not connect: ' .mysqli_error($con));	    
	}									

						
						
						
						
						
						
						
						
						
						
						
						
			             
						  $insert = mysqli_query($con,"UPDATE food SET name= '$on' where id='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
						
							
							 $insert = mysqli_query($con,"UPDATE food SET price= '$dar' where id='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
							  $insert = mysqli_query($con,"UPDATE food SET type= '$var' where id='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
							
						 $insert = mysqli_query($con,"UPDATE food SET nom= '$bah' where id='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
			
	
if($fileName!="")

{

 $insert = mysqli_query($con,"UPDATE food SET file_name='".$fileName."' where id='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 
 
 }			
			
					
					$norm='<p style="color:blue;">Update Successful!</p>';
					
					header('Refresh:1; url=foods.php');
                    echo $norm;
	  
	  
	  }
	  
	  ?>