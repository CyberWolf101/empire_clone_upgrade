<?php include"header.php"; ?>
<!---...This is for Microlashing section - single.. --->

<style>


.ter{
background-color:#fff;
margin-bottom:10px;
outline:none;
border:none;
padding:10px;

}


span{

font-size:14px;
font-weight:900;


}
.img{
max-width:30%;
max-height:30%;
border-radius:50%;
background-color:#000000;
}
/* My  */
.submitn{
  
 background:#FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px 30px 11px 30px;
  font-size: 14px;
  font-weight: 600;
  outline:none;
  width:300px;
 
}

.submitn:hover{
  background: #000000;
  color: #fff;
}

.vertical-alignment-helper {
display:table;
height: 100%;
width: 100%;
pointer-events:none;}

.vertical-align-center {
/* To center vertically */
display: table-cell;
vertical-align: middle;
pointer-events:none;}


.close{
color:#0000FF;
text-shadow:none;
opacity:1;
font-size:30px;
}


</style>
 <?php
 session_start();
?>







  <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#FFFFFF;" align="center">
        
		   <?php 
		  global $tx_ref;
 
$ref = $_GET['tx_ref'];
$reed = $_GET['status'];
 
    $card= "CHB".$randomString = substr(md5(microtime()), 0, 7);
   
      
  include "connect_to_mysqli.php";
  $string= trim($ref);
 if ($reed == "cancelled")
			  {
			  echo '<h3>Payment Cancelled. <br>Please try Again</h3>
			  <p><a href="https://chbluxuryempire.com/" style="color:#FFC700;" >Click here to Home</a><p>';
				  } 
			 else 
			  {
			      
			       $sql = "SELECT * FROM cart WHERE id ='$string'";
                   $sql2 = mysqli_query($con,$sql);
			      	     while($row = mysqli_fetch_array($sql2))
				    
					{
					  $id = $row["s"]; 
					  $orid = $row["id"];   					
					   $emails= $row["email"];
					   $named= $row["name"];
					   $mob=$row["phone"];
					   $sat=$row["nom"];
					   $dear=$row["date"];
					   
					   
					   
					 
					 
				   
					 }
					 
					 header('Refresh:3; url=index.php');
			       echo'<h2>PAYMENT SUCCESSFUL</h2>
          <p>
		  Here is your booking ID - <span style="font-size:large; text-transform:none; color:#FFC700;">'.$ref .'</span><br></p>
<p style="font-size:17px; font-weight:700;">Your appointment No have been sent to you which will be provided for the receptionist upon arrival.
kindly check your email<br><span ><a href="https://chbluxuryempire.com/" style="color:#FFC700;" >Click here to Home</a><p>';

                    $far ="Paid";
					 $fa="Confirmed";
				
		
				
				
				
					
					 $insert = mysqli_query($con,"UPDATE cart SET status= '$far' where id='$orid' && timef!='' ") or die ('Could not connect: ' .mysqli_error($con)); 
					
					$insert = mysqli_query($con,"UPDATE cart SET app= '$fa' where id='$orid'") or die ('Could not connect: ' .mysqli_error($con));   
					$insert = mysqli_query($con,"UPDATE cart SET meth= 'Card' where id='$orid'") or die ('Could not connect: ' .mysqli_error($con)); 					
	                $insert = mysqli_query($con,"UPDATE enter SET status= 'Paid' where orderid='$orid'") or die ('Could not connect: ' .mysqli_error($con));

if($status="paid"){
    
    
   
    $insert = mysqli_query($con,"UPDATE giftcard SET cardno='$ref' where id='$orid'") or die ('Could not connect: ' .mysqli_error($con)); 
}



	
	
		if($sat == "1")
				{
				    $sat="Single Booking";
				}
				else if ($sat == "2")
				{
				    $sat="Couple Booking";
				}
				else if ($sat > 2)
				{
				    $sat="Family Booking";
				}
				
				
				
						
				
			$sql = "SELECT sum(price) from cart where id='$orid' ";
$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))

$p=$row[0];

			$sql = "SELECT sum(priced) from enter where orderid='$orid' ";
$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))

$k=$row[0];

$price=$k+$p;
	

			 $sql = "SELECT * FROM cart where id='$orid' && timef !=''";
                      $sql2 = mysqli_query($con,$sql);
		   while($row = mysqli_fetch_array($sql2))
			  {  
				
			$seves[] = $row['service'];
				$prices[] = $row['price'];
					$timefs[] = $row['timef'];
						$timets[] = $row['timet'];
							$dates[] = $row['date'];
								$staffs[] = $row['staff'];
			}
			
			
			
foreach ($seves as $kep => $value) {

    $hem .= "<tr><td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $seves[$kep]. "</td>
    <td  style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $prices[$kep]. "</td>
    <td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $dates[$kep]. " </td>
    <td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $timefs[$kep]. " - " . $timets[$kep]. " </td>
    <td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $staffs[$kep]. "</td></tr>";
}





				
				
			 $sql = "SELECT * FROM enter where orderid='$orid' ";
                      $sql2 = mysqli_query($con,$sql);
		   while($row = mysqli_fetch_array($sql2))
			  {  
				
			$name[] = $row['id'];
				$surname[] = $row['no'];
					$address[] = $row['priced'];
			}
			
			
			
foreach ($name as $key => $value) {

    $html .= "<tr><td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $name[$key]. "</td><td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $surname[$key]. "</td>
    <td  style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $address[$key]. "</td>";
}			
				
				
				
				
				
				
				

	

	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from="admin@chbluxuryempire.com";					 
	$email_to = "$emails";
    $email_subject = "Appointment Successfully Booked!";
             $comments ="
		  <center><div style='background-color:#000000; color:#FFFFFF; height:1000px; width:500px;'>
	 <div style='background-color:#000000; width:500px; height:150px;'>
	 <p style='text-align:left;'>
	<img src='http://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='60px;' style='margin-top:13px;'/>
</p>
	
	<p style='color:#FFC700; font-size:30px;'>Booking Notification</p> 
	 
	 </div>
 <p style='color:white;'>Hello Dear Client,Here is your appointment booking No which will be provided to the receptionist
 upon arrival to get your booked services and other items.Thank you.</p>
 <p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#FFFFFF;' width='500px'>
 <tr style='border-bottom:#FFFFFF solid;'><td>Booking</td><td style='color:#FFC700;' colspan='2' >No - $orid</td><td  colspan='2'>Type - $sat</td></tr>
 <tr><td  style='color:#fff; text-align:center;'>Service</td><td>Price</td><td>Date</td><td>Time</td><td>Staff</td></tr>
 $hem
 </table>
</p> 


<p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#FFFFFF;' width='500px'>
 <tr><td  style='color:#fff; text-align:center;'>Your Items</td><td>Quantity</td><td>Price</td></tr>
 $html 
 </table>
</p> 
 
<br><br> <p style='text-align:center;'><a href='chbluxuryempire.com' style='color:#FFC700; '>CHBLuxuryEmpire</a></p>
 </div></center>
	
	


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
$headers .= 'From: admin@chbluxuryempire.com' . "\r\n";


if(!@mail($email_to, $email_subject, $email_message, $headers)){

echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';
	}
	////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////
 }
			  
			  
			session_destroy();

	?>
	
         
          </span><br><br>

     
 
       
          
		  
		  </div>
        </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->
 <?php include"footer.php"; ?>