<?php include"header.php"; 
include "repair_center.php"; 
$repairid=$_SESSION['repair_id'];


$sql = "SELECT * from repair_center where repair_id='$repairid' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
if (mysqli_affected_rows($con) == 0) {
    $active_log = 0;
} else {
while ($row = mysqli_fetch_array($sql2)){
$id=$row['repair_id'];
$item=$row['item'];
$describe=$row['description'];
$purchase=$row['purchase'];
$duration=$row['duration'];
$media=$row['picture'];
$user_name=$row['name'];
$user_email=$row['email'];
$user_phone=$row['contact'];
$status=$row['status'];
$active_log = 1;
}
}
?>
<main>
         
 <style>
#chat{
	padding-left:0;
	margin:0;
	list-style-type:none;
	overflow-y:scroll;
	height:535px;
	border-top:2px solid #fff;
	border-bottom:2px solid #fff;
}
#chat li{
	padding:10px 30px;
}
#chat h2,#chat h3{
	display:inline-block;
	font-size:13px;
	font-weight:normal;
}
#chat h3{
	color:#bbb;
}
#chat .entete{
	margin-bottom:5px;
}

#chat .me{
	text-align:left;
}

#chat .me .triangle{
		border-color: transparent transparent #6fbced transparent;
		margin-left:375px;
}

   /*
 .info{
  background: #040b14; 
    color:#fff;
    padding:10px 15px;
    border-radius:8px;
    
}
*/
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
.btn {
  display: inline-block;
  padding:8px 20px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#FFC700;
  margin-bottom:20px;
box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
  cursor:pointer;
  float:right;
}
.btn:hover {
  display: inline-block;
  padding:8px 20px ;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#000000;
  margin-bottom:20px;
float:right;
  cursor:pointer;
}

.status{
 color:#FFC700;
 font-size:32px;
 text-transform:uppercase;
 
}

.prepair{
    text-transform:uppercase;
    font-weight:600;
    color:#FFC700;
} 

.prepair span{
    text-transform:none;
    font-weight:500;
    color:white;   
}

img{
    width:50%;
    height:50%;
} 
/*
.info{
    background:#FFC700;
    color:#fff;
    padding:10px 15px;
    border-radius:3px;
    height:800px;
}
*/

.sender{
    font-size:13px;
    color:#fff;
    padding:5px 10px;
}
.message{
    font-size:16px;
}
.progress-text{
   font-size:13px;
}

.date{
    font-size:10px;
}
.file a{
    color:#fff;
}

.btn-buya {
  display: inline-block;
  padding:5px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#FFC700;
  margin-bottom:20px;
  width:300px;
  
}
.btn-buya:hover {
  display: inline-block;
  padding:10px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#000000;
  margin-bottom:20px;
  width:300px;
  
}
</style>

<?php
if(isset($_POST['message'])){
    
$orid = $_POST['ordid'];
$info= $_POST['info'];  
$mail= $_POST['mail']; 

$dates=date('Y-m-d h:i:s');   
$date=date('Y-m-d');     
$targetDir ="repairs/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

 if(isset($_POST["message"]) && !empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
     // Upload file to server
     if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
     // Insert image file name into database
     $insert = mysqli_query($con,"INSERT into images (file_name, uploaded_on) VALUES ('".$fileName."', NOW())");
 }}}
 
 $submit = mysqli_query($con,"insert into repair_progress (`repair_id`, `message`, `picture`, `date`, `user`, `status`) 
 VALUES ('$orid', '$info', '".$fileName."', '$dates','customer', 'sent')") or die ('Could not connect: ' .mysqli_error($con));  
}

if($submit){
    


	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from="$mail";					 
	$email_to = "admin@chbluxuryempire.com";
    $email_subject = "Additional info from customer!";
    $email_message ="
	
<html><body><div style='background-color:#000000;  padding:20px; font-weight:600;'>
<p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' />
</p><br>
<p style='color:#FFFFFF;'>Message: 
<br><br>
<p style=' color:#fff;'>$info</p></p>
<p style=' color:#fff;'>Tracking id:$id</p></p>
<br>
 
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
         $email_message.="Image attached";
$semi_rand = md5(uniqid(time()));
$headers = "From: ".$email_from;
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    $headers .= "\nMIME-Version: 1.0\n" .
    "Content-Type: multipart/mixed;\n" .
    " boundary=\"{$mime_boundary}\"";


   $fileName = basename($_FILES["file"]["name"]);
           $strContent = chunk_split(base64_encode(file_get_contents($targetFilePath , $_FILES["file"]["tmp_name"]))); 


    $email_message .= "This is a multi-part message in MIME format.\n\n" .
    "--{$mime_boundary}\n" .
    "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" .
    $email_message .= "\n\n";


    $email_message .= "--{$mime_boundary}\n" .
    "Content-Type: application/octet-stream;\n" .
    " name=\"{$fileName}\"\n" .
    //"Content-Disposition: attachment;\n" .
    //" filename=\"{$fileatt_name}\"\n" .
    "Content-Transfer-Encoding: base64\n\n" .
    $strContent  .= "\n\n" .
    "--{$mime_boundary}--\n";
 


$mailsent=mail($email_to, $email_subject,$email_message,$headers);

	////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////
if($mailsent ==true){
echo"<script>alert(Message Successfully sent!');</script>";
}
	////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////
	




}


?>

		
		<?php
	     if ($active_log=="0")
		   {
		    ?>   
		   <div style="margin-top:100px; color:#FFFFFF; padding:50px;">
		  <div class="justify-content-center" align="center"><p>
		  <p>This repair ID is invalid</p>  
		   <p><a href="index.php"  class="form-control btn-buya">BACK TO HOME</a> </p>
		  </div></div>
		       
		      <?php  
            }
            else{
	     ?>
		  
		  <div style="margin-top:100px; color:#FFFFFF; padding:50px;">
		  <div class="justify-content-center" align="center"><p><span class="status">Status: <span style="font-size:25px;"><?php echo $status; ?></span></span>
		  <br></p></div>
		  <div class="row justify-content-center">
		  
		  <?php
		  if($status =="pending"){
		  ?>
		     <div class="col-lg-6 info">
        <p><div class="progress" style="text-align: center !important;">
        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">10%</div>
    <?php  
		  }
		  if($status == "check  in progress"){
		  
    ?>
     <div class="col-lg-6 info">
        <p><div class="progress" style="text-align: center !important;">
        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
        </div><span class="progress-text"><?php echo $status; ?></span>
         </p>
	    </div>
	    
	    <?php  }
	    if($status == "repair in progess"){
	      ?>  
	   
	      <div class="col-lg-6 info">
        <p><div class="progress" style="text-align: center !important;">
        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
        </div><span class="progress-text"><?php echo $status; ?></span>
         </p>
	    </div>
	    <?php  
		  }
		  
		  if($status == "repair completed"){
	        
	  ?>
	      <div class="col-lg-6 info">
        <p><div class="progress" style="text-align: center !important;">
        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
        </div><span class="progress-text"><?php echo $status; ?></span>
         </p>
	    </div>

    <?php
    
		  }
	    if($status == "repair failed"){
	        
	   ?>
	      <div class="col-lg-6 info">
        <p><div class="progress" style="text-align: center !important;">
        <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">0%</div>
        </div><span class="progress-text"><?php echo $status; ?></span>
         </p>
	    </div>
	    <?php  
	    }
	    if($status == "item collected"){
	        
	  ?>
	  
	    <div class="col-lg-6 info">
        <p><div class="progress" style="text-align: center !important;">
        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
       </div><span class="progress-text"><?php echo $status; ?></span>
         </p>
	    </div>
	    
	  <?php }  ?>
	    
	    </div>
	    
	    
	    
         </p>
         <br>
         	   	 <div class="row justify-content-center">
	   
	    <div class="col-lg-6" style="
	
	padding:8px 20px;
	background:black;">
	       <h4>ADDITIONAL INFO MESSAGES</h4>
<p> All Messages</p>
<?php

$sql = "SELECT * from repair_progress where repair_id='$id' ORDER BY s DESC  ";
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
<ul style="
	list-style-type:none; "  >
		
			<li style="text-align:left; style="">
				

				<div style="background-color:'.$color.';" class="sender">
			<span class="message">'.$row['message'].'</span></br>
'.$file.'
<span class="date">'.$row['date'].'</span>
				</div>
				
			</li></ul>

';

}
?>
	       </div>
	   </div>
	   <br><br>
		 <div class="row justify-content-center">
         <div class="col-lg-6 info">
        <form id="form" name="form" action="" method="post" enctype="multipart/form-data" > 
                     <h6 style="color:#fff;">Send Additional Info</h6>
                     <input type='text' name='ordid' value='<?php echo $id;?>' required hidden> 
                      <input type='text' name='mail' value='<?php echo $user_email;?>' required hidden> 
					 <p><textarea class="form-control" placeholder="*Type here" name="info" style="box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;" required></textarea></p>
	                 <p><label>Add an image if required</label><input type="file"  name="file"  class="form-control" /></p>
                     
					 
					  <input id="submit" name="message" class="btn btn-sm btn-primary shadow-sm" type="submit" value="Send"></form>
	    </div>
	   
	 
	   
	   
	   
	  
	   
	   
	   </div>
	   <div class="row justify-content-center">
         <div class="col-lg-6 info">
         
                     <h2>Repair Requests</h2>
          <p>Here are all the logs of your repair request. View details here</p>
          <br><br>
          <p class="prepair"><img src="repairs/<?php echo $media; ?>"</p> 
<p class="prepair">REPAIR STATUS:  <span><?php echo $status; ?></span></p> 
<p class="prepair">Equipment Name:  <span><?php echo $item; ?></span></p> 
<p class="prepair">Purchased from us?:  <span><?php echo $purchase; ?></span></p>  
<p class="prepair">Usage Duration:  <span><?php echo $duration; ?></span></p>  
<p class="prepair">Damage Description:  <span><?php echo $describe; ?></span></p>  
<p class="prepair">Customer Name:    <span><?php echo $user_name; ?></span></p>  
<p class="prepair">Customer Email:    <span><?php echo $user_email; ?></span></p> 
<p class="prepair">Customer Phone Number:    <span><?php echo $user_phone; ?></span></p> 
	    </div>
	   
	 
	   
	   
	   
	  
	   
	   
	   </div>

	   </div>
		  

 		  
		  
		
  
		  
		  
		  
		  
		  
		  </div>
		 <?php
	   
	   } ?>   
       <?php include "footer.php"; ?>