<?php include"header.php";

$repair=$_SESSION['repair_id'];

$sql = "SELECT * from repair_center where s='$repair' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
$id=$row['repair_id'];
$item=$row['item'];
$describe=$row['description'];
$purchase=$row['purchase'];
$duration=$row['duration'];
$media=$row['picture'];
$user_name=$row['name'];
$user_email=$row['email'];
$user_phone=$row['contact'];
$user_status=$row['status'];

}

?>

<main id="main">
<style>
.prepair{
    text-transform:uppercase;
    font-weight:600;
    color:blue;
} 

.prepair span{
    text-transform:none;
    font-weight:500;
    color:black;   
}

img{
    width:50%;
    height:50%;
} 

.info{
    background: #040b14;
    color:#fff;
    padding:10px 15px;
    border-radius:8px;
    
}

.sender{
    font-size:13px;
    color:#fff;
    padding:5px 10px;
}
.message{
    font-size:16px;
}
.date{
   font-size:10px; 
}
.file a{
    color:#fff;
}
</style>
 <script>
 // Function to prompt for form submission
function promptSubmitForm() {
  var inputValue = document.getElementById("myFormInput").value;
  var confirmation = confirm("Are you sure that you want to carry out the following action: " + inputValue + "?");
      
  if (confirmation) {
    // Submit the form
    return true;
  } else {
    // User selected "Cancel," prevent form submission
    return false;
  }
}

</script> 








    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Repair Requests - (<?php echo $item; ?>)</h2>
          <p>Here are all the logs of every repair request. View details here</p>
        </div>
      
        
<div class="row"><div class="col-lg-6">
<p class="prepair"><img src="../repairs/<?php echo $media; ?>"</p> 
<p class="prepair">REPAIR STATUS:  <span><?php echo $user_status; ?></span></p> 
<p class="prepair">Equipment Name:  <span><?php echo $item; ?></span></p> 
<p class="prepair">Purchased from us?:  <span><?php echo $purchase; ?></span></p>  
<p class="prepair">Usage Duration:  <span><?php echo $duration; ?></span></p>  
<p class="prepair">Damage Description:  <span><?php echo $describe; ?></span></p>  
<p class="prepair">Customer Name:    <span><?php echo $user_name; ?></span></p>  
<p class="prepair">Customer Email:    <span><?php echo $user_email; ?></span></p> 
<p class="prepair">Customer Phone Number:    <span><?php echo $user_phone; ?></span></p> 
  
<div class="col-lg-4"><p>
<?php 
if  ($admin_status !== "superadmin" &&  $user_status =="pending") { 
    echo '<form method="post" id="myForm" onsubmit="return promptSubmitForm();">
<select class="form-control" name="action" id="myFormInput" required>
<option value="" selected> - Select Action -</option>    
<option value="check  in progress" > Accept Request </option> 
<option value="repair in progess" disabled>Repair in progress </option> 
<option value="repair completed" disabled > Mark as Completed </option> 
<option value="repair failed" disabled> Mark as Failed </option> 
<option value="item collected" disabled> Mark as Delivered </option> 
<option value="send additional info" > Send Additional Info </option> 
</select></div></p><p>
<input type="text" name="ordid" value="'.$id.'" required hidden>
<input type="text" name="mail" value="'.$user_email.'" required hidden> 
<button type="submit" name="mark" value="Apply Action" class="submitn">Apply Action</button>
</form>';
}


else if  ($admin_status !== "superadmin" &&  $user_status =="check  in progress") { 
    echo '<form method="post" id="myForm" onsubmit="return promptSubmitForm();">
<select class="form-control" name="action" id="myFormInput" required>
<option value="" selected> - Select Action -</option>    
<option value="check  in progress" disabled > Accept Request </option> 
<option value="repair in progess">Repair in progress </option> 
<option value="repair completed" disabled > Mark as Completed </option> 
<option value="repair failed" disabled> Mark as Failed </option> 
<option value="item collected" disabled> Mark as Delivered </option> 
<option value="send additional info" > Send Additional Info </option> 
</select></div></p><p>
<input type="text" name="ordid" value="'.$id.'" required hidden>
<input type="text" name="mail" value="'.$user_email.'" required hidden> 
<button type="submit" name="mark" value="Apply Action" class="submitn">Apply Action</button>
</form>';
}
else if  ($admin_status !== "superadmin" &&  $user_status =="repair in progess") { 
    echo '<form method="post" id="myForm" onsubmit="return promptSubmitForm();">
<select class="form-control" name="action" id="myFormInput" required>
<option value="" selected> - Select Action -</option>    
<option value="check  in progress" disabled > Accept Request </option> 
<option value="repair in progess" disabled>Repair in progress </option> 
<option value="repair completed"> Mark as Completed </option> 
<option value="repair failed"> Mark as Failed </option> 
<option value="item collected" disabled> Mark as Delivered </option> 
<option value="send additional info" > Send Additional Info </option> 
</select></div></p><p>
<input type="text" name="ordid" value="'.$id.'" required hidden>
<input type="text" name="mail" value="'.$user_email.'" required hidden> 
<button type="submit" name="mark" value="Apply Action" class="submitn">Apply Action</button>
</form>';
}
else if  ($admin_status !== "superadmin" &&  $user_status =="repair completed" ||  $user_status =="repair failed") { 
    echo '<form method="post" id="myForm" onsubmit="return promptSubmitForm();">
<select class="form-control" name="action" id="myFormInput" required>
<option value="" selected> - Select Action -</option>    
<option value="check  in progress" disabled > Accept Request </option> 
<option value="repair in progess" disabled>Repair in progress </option> 
<option value="repair completed" disabled> Mark as Completed </option> 
<option value="repair failed" disabled> Mark as Failed </option> 
<option value="item collected"> Mark as Delivered </option> 
<option value="send additional info" > Send Additional Info </option> 
</select></div></p><p>
<input type="text" name="ordid" value="'.$id.'" required hidden>
<input type="text" name="mail" value="'.$user_email.'" required hidden> 
<button type="submit" name="mark" value="Apply Action" class="submitn">Apply Action</button>
</form>';
}

else if  ($admin_status !== "superadmin" &&  $user_status =="item collected" ) { 
    echo '<form method="post" id="myForm" onsubmit="return promptSubmitForm();">
<select class="form-control" name="action" id="myFormInput" required>
<option value="" selected> - Select Action -</option>    
<option value="check  in progress" disabled > Accept Request </option> 
<option value="repair in progess" disabled>Repair in progress </option> 
<option value="repair completed" disabled> Mark as Completed </option> 
<option value="repair failed" disabled> Mark as Failed </option> 
<option value="item collected" disabled> Mark as Delivered </option> 
<option value="send additional info" > Send Additional Info </option> 
</select></div></p><p>
<input type="text" name="ordid" value="'.$id.'" required hidden>
<input type="text" name="mail" value="'.$user_email.'" required hidden> 
<button type="submit" name="mark" value="Apply Action" class="submitn">Apply Action</button>
</form>';
}


else{
    if($admin_status == "superadmin"){
    
    echo '<form method="post" id="myForm" onsubmit="return promptSubmitForm();">
<select class="form-control" name="action" id="myFormInput" required>
<option value="" selected> - Select Action -</option>    
<option value="check  in progress" > Accept Request </option> 
<option value="repair in progess">Repair in progress </option> 
<option value="repair completed"> Mark as Completed </option> 
<option value="repair failed"> Mark as Failed </option> 
<option value="item collected"> Mark as Delivered </option> 
<option value="send additional info" > Send Additional Info </option> 
</select></div></p><p>
<input type="text" name="ordid" value="'.$id.'" required hidden>
<input type="text" name="mail" value="'.$user_email.'" required hidden> 
<button type="submit" name="mark" value="Apply Action" class="submitn">Apply Action</button>
</form>';
}
}

?>


</p>
<br><br>
</div>




<div class="col-lg-6 info">
<h4>ADDITIONAL INFO</h4>
<p>Messages exchanged during repair will appear here</p>
<?php

$sql = "SELECT * from repair_progress where repair_id='$id' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
$user_send=$row['user'];
$media=$row['picture'];
$color="#FFC700";
if($user_send=="support"){
    $color="#149ddd";
}

$file="";
if($media!==''){
$file='<span class="file"><a href="http://chbluxuryempire.com/repairs/'.$media.'" target="_blank"><u>View attached image here</u></a></span></br>';
}

echo'
<p class="sender" style="background-color:'.$color.'">'.$row['user'].':<br>
<span class="message">'.$row['message'].'</span></br>
'.$file.'
<span class="date">'.$row['date'].'</span></p>

';

}
?>
</div>
</div>

       

<?php


 


if(isset($_POST['mark'])){

$orid = $_POST['ordid'];
$action= $_POST['action'];
$mail= $_POST['mail']; 
$date=date('Y-m-d'); 


if($action=="send additional info"){
 echo "<script>$(document).ready(function(){ $('#myaddModal').modal('show'); });</script> ";
}


else if($action=="repair failed" && $admin_status == "superadmin" ){
    $inserts = mysqli_query($con,"UPDATE repair_center SET status='repair failed' where repair_id='$orid'") or die ('Could not connect: ' .mysqli_error($con)); 
 echo "<script>$(document).ready(function(){ $('#myaddModa').modal('show'); });</script> ";
  

}

else{
$insert = mysqli_query($con,"UPDATE repair_center SET status='$action' where repair_id='$orid'") or die ('Could not connect: ' .mysqli_error($con)); 
if($insert)
{
	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
    $email_from="admin@chbluxuryempire.com";					 
	$email_to = $mail;
    $email_subject = "Repair Progress Updated!";
   $email_message ="
	
<html><body><div style='background-color:#000000; color:#fff; height:700px; width:500px; padding:50px; font-weight:600;'>
<p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' />
<span style='float:right; color:#fff; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$date</span></p><br>
<p style='color:#fff;'>Hello Dear customer,Your item repair progress has been updated. 
<br>ID: <span style='color:#FFC700;'>$orid</span> .Click the link below to check the status of your repair.</p>
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
							 
		 
							 
 
   
// create email headers
         $header =  'From: "CHBLUXURYEMPIRE" <admin@chbluxuryempire.com>'. "\r\n"; 
         $header .= "Cc:admin@chbluxuryempire.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";


$mailsent=mail($email_to, $email_subject,$email_message,$header);
if($mailsent ==true){
echo'<script>alert("Repair status updated successfully!");</script>';
}

header('Refresh:1; url=repairs.php'); 
}
}
}


if(isset($_POST['message'])){
    
$orid = $_POST['ordid'];
$info= $_POST['info'];  
$mail= $_POST['mail']; 

$dates=date('Y-m-d h:i:s');   
$date=date('Y-m-d');     
$targetDir ="../repairs/";
$fileName = basename($_FILES["product"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

 if(isset($_POST["message"]) && !empty($_FILES["product"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
     // Upload file to server
     if(move_uploaded_file($_FILES["product"]["tmp_name"], $targetFilePath)){
     // Insert image file name into database
     $insert = mysqli_query($con,"INSERT into images (file_name, uploaded_on) VALUES ('".$fileName."', NOW())");
 }}}
 
 $submit = mysqli_query($con,"insert into repair_progress (`repair_id`, `message`, `picture`, `date`, `user`, `status`) 
 VALUES ('$orid', '$info', '".$fileName."', '$dates','support', 'sent')") or die ('Could not connect: ' .mysqli_error($con));  



	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from="admin@chbluxuryempire.com";					 
	$email_to = $mail;
    $email_subject = "Repair Progress Updated!";
    $email_message ="
	
<html><body><div style='background-color:#000000; color:#fff; height:700px; width:500px; padding:50px; font-weight:600;'>
<p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' />
<span style='float:right; color:#fff; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$date</span></p><br>
<p style='color:#FFFFFF;'>Hello Dear customer,Your item repair progress has been updated. 
<br>ID: <span style='color:#FFC700;'>$orid</span> .Click the link below to check the status of your repair.</p>
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
							 

   
// create email headers
         $header =  'From: "CHBLUXURYEMPIRE" <admin@chbluxuryempire.com>'. "\r\n"; 
         $header .= "Cc:admin@chbluxuryempire.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";



$mailsent=mail($email_to, $email_subject,$email_message,$header);
if($mailsent ==true){
echo'<script>alert("Additional info sent successfully!");</script>';
}
	////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////
	
header('Refresh:1; url=repairdetails.php'); 


}

?>  

  <?php
  
  if(isset($_POST['submitinfos'])){
     $inform= $_POST['reasonss'];
      $email_to = $email_subject = $email_from = $msg = "";	
	$email_from="admin@chbluxuryempire.com";					 
	$email_to = $user_email;
    $email_subject = "Failed repair Request!";
    $email_message ="
	
<html><body><div style='background-color:#000000; color:#fff; height:700px; width:500px; padding:50px; font-weight:600;'>


<h6 style='color:#fff;'>Hello Dear $user_name,Thank you for choosing CHB luxury repair center.<br><br> <span style='color:#FFC700;'></span> </h6> 


<br><br>
<p style='color:#fff;'>$inform</p>
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
         $header = 'From: "CHBLUXURYEMPIRE" <admin@chbluxuryempire.com>'. "\r\n"; 
         $header .= "Cc:admin@chbluxuryempire.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
  						 
   
$mailsent=mail($email_to, $email_subject,$email_message,$header);
  if($mailsent ==true){
echo"<script>alert('Email Successfully sent!');
window.location.assign('repairs.php')</script>";
   
  }
  }
 
  
  ?>
  
 
    </div>
    </section><!-- End About Section -->
    
    <div class="modal fade" id="myaddModa" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
				<div class="modal-header">
				<h6 style="color:black;">ADD REASON</h6>
				</div>
                <div class="modal-body">
                  <form id="form" name="form" action="" method="post" enctype="multipart/form-data" > 
                      <div class="row mb-3">
                      <div class="col-md-12">
                     
					 <p><textarea class="form-control" placeholder="*why it failed" name="reasonss" required></textarea></p>
	                 
                      </div></div></div>
					  <div class="modal-footer">
					  <input id="submit" name="submitinfos" class="btn btn-sm btn-primary shadow-sm" type="submit" value="Send"></form>
                    </div>
                  </div>
                </div></div>
               </div>
            

 	<div class="modal fade" id="myaddModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
				<div class="modal-header">
				<h6 style="color:black;">Send Additional Info</h6>
				</div>
                <div class="modal-body">
                  <form id="form" name="form" action="" method="post" enctype="multipart/form-data" > 
                      <div class="row mb-3">
                      <div class="col-md-12">
                     <input type='text' name='ordid' value='<?php echo $id;?>' required hidden> 
                      <input type='text' name='mail' value='<?php echo $user_email;?>' required hidden> 
					 <p><textarea class="form-control" placeholder="*Type here" name="info" required></textarea></p>
	                 <p><label>Add an image if required</label><input type="file"  name="product"  class="form-control" /></p>
                      </div>
					  <div class="modal-footer">
					  <input id="submit" name="message" class="btn btn-sm btn-primary shadow-sm" type="submit" value="Send"></form>
                    </div>
                  </div>
                </div></div>
               </div><!-- End Modal Dialog Scrollable-->  
               
            
  </main><!-- End #main -->
  <?php include 'footer.php'; ?>