<?php include "header.php";
if($username!=""){echo header("location:cart.php");} 
?>











<div style="margin-top:100px; color:#FFFFFF;">
<div class="justify-content-center" align="center">
<form action="" method="post">
<p><b>PERSONAL DETAILS</b></p>
<p>To procced to your cart,enter your details below</p>
<div class="col-lg-5">
	<p><input type="text" class="form-control" name="name" placeholder="Your Name.." required/></p>
	<p><input type="email" class="form-control" name="email" placeholder="Your Email.."  required/></p>
	<p><input type="number" class="form-control" name="mobile" placeholder="Your Mobile Number.."  required /></p>


<p>
        <select name="owner" class="form-control" required>
        <option value="" selected>- Buying for -</option>
        <option value="0">Myself</option>
        <option value="1">Others</option>
        </select>
</p>
<div id="recipient-details" style="display:none;">
    <p><b>RECIPIENT DETAILS</b></p>
    <p><input type="text" class="form-control" name="rname" placeholder="Recipient Name.."/></p>
    <p><input type="email" class="form-control" name="remail" placeholder="Recipient Email.." /></p>
    <p><input type="number" class="form-control" name="rmobile" placeholder="Recipient Number (optional).." /></p>
</div>

	</div>
<div class="col-lg-12"> <p> <button type="submit" name="submitdetails" value="1" class="btn-buya">SUBMIT</button>  </p> </div>
</form></div>

<script>
    // Function to toggle recipient details section
    function toggleRecipientDetails() {
        var ownerSelect = document.querySelector('select[name="owner"]');
        var recipientDetails = document.getElementById('recipient-details');
        var rnameInput = document.querySelector('input[name="rname"]');
        var remailInput = document.querySelector('input[name="remail"]');

        if (ownerSelect.value === "1") {
            recipientDetails.style.display = 'block'; // Show recipient details
            rnameInput.required = true; // Make "Recipient Name" required
            remailInput.required = true; // Make "Recipient Email" required
        } else {
            recipientDetails.style.display = 'none'; // Hide recipient details
            rnameInput.required = false; // Remove "required" attribute
            remailInput.required = false; // Remove "required" attribute
        }
    }

    // Add an event listener to the owner select
    document.querySelector('select[name="owner"]').addEventListener('change', toggleRecipientDetails);

    // Initial check when the page loads
    toggleRecipientDetails();
</script>
















<?php


if (isset($_POST['submitdetails'])){
		 $name=$_POST['name'];
		 $mail=$_POST['email'];
		 $mob=$_POST['mobile'];  
		 $owner=$_POST['owner']; 
		 
if($owner=="1"){
$rname=$_POST['rname'];
$rmail=$_POST['remail'];
$rmob=$_POST['rmobile'];    

$insert = mysqli_query($con,"UPDATE voucher_orders SET ownername='$rname' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE voucher_orders SET owneremail='$rmail' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE voucher_orders SET ownerphone='$rmob' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
    
}else{
$insert = mysqli_query($con,"UPDATE voucher_orders SET ownername='$name' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE voucher_orders SET owneremail='$mail' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE voucher_orders SET ownerphone='$mob' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));    
}



$insert = mysqli_query($con,"UPDATE voucher_orders SET name='$name' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE voucher_orders SET email='$mail' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE voucher_orders SET phone='$mob' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE voucher_orders SET owner='$owner' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
echo"<script>alert('Details uploaded successfully!');</script>";
header("Refresh: 0; url=cart.php");
}
include "footer.php"; ?>