<?php 
 include 'connect_to_mysqli.php';
if(isset($_POST['submit'])){

$name=$_POST['name'];
$email=$_POST['email'];
$db=$_POST['date'];
$reason=$_POST['reason'];
$reasons=$_POST['reasons'];
$hour=$_POST['hour'];
$people=$_POST['people'];
$tele=$_POST['phone'];


  $mail_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';


 if ($reason == "" && $reasons == "")
	     {
		   $er='At least one of the reason field must be provided' ;
		 }
		
		 
	
	      elseif (
           
                 (!preg_match($mail_exp,$email))   )
       
                  {  $err1= 'enter a valid email to continue'; 
           
                  }
                  
                  
                  else{
         if ($reason !== "" || $reasons !== "")
	     {
$date=date('Y-m-d');
$code=substr(mt_rand(), 0, 4);
$id='RE'.$code;

		 $submit = mysqli_query($con,"insert into rental_center (rental_id,name,email,dateregistered,datetouse,firstreason,secondreason,duration,people,confirmation,rentalhistory,phone) values ('$id', '$name', '$email', '$date', '$db','$reason', '$reasons', '$hour', '$people', 'pending', 'no','$tele')")or die ('Could not connect: ' .mysqli_error($con));
		   }
           }
                 
                 
                 
                 
                  if ($submit){
                 


	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
    $email_to = $email_subject = $email_from = $msg = "";	
	$email_from="admin@chbluxuryempire.com";					 
	$email_to = $email;
    $email_subject = "Rental Request Sent!";
    $email_message ="
	
<html><body><div style='background-color:#000000; color:#fff; height:700px; width:500px; padding:50px; font-weight:600;'>
<p><img src='http://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' />
<span style='float:right; color:#fff; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$date</span></p><br>
<p style='color:#fff;'>Hello Dear $name,Thank you for choosing CHB luxury rental for beauty and skill training center.We will get back to you in the next 24-72hrs when your rental request has been reviewed 
<br>However this is your decide tracking code <span style='color:#FFC700;'>$id</span> to check the status of your rental via the link below.<br></p>
<p><a href='https://chbluxuryempire.com/rent_progress.php?rentid=$id' style='color:#FFC700; text-decoration:underline;'>Check Rental Progress</a></p> 


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
         $header = 'From: "CHBLUXURYEMPIRE" <admin@chbluxuryempire.com>'. "\r\n"; 
         $header .= "Cc:admin@chbluxuryempire.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
  						 
   
$mailsent=mail($email_to, $email_subject,$email_message,$header);
  if($mailsent ==true){

	////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////
	
	
	
	
	
		///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$commentss = $email_tos = $email_subjects = $email_froms = $email_messages = "";	
	$email_froms="admin@chbluxuryempire.com";					 
	$email_tos = "corporatehair.sales@gmail.com";
    $email_subjects = "New Rental Request";
    $email_messages ="
    
    
     <html><body><div style='background-color:#000000; color:#fff; height:700px; padding:50px; width:500px;'>
      <p><img src='http://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' /></p><br><br>
     <p>Hello Dear Admin,There has been a new rental for beauty and skill training center request!</p><br><br>
     <pstyle='color:#FFFFFF;'>from $name </p>

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
         $headers = "From: admin@chbluxuryempire.com \r\n";
         $headers .= "Cc:admin@chbluxuryempire.com \r\n";
         $headers .= "MIME-Version: 1.0\r\n";
         $headers .= "Content-type: text/html\r\n";



$mailsents=mail($email_tos, $email_subjects,$email_messages,$headers);
if($mailsents){
$_SESSION['rent_id']=$id;
header("location: thanks.php"); 
}
}}
else{
$fail='<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';
	}
	////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////

}









			