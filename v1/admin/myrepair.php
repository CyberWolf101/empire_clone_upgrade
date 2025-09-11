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
<?php
include"admin_checker1.php";
 session_start(); 

 $code = $_SESSION['user'];

 $sql3 = "SELECT * from admob where email = '".$code."'  ";
		    $sql4= mysqli_query($con,$sql3);
			while($row = mysqli_fetch_array($sql4))
				    
					{
										  $ids = $row["s"];   					
					                      $name = $row['name'];
					                      $email = $row['email'];
					                      $admin_status = $row['status'];
					  
					  
					  
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
<input type="text" name="ordid "value="<?php echo $repair;?>" required hidden>
<input type="text" name="mail" value="<?php echo $user_email;?>" required hidden> 
<button type="submit" name="mark"value="Apply Action" class="submitn">Apply Action</button>
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
<input type="text" name="ordid "value="<?php echo $repair;?>" required hidden>
<input type="text" name="mail" value="<?php echo $user_email;?>" required hidden> 
<button type="submit" name="mark"value="Apply Action" class="submitn">Apply Action</button>
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
<input type="text" name="ordid "value="<?php echo $repair;?>" required hidden>
<input type="text" name="mail" value="<?php echo $user_email;?>" required hidden> 
<button type="submit" name="mark"value="Apply Action" class="submitn">Apply Action</button>
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
<input type="text" name="ordid "value="<?php echo $repair;?>" required hidden>
<input type="text" name="mail" value="<?php echo $user_email;?>" required hidden> 
<button type="submit" name="mark"value="Apply Action" class="submitn">Apply Action</button>
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
<input type="text" name="ordid "value="<?php echo $repair;?>" required hidden>
<input type="text" name="mail" value="<?php echo $user_email;?>" required hidden> 
<button type="submit" name="mark"value="Apply Action" class="submitn">Apply Action</button>
</form>';
}


else{
    
    
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
<input type="text" name="ordid "value="<?php echo $repair;?>" required hidden>
<input type="text" name="mail" value="<?php echo $user_email;?>" required hidden> 
<button type="submit" name="mark"value="Apply Action" class="submitn">Apply Action</button>
</form>';
}
?>


</p>
<br><br>
</div>




<div class="col-lg-6 info">
<h4>ADDITIONAL INFO</h4>
<p>Messages exchanged during repair will appear here</p>
<?php

$sql = "SELECT * from repair_progress where repair_id='$id' ORDER BY s ";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
$user_send=$row['user'];
   $color="#FFC700";
if($user_send=="support"){
    $color="#149ddd";
}

$file="";
if(isset($row['picture'])){
$file='<span class="file"><a href="http://chbluxuryempire.com/repairs/'.$row['picture'].'" target="_blank"><u>View attached image here</u></a></span></br>';
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
if($action=="send additional info"){
 echo "<script>$(document).ready(function(){ $('#myaddModal').modal('show'); });</script> ";
}
else{
$insert = mysqli_query($con,"UPDATE repair_center SET status= '$action' where s='$orid'") or die ('Could not connect: ' .mysqli_error($con)); 
	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from= "admin@chbluxuryempire.com";					 
	$email_to = $mail;
    $email_subject = "Repair Progress Updated!";
    $comments ="
	
<html><body><div style='background-color:#000000; color:#fff; height:700px; width:500px; padding:50px; font-weight:600;'>
<p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' />
<font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$date</font></p><br><br>
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
							 
	  function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
				 
							 
    $email_message ="";					 
    $email_message .= "  ". clean_string($user)."\n";
    $email_message .= clean_string($comments)."\n";
 
   
// create email headers
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $header .= "CC: ".$cc."\r\n";
    $headers .= 'From: admin@chbluxuryempire.com' . "\r\n";
    $headers .=  'From: "CHBLUXURY ACADEMY" <admin@chbluxuryempire.com>'. "\r\n"; 


if(!@mail($email_to, $email_subject, $email_message, $headers)){

echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';
	}
	////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////
echo'<script>alert("Repair status updated successfully!");</script>';
header('Refresh:1; url=repairs.php'); 
}
}

if(isset($_POST['submitinfo'])){
    
$orid = $_POST['ordid'];
$info= $_POST['info'];  
$mail= $_POST['mail']; 

$date=date('Y-m-d h:i:s');   
    
$targetDir ="../repairs/";
$fileName = basename($_FILES["product"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

 if(isset($_POST["submitinfo"]) && !empty($_FILES["product"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
     // Upload file to server
     if(move_uploaded_file($_FILES["product"]["tmp_name"], $targetFilePath)){
     // Insert image file name into database
     $insert = mysqli_query($con,"INSERT into images (file_name, uploaded_on) VALUES ('".$fileName."', NOW())");
 }}}
 
 $submit = mysqli_query($con,"insert into repair_progress (`repair_id`, `message`, `picture`, `date`, `user`, `status`) 
 VALUES ('$orid', '$info', '".$fileName."', '$date','support', 'sent')") or die ('Could not connect: ' .mysqli_error($con));  



	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from= "admin@chbluxuryempire.com";					 
	$email_to = "kanyin500@gmail.com";
    $email_subject = "Repair Progress Updated!";
    $comments ="
	
<html><body><div style='background-color:#000000; color:#fff; height:700px; width:500px; padding:50px; font-weight:600;'>
<p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' />
<font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$date</font></p><br><br>
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
							 
	  function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
				 
							 
    $email_message ="";					 
    $email_message .= "  ". clean_string($user)."\n";
    $email_message .= clean_string($comments)."\n";
 
   
// create email headers
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $header .= "CC: ".$cc."\r\n";
    $headers .= 'From: admin@chbluxuryempire.com' . "\r\n";
    $headers .=  'From: "CHBLUXURY ACADEMY" <admin@chbluxuryempire.com>'. "\r\n"; 


if(!@mail($email_to, $email_subject, $email_message, $headers)){

echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';
	}
	////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////
	


echo'<script>alert("additional info sent successfully!");</script>';
header('Refresh:1; url=repairdetails.php'); 



}


?>  
  
  
  
    </div>
    </section><!-- End About Section -->

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
					  <input id="submit" name="submitinfo" class="btn btn-sm btn-primary shadow-sm" type="submit" value="Send"></form>
                    </div>
                  </div>
                </div></div>
               </div><!-- End Modal Dialog Scrollable-->  
  </main><!-- End #main -->
  <?php include"footer.php" ?>