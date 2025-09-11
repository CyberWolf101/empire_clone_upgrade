<?php include "header.php"; ?>

<!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#FFFFFF;" align="center">
          
<?php 
global $tx_ref;
 
$ref = $_GET['tx_ref'];
$reed = $_GET['status'];
 
$string=trim($ref);
if ($reed == "cancelled")
{
echo "<h3>Payment Cancelled. Please try Again</h3><p><a href='index.php' style='color:#FFC700;;'><u>Back To Menu</u></a></p>";
 } 
			 
			 
			 else 
			  {
			      
header('Refresh:3; url=index.php');
echo'<h2>PAYMENT SUCCESSFUL</h2>
<p>Here is your PickUp No- <span style="font-size:large; text-transform:none; color:#FFC700;">'.$string.' </span><br></p>
<p style="font-size:14px; font-weight:500;">Your PickUp No have been sent to you which will be provided for the store upon arrival to get your package.
kindly check your email<br><span ><a href="https://chbluxuryempire.com/" style="color:#FFC700;" >Click here to Home</a><p>';



			//Total Cost		
			$sql = "SELECT sum(price*quantity) from delta_cart where order_id='$string' ";
            $sql2 = mysqli_query($con,$sql);
			while($row = mysqli_fetch_array($sql2))
            $price=$row[0]; 
            
			
			 $sql = "SELECT (price*quantity) as total,meal_id, quantity,email FROM delta_cart where order_id='$string' ";
             $sql2 = mysqli_query($con,$sql);
		     while($row = mysqli_fetch_array($sql2))
			  {  
				
			        $name[] = $row['meal_id'];
				    $quantity[] = $row['quantity'];
					$total[] = $row['total'];
					$emails = $row['email'];
					$customer = $row['name'];
					$phone = $row['phone'];
					$dear = $row['date'];
			}
			
			
			
foreach ($name as $key => $value) {

    $html .= "<tr><td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $name[$key]. "</td><td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $quantity[$key]. "</td>
    <td  style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $total[$key]. "</td></tr>";
}
			
		
$insert = mysqli_query($con,"UPDATE delta_cart SET status= 'Paid' where order_id='$string'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE delta_cart SET app= 'Confirmed' where order_id='$string'") or die ('Could not connect: ' .mysqli_error($con));   
	
	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $email_message= "";	
	$email_from="admin@chbluxuryempire.com";					 
	$email_to = "$emails";
    $email_subject = "Items Purchased Successfully!";
    $email_message ="

	
<div style='background-color:#000000; color:#fff; height:700px; width:500px; padding:50px; font-weight:600;'>
<p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' />
<font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>Total Cost: &#8358;$price <br>$dear</font></p><br><br>
<p style='color:#fff;'>Hello Dear $customer,Here is your PickUp No which will be provided to the store upon arrival to get your  items.Thank you.<br> </p>
 <p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#FFFFFF;' width='500px'>
 <tr style='border-bottom:#FFFFFF solid; font-size:14px; font-weight:500;'><td>PickUp No</td><td style='color:#FFC700; font-size:14px; font-weight:500;' colspan='2' >$string</td></tr>
 <tr><td  style='color:#fff; text-align:center;'>Your Items</td><td>Quantity</td><td>Price</td></tr>
 $html 
 </table>
 
</p> 


<br><br>
<p style='text-align:center; color:#fff;'>
  Visit our website:
  <a href='https://chbluxuryempire.com/' style='color:#FFC700; text-decoration:underline;'>
DELTA KITCHEN
  </a>
</p>
</div>	


";
  // create email headers
         $header =  'From: "DELTA KITCHEN" <admin@chbluxuryempire.com>'. "\r\n"; 
         $header .= "Cc:admin@chbluxuryempire.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";


if(!@mail($email_to, $email_subject, $email_message, $header)){

echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';
	}
	
	////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////
	
	
	
	
	
		///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $email_message = "";	
	$email_from="admin@chbluxuryempire.com";					 
	$email_to = "corporatehair.sales@gmail.com";
    $email_subject = "New Purhcase From Delta Kitchen!";
    $email_message ="
    
    
       <p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' /></p><br><br>
     <p>Hello Dear Admin,There has been a new delta kicthen order!</p><br><br>
     
     <p style='color:white;'>
 <br>Customer Name :  $customer
 <br>Phone Number:   $phone</p>
 <p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#FFFFFF;' width='500px'>
 <tr style='border-bottom:#FFFFFF solid; font-size:14px; font-weight:500;'><td>PickUp No</td><td style='color:#FFC700; font-size:14px; font-weight:500;' colspan='2' >$string</td></tr>
 <tr><td  style='color:#fff; text-align:center;'>Items</td><td>Quantity</td><td>Price</td></tr>
 $html 
 </table>
 
</p> 
  <p style='text-align:center; color:#fff;'>
  Visit our website:
  <a href='https://chbluxuryempire.com/' style='color:#FFC700; text-decoration:underline;'>
 CHB LUXURY ACADEMY
  </a>
</p>
	

	
	


";			 
							 
   // create email headers
         $header =  'From: "DELTA KITCHEN" <admin@chbluxuryempire.com>'. "\r\n"; 
         $header .= "Cc:admin@chbluxuryempire.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";


if(!@mail($email_to, $email_subject, $email_message, $header)){

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
    
    
    
    
<?php include "footer.php"; ?>