<?php 
if(isset($_POST['repair'])){

$item=$_POST['item'];
$damage=$_POST['describe'];
$name=$_POST['name'];
$phone=$_POST['phone'];
$email=$_POST['email'];
$purchase=$_POST['purchase'];
$duration=$_POST['duration'];




$targetDir ="../repairs/";
$fileName = basename($_FILES["product"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

 if(isset($_POST["repair"]) && !empty($_FILES["product"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
     // Upload file to server
     if(move_uploaded_file($_FILES["product"]["tmp_name"], $targetFilePath)){
     // Insert image file name into database
     $insert = mysqli_query($con,"INSERT into images (file_name, uploaded_on) VALUES ('".$fileName."', NOW())");
 }}}

$date=date('Y-m-d');
$code=substr(mt_rand(), 0, 4);
$id='RP'.$code;
$submit = mysqli_query($con,"insert into repair_center(`repair_id`,`name`, `contact`,`email`, `item`, `description`,`purchase`,`duration`, `picture`, `date`, `status`, `repairhistory`) 
values ('$id','$name','$phone', '$email','$item','$damage','$purchase','$duration', '".$fileName."', '$date', 'pending','no')") or die ('Could not connect: ' .mysqli_error($con));



	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $email_message= "";	
	$email_from="admin@chbluxuryempire.com";					 
	$email_to = $email;
    $email_subject = "Repair Request Sent!";
    $email_message ="
	
<html><body><div style='background-color:#000000; color:#fff; height:700px; width:500px; padding:50px; font-weight:600;'>
<p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' />
<font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$date</font></p><br><br>
<p style='color:#fff;'>Hello Dear $name,Thank you for choosing CHB luxury repair center.We will get back to you in the next 24-72hrs if your device can be fixed or not. 
<br>However this is your decide tracking code <span style='color:#FFC700;'>$id</span> to check the status of your repair via the link below.<br></p>
 <p><a href='https://chbluxuryempire.com/repair_progress.php?repairid=$id' style='color:#FFC700; text-decoration:underline;'>Check Repair Progress</a></p> 


<br><br>
<p style='text-align:center; color:#fff;'>
  Visit our website:
  <a href='https://chbluxuryempire.com/' style='color:#FFC700; text-decoration:underline;'>
 CHB LUXURY ACADEMY
  </a>
</p>
</div>
</body></html>
	
	


	
	


";
							 
	  function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
				 
							 
// create email headers
         $header =  'From: "CHBLUXURYEMPIRE" <admin@chbluxuryempire.com>'. "\r\n"; 
         $header .= "Cc:admin@chbluxuryempire.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
	
	
	
		///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $email_message= "";	
	$email_from="admin@chbluxuryempire.com";					 
	$email_to = "corporatehair.sales@gmail.com";
    $email_subject = "New Repair Request";
    $email_message ="
    
    
     <html><body><div style='background-color:#000000; color:#fff; height:700px; padding:50px; width:500px;'>
      <p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' /></p><br><br>
     <p>Hello Dear Admin,There has been a new repair request!</p><br><br>
     <pstyle='color:#FFFFFF;'>Item is a $item </p>

  <p style='text-align:center; color:#fff;'>
  Visit our website:
  <a href='https://chbluxuryempire.com/' style='color:#FFC700; text-decoration:underline;'>
 CHB LUXURY ACADEMY
  </a>
</p>
 </div>
 </body></html>
	

 
 
	


";			 
							 
   // create email headers
         $header =  'From: "CHBLUXURYEMPIRE" <admin@chbluxuryempire.com>'. "\r\n"; 
         $header .= "Cc:admin@chbluxuryempire.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";


$mailsent=mail($email_to, $email_subject,$email_message,$header);
if($mailsent ==true){
echo '<p style="color:blue;">Repair Request Submitted Successfully</p>';
header("refresh:1;url=repairs.php"); 
}    





}					
?>