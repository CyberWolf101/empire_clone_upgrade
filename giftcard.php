<?php include"header.php";  

     if(isset($_POST['submit']))
	 {
	 
	 $rname = $_POST['r_name']; 
	 $rmail =  $_POST['r_email'];
	 $sname = $_POST['s_name']; 
	 $semail = $_POST['s_email']; 
	 $sphone = $_POST['s_phone']; 
	 $amount = $_POST['amount'];
	 $owner = $_POST['owner']; 
	 
	 if($owner==1){
	 $notes = mysqli_real_escape_string($con, $_POST['notes']);
	  $sname = $_POST['s_name_others']; 
	 $semail = $_POST['s_email_others']; 
	 $sphone = $_POST['s_phone_others']; 
	 }
	 
	 $date=date("Y-m-d");
	 $gift = str_pad(mt_rand(1, 9999999999999999), 16, '0', STR_PAD_LEFT);

	 
	  
	  $submit = mysqli_query($con,"insert into giftcard(giftcardno,amount,amount_left,sendername,senderphone,senderemail,ownername,owneremail,notes,status,date,owner) 
	  values ('$gift','$amount','$amount','$sname','$sphone','$semail','$rname','$rmail','$notes','unpaid','$date','$owner')") or die ('Could not connect: ' .mysqli_error($con));
	



     echo'
        
        
  <div class="modal fade" id="PayModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="vertical-alignment-helper">
  <div class="modal-dialog vertical-align-center">
<div class="modal-content">
      <div class="modal-header" style="background-color:#000000; color:#fff;">
        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Payment Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:#FFFFFF;"></button>
      </div>
      <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
        <center><p style="text-align:center; font-size:16px;">Ready to checkout and pay?</p>
        <form  method="post" action="https://checkout.flutterwave.com/v3/hosted/pay">
	 <input type="hidden" name="public_key" value="'.$apikey.'" />
     <input type="email" name="customer[email]"  value="'.$semail.'" placeholder="Enter Email" hidden />
     <input type="hidden" name="customer[phone_number]" value="'.$sphone.'" placeholder="Enter Phone Number" />
     <input type="hidden" name="customer[name]" value="'.$sname.'" />
        <input type="hidden" name="tx_ref" value="'.$gift.'" />
        <input type="hidden" name="amount" value="'.$amount.'" />
        <input type="hidden" name="currency" value="NGN" />
        <input type="hidden" name="meta[token]" value="54" />
        <input type="hidden" name="redirect_url" value="https://chbluxuryempire.com/giftcard_success.php" />
		<button type="submit" class="submitn">Proceed To Checkout</button>
        </table></form>
        
       <p><button type="button" class="submitn" style="background-color:white; color:black; border:none;" data-bs-dismiss="modal">No,Decline</button><p></center>
      </div>
    </div>
  </div>
</div></div>';


echo"<script>
    $(document).ready(function () {
        $('#PayModal').modal('show');
    });
</script>";


    
}


?>
<main>
         
<style>
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


.giftcard{
width:600px;
background-color:white;
border-radius:6px;
color:black;
padding:20px;
}

.select-button {
    padding: 10px 20px;
    margin: 5px;
    border: none;
    background: #000;
    color: #fff;
    cursor: pointer;
    transition: background 0.3s, color 0.3s;
}

.select-button.selected {
    background: #FEBF01;
    color: #fff;
}


.select-buttons {
    padding: 10px 20px;
    margin: 5px;
    border: none;
    background: #000;
    color: #fff;
    cursor: pointer;
    transition: background 0.3s, color 0.3s;
}

.select-buttons.selected {
    background: #FEBF01;
    color: #fff;
}
</style>
		
		  
		 <div class="row justify-content-center" style="margin-top:100px; color:#FFFFFF;">
		
		 <div class="col-lg-6 giftcard">
		<p><img src="cards.png" style="width:50%; margin-top:20px;" /></p>
		<p style="color:#FFC700;"><b>GET A E-GIFTCARD TODAY!</b></p>
		<p>Select giftcard amount  to buy for your friends, loved ones and family.</p>
		
		
	<p><div class="select-options">
    <button class="select-button" data-value="10000">&#8358;10,000</button>
    <button class="select-button" data-value="20000">&#8358;20,000</button>
    <button class="select-button" data-value="30000">&#8358;30,000</button>
     <button class="select-button" data-value="50000">&#8358;50,000</button>
    <button class="select-button selected" data-value="100000">&#8358;100,000</button>
    <button class="select-button" data-value="20000">&#8358;200,000</button>
    <button class="select-button" data-value="500000">&#8358;500,000</button>
    <button class="select-button" data-value="" onclick="convertToNumber()">Enter Custom Amount</button>
    <!-- Add more buttons as needed -->
</div><form method="post">

		
		<p style="color:#FFC700;"><b>Send unlimited access and infinite joy</b></p>
		<p  id="selectedValues" style="display:none;"><label>Enter Custom Amount</label><input type="number" id="selectedValue" class="form-control" name="amount"  value="" required/></p>
		
		  <p>
            <select name="owner" class="form-control" id="owner" onchange="toggleFields()" required>
                <option value="" selected>- Buying for -</option>
                <option value="0">Myself</option>
                <option value="1">Others</option>
            </select>
        </p>
        
        <div id="myself" style="display:none;">
            <p>
                <label><b>YOUR DETAILS</b></label>
                <input type="text" class="form-control" name="s_name" id="s_name" placeholder="Your Name" /><br>
                <input type="email" class="form-control" name="s_email" id="s_email" placeholder="Your Email Address" /><br>
                <input type="tel" class="form-control" name="s_phone" id="s_phone" placeholder="Your Mobile Number" /><br>
            </p>
        </div>
        
        <div id="others"  style="display:none;">
            <p>
                <label><b>TO</b></label>
                <input type="text" class="form-control" name="r_name" id="r_name" placeholder="Recipient Name" /><br>
                <input type="email" class="form-control" name="r_email" id="r_email" placeholder="Recipient Email Address" />
            </p>
            <p>
                <label><b>FROM</b></label>
                <input type="text" class="form-control" name="s_name_others" id="s_name_others" placeholder="Your Name" /><br>
                <input type="email" class="form-control" name="s_email_others" id="s_email_others" placeholder="Your Email Address" /><br>
                <input type="tel" class="form-control" name="s_phone_others" id="s_phone_others" placeholder="Your Mobile Number" /><br>
                <textarea class="form-control" name="notes" id="notes" placeholder="Add a note or message (Optional)"></textarea>
            </p>
        </div>
		
		 <center>
		<p><b>Total: &#8358;<span id="here"></span> + VAT</b><br>
		<input type="submit" value="PURCHASE GIFTCARD" name="submit" class="form-control btn-buya"/></p>  
		    
		</form>
<script>
        // Function to toggle the visibility of sections and set required attribute
        function toggleFields() {
            var owner = document.getElementById('owner').value;
            var myselfDiv = document.getElementById('myself');
            var othersDiv = document.getElementById('others');
            
            if (owner === '0') {
                myselfDiv.style.display = 'block';
                othersDiv.style.display = 'none';
                
                // Set required attribute for Myself section
                document.getElementById('s_name').required = true;
                document.getElementById('s_email').required = true;
                document.getElementById('s_phone').required = true;
                
                // Remove required attribute for Others section
                document.getElementById('r_name').required = false;
                document.getElementById('r_email').required = false;
                document.getElementById('notes').required = false;
            } else if (owner === '1') {
                myselfDiv.style.display = 'none';
                othersDiv.style.display = 'block';
                
                // Set required attribute for Others section
                document.getElementById('r_name').required = true;
                document.getElementById('r_email').required = true;
                document.getElementById('s_name_others').required = true;
                document.getElementById('s_email_others').required = true;
                document.getElementById('s_phone_others').required = true;
                document.getElementById('notes').required = true;
                
                // Remove required attribute for Myself section
                document.getElementById('s_name').required = false;
                document.getElementById('s_email').required = false;
                document.getElementById('s_phone').required = false;
            } else {
                myselfDiv.style.display = 'none';
                othersDiv.style.display = 'none';
                
                // Remove required attribute for both sections
                document.getElementById('s_name').required = false;
                document.getElementById('s_email').required = false;
                document.getElementById('s_phone').required = false;
                document.getElementById('r_name').required = false;
                document.getElementById('r_email').required = false;
                document.getElementById('s_name_others').required = false;
                document.getElementById('s_email_others').required = false;
                document.getElementById('s_phone_others').required = false;
                document.getElementById('notes').required = false;
            }
        }
  
function convertToNumber() {
    // Get the input element by its ID
    var numberInput  = document.getElementById('selectedValues');

    // Hide the hidden input
    numberInput.style.display = 'block';
}

// Function to update the span with the value of the hidden input
function updateSpanWithValue() {
    // Get the hidden input element by its ID
    var inputElement = document.getElementById('selectedValue');
    
    // Get the span element by its ID
    var spanElement = document.getElementById('here');
    
    // Update the span's text content with the input's value
    spanElement.textContent = inputElement.value;
}

// Call the update function immediately
updateSpanWithValue();

// Set up an interval to update the span every second
setInterval(updateSpanWithValue, 1000); // 1000 milliseconds = 1 second


document.addEventListener("DOMContentLoaded", function () {
    const selectButtons = document.querySelectorAll(".select-button");
    const hiddenInput = document.getElementById("selectedValue");
    const totalValue = document.getElementById("here");

    // Function to set selected class and input value
    function setSelected(button) {
        selectButtons.forEach(btn => btn.classList.remove("selected"));
        button.classList.add("selected");
        hiddenInput.value = button.getAttribute("data-value");
         totalValue.textContent = button.getAttribute("data-value");
 
    }

    selectButtons.forEach(button => {
        button.addEventListener("click", function () {
            setSelected(this);
        });
    });

    // Check for selected option on page load
    const selectedButton = document.querySelector(".select-button.selected");
    if (selectedButton) {
        setSelected(selectedButton);
    }
});
</script>
		    
		    
		    
		    
		    
		    
		    
		    
		    
		    
		    
		    
	<p><a href="index.php"  style="background-color:black;" class="form-control btn-buya">BACK TO HOME</a> </p></center>
	 </div>
	 </div>
	 </div> 
		   
       <?php include "footer.php"; ?>