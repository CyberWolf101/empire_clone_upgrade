<?php include"header.php" ?>
  <?php
  	include	 'connect_to_mysqli.php';
$rent=$_SESSION['rent_id'];

$sql = "SELECT * from rental_center where s='$rent' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
 $ids=$row['s'];   
$id=$row['rental_id'];
$name=$row['name'];
$email=$row['email'];
$dateregistered=$row['dateregistered'];
$datetouse=$row['datetouse'];
$firstreason=$row['firstreason'];
$secondreason=$row['secondreason'];
$duration=$row['duration'];
$people=$row['people'];
$confirmation=$row['confirmation'];


}
?>
<?php
if (isset($_POST['cancel']))
 {
     
      header("location: rents.php"); 
}
 if (isset($_POST['repair']))
 {
		    $use =  $_POST['repair_id'];
			$approve= mysqli_query($con,"update rental_center set confirmation='Approved' where rental_id='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					 
					
if ($approve){
                 
$sql3 = "SELECT * from rental_center where rental_id='$use'  ORDER BY s DESC";
$sql4 = mysqli_query($con,$sql3);
$i=1;
while ($row = mysqli_fetch_array($sql4)) {
   $id=$row['rental_id'];
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
<br>Tracking code <span style='color:#FFC700;'>($id).</span>Check to confirm the status of your rental via the link below.</p>
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
    
    
echo"<script>alert('Rental Approved!');
window.location.assign('rents.php')</script>";
 
}
}
}

?>
  
  <script type="text/javascript">
    window.onload = () => {
        $('#myModal').modal('show');
    }
</script>


    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
        <h5 class="modal-title w-100 text-center" id="exampleModalLabel"><i class="bx bx-wink-smile" style="font-size:20px;"></i></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:#FFFFFF;"></button>
      </div>
      <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
        <p style="font-weight:600;">Are you sure you want to approve this request?</p>
			<p style="color:black;" style="margin-bottom:30px;">
			    <form action="" method="post" >
			  <input type='text' name='repair_id' value='<?php echo $id  ?>' required hidden>  
<input type='submit' name='repair' value='yes' class='submitn' ></p>
               <p><button class="submitn" type="submit" name="cancel" data-bs-dismiss="modal">No</button></p></form>
      </div>
    </div>
  </div>
</div></div>


    <?php include "footer.php" ?>