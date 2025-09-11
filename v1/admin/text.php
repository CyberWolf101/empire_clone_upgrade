<?php include"header.php"; ?>
<main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>All Rental Requests</h2>
          <p>Here are all the logs of every rental request. View details here</p>
        </div>
  
<p><center><form action="" method="post"><input type="date" name="kayd"/><input type="submit" value="Search" /></form></center></p>
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>
<thead>
		 <tr bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'   width='200px'>ID</th>
					<th data-column-id='employee_name'   width='200px'>Client</th>
					<th data-column-id='employee_name'   width='200px'>Date</th>
					<th data-column-id='employee_name'   width='200px'>Status</th>
					<th data-column-id='employee_name'   width='200px'></th>
					<th data-column-id='employee_name'   width='200px'></th>
					<th data-column-id='employee_name'   width='200px'></th>
					<th data-column-id='employee_name'   width='200px'></th>
				</tr>
			    </thead>
		
			    
			        <?php 

$date=date('Y-m-d'); 

			 if (isset($_GET['submin']))
 {
		    $uses =  $_GET['ordid'];
			$del = mysqli_query($con,"update rental_center set rentalhistory='yes' where s='$uses'") or die ('Could not connect: ' .mysqli_error($con)); 
			echo"<script>alert('Rental History Closed!');</script>";
			
 }

		  
		  
		  
			 if (isset($_POST['repair']))
 {
		    $use =  $_POST['repair_id'];
			$approve= mysqli_query($con,"update rental_center set confirmation='Approved' where s='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					 
					
if ($approve){
                 
$sql3 = "SELECT * from rental_center where s='$use'  ORDER BY s DESC";
$sql4 = mysqli_query($con,$sql3);
$i=1;
while ($row = mysqli_fetch_array($sql4)) {
    $ids=$row['rental_id'];
    $email=$row['email'];
    $name=$row['name'];
}
    
    
    
	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
    $comments = $email_to = $email_subject = $email_from = $msg = "";
	$email_from="admin@chbluxuryempire.com";					 
	$email_to = $email;
    $email_subject = "Rental Request Approved!";
    $email_message ="
	
<html><body><div style='background-color:#000000; color:#fff; height:700px; width:500px; padding:50px; font-weight:600;'>
<p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' />
<span style='float:right; color:#fff; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$date</span></p><br>
<p style='color:#FFFFFF;'>Hello Dear $name,Thank you for choosing CHB luxury rental for beauty and skill training center.Your Request has been approved. 
<br>Tracking code <span style='color:#FFC700;'>($ids).</span>Check to confirm the status of your rental via the link below.</p>
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
         $header =  'From: "CHBLUXURYEMPIRE" <admin@chbluxuryempire.com>'. "\r\n"; 
         $header .= "Cc:admin@chbluxuryempire.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
  						 
   
$mailsent=mail($email_to, $email_subject,$email_message,$header);
if($mailsent ==true){
echo"<p style='color:green;'> Rent Approved!</staff>";	
 
}
}
}

			 if (isset($_GET['reject']))
 {
		    $used =  $_GET['ordids'];
			$reject= mysqli_query($con,"update rental_center set confirmation='Rejected' where s='$used'") or die ('Could not connect: ' .mysqli_error($con)); 
					 
				
if ($reject){
$sql5 = "SELECT * from rental_center where s='$used' ORDER BY s DESC";
$sql6 = mysqli_query($con,$sql5);
$i=1;
while ($row = mysqli_fetch_array($sql6)) {
    $ids=$row['s'];
    $email=$row['email'];
    $name=$row['name'];
}


	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
    $comments = $email_to = $email_subject = $email_from = $msg = "";		
	$email_from="admin@chbluxuryempire.com";					 
	$email_to = $email;
    $email_subject = "Rental Request Rejected!";
    $email_message ="
	
<html><body><div style='background-color:#000000; color:#fff; height:700px; width:500px; padding:50px; font-weight:600;'>
<p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='100px' />
<span style='float:right; color:#fff; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$date</span></p><br>
<p style='color:#fff;'>Hello Dear $name,Thank you for choosing CHB Luxury rental for beauty and skill training center.Your Request has sadly been disapproved. <br>
You are welcome to reapply again,thank you.</p>


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
if($mailsent){
echo"<p style='color:red;'> Rent Rejected!</staff>";	
}
}
}	

		   
$sql = "SELECT * from rental_center where rentalhistory='no' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
$i=1;
while ($row = mysqli_fetch_array($sql2)) {
$ids=$row['s'];

echo"

<tr  bgcolor='#fff'>
<td   width='200px'>" . $row['rental_id'] . "</td>
<td   width='200px'>" . $row['name'] . "</td>
<td   width='200px'>" . $row['datetouse'] . "</td>
<td   width='200px'>" . $row['confirmation'] . "</td>
<td  width='200px'><form action='rent_details.php' method='post'>
<input type='text' name='rent_id' value='" . $row['s'] . "' required hidden>  
<input type='submit' name='rent' value='View Request' class='submitn' ></form></td>	

<td  width='200px'><a href='#close" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>open</button></a>
			
		
	<div class='modal fade' id='close" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Close Rent Request?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to close this request?</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['s'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin' value='yeah'>Yes</button></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
     </div>
      </div>
    </div>
  </div>
 </div> 
</div>

             
</div></td>

<td  width='200px'>	<a href='#close" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>Close</button></a>
			
		
	<div class='modal fade' id='close" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>open Rent Request?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to close this request?</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['s'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin' value='yeah'>Yes</button></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
     </div>
      </div>
    </div>
  </div>
 </div> 
</div>

             
</div>
</td>
<td  width='200px'><a href='#close" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>Close</button></a>
			
		
	<div class='modal fade' id='close" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Close Rent Request?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to close this request?</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['s'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin' value='yeah'>Yes</button></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
     </div>
      </div>
    </div>
  </div>
 </div> 
</div>

             
</div>
</td>
</tr>
";  
				
}


?> 
				

	  </table></div> </p>
      

      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->
  <?php include "footer.php" ?>