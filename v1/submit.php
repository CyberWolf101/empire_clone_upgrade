<?php
  include "connect_to_mysqli.php";
  	 if(isset($_POST['submit'])){
  	     $dear=date("Y-m-d");
  $gift=$_POST['gift'];
  $total=$_POST['total'];
  $first=$_POST['first'];
  $email=$_POST['email'];
  $sql = "SELECT * FROM giftcard WHERE cardno='$gift'";
                      $sql2 = mysqli_query($con,$sql);
                         if (mysqli_affected_rows($con) == 0)
                     {
                         
                       $err1= ' <font color="red"> invalid giftcard </font>';   
                     }
                         
  
   else{
                  
                    
		   while($row = mysqli_fetch_array($sql2))
			  {
				
			$card = $row['cardno'];
			$price= $row['price'];
			}
			if($price < $total ){
			   $err=' <font color="red"> insufficient fund </font>';
			}
				

					//Check if item exists



else{
   $det= $price-$total;
   $detty=$det;
    $insert = mysqli_query($con,"UPDATE giftcard SET price= '$detty' where cardno='$gift'") or die ('Could not connect: ' .mysqli_error($con)); 
 
}
}

if($insert){
        
        	$commentss = $email_tos = $email_subjects = $email_froms = $email_messages = "";	
	$email_froms="admin@chbluxuryempire.com";					 
	$email_tos = "$email";
    $email_subjects = "Debit Alert";
    $email_messages ="
    
    
     <html><body><div style='background-color:#000000; color:#fff; height:700px; padding:50px; width:500px;'>
      <div> $image_name</div><br><br>
     <p>Hello Dear $first,This is to inform you that a transaction has occured on your gift card!</p><br><br>
     
     <P><table border='1px' bordercolor='#ffffff' cellpadding='10' style='color:#FFFFFF;' width='500px'>
 <tr style='border-bottom:#FFFFFF solid; font-size:14px; font-weight:500;'><td style='color:#fff; text-align:center;'>Name</td><td style='color:#FFC700; font-size:14px; font-weight:500;' >$first</td></tr>
 <tr><td style='color:#fff; text-align:center;'>Gift card No</td><td style='color:#FFC700; font-size:14px; font-weight:500;'>$gift</td>
 </tr>
 <tr><td  style='color:#fff; text-align:center;'>Transaction type</td><td>Debit Alert</td></tr>
 <tr><td  style='color:#fff; text-align:center;'>Transaction Amount</td><td>$total</td></tr>
 <tr><td  style='color:#fff; text-align:center;'>Date</td><td>$dear</td></tr>
 <tr><td  style='color:#fff; text-align:center;'>Balance</td><td>$detty</td></tr>
 </table> </p>

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
   $headers = 'From: "CHBLUXURYEMPIRE" <admin@chbluxuryempire.com>'. "\r\n"; 
         $headers .= "Cc:admin@chbluxuryempire.com \r\n";
         $headers .= "MIME-Version: 1.0\r\n";
         $headers .= "Content-type: text/html\r\n";
   



$mailsents=mail($email_tos, $email_subjects,$email_messages,$headers);
if($mailsents){

$alert='<div class="alert alert-success">
    <strong>Successful!</strong> Payment.
  </div>';


}
else{
$fail='<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';
	}
}
}
  
  ?>