<?php include "header.php"; 





?>
<style>
h5{
font-size:15px;
text-align:left;
}
</style>





<div style="margin-top:100px; color:#FFFFFF;">
<div class="row justify-content-center" align="center">
<form action="" method="post">
<p><b>RENTAL DETAILS</b></p>
<p> Submit your details to proceed</p>
<div class="col-lg-6 col-12" style="text-align:center; font-size:15px;">
    <p><input type="text" class="form-control" name="name" placeholder="Your Name.." required/></p>
	<p><input type="email" class="form-control" name="email" placeholder="Your Email.."  required/></p>
	<p><input type="number" class="form-control" name="mobile" placeholder="Your Mobile Number.."  required /></p>
		
	<p><select class="form-control" name="quantity" id="quantitySelect">
	<option selected="selected" value="">-Select No of Days-</option>
	<?php for ($i=1; $i<=10; $i++) {?>
    <option value="<?php echo $i;?>"><?php echo $i;?></option>
   <?php } ?></select></p>
	
	
   <div id="memberContainer"></div>	
	
	
   <script>
    // Function to create member divs
function createMemberDivs(quantity) {
    const container = document.getElementById('memberContainer');
    container.innerHTML = ''; // Clear any previous content

    for (let i = 1; i <= quantity; i++) {
        const memberDiv = document.createElement('div');
        memberDiv.innerHTML = `<h5>DAY ${i}(Select Date)</h5>
            <p><input type="date" class="form-control date-input" name="dates[]" min="${getToday()}" required/></p>`;
        
        container.appendChild(memberDiv);

        // Add an event listener to the date input field for AJAX check
        const dateInput = memberDiv.querySelector('.date-input');
        dateInput.addEventListener('change', function () {
            checkBooking(this);
        });
    }
}


// Function to get today's date in the format yyyy-mm-dd
function getToday() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Add an event listener to the quantity dropdown
document.getElementById('quantitySelect').addEventListener('change', function () {
    const selectedQuantity = parseInt(this.value);
    createMemberDivs(selectedQuantity);
});


// Function to check if a date has been booked
function checkBooking(dateInput) {
    const selectedDate = dateInput.value;
    
    // Check if the selected date has already been booked
    $.ajax({
        type: 'POST',
        url: 'check_booking.php', // Create a PHP file to handle the database query
        data: { selectedDate: selectedDate },
        success: function (response) {
            if (response === 'booked') {
                alert('Date has already been booked. Please select another date.');
                dateInput.value = ''; // Reset the date input field
            } else {
                // Check if the selected date has been selected in other date inputs
                const allDateInputs = document.querySelectorAll('.date-input');
                for (const input of allDateInputs) {
                    if (input !== dateInput && input.value === selectedDate) {
                        alert('Date has already been selected in another field. Please select another date.');
                        dateInput.value = ''; // Reset the date input field
                        break; // Stop checking once a duplicate is found
                    }
                }
            }
        }
    });
}


   </script>
	</div>
<div class="col-lg-12"> <p> <button type="submit" name="submitdetails" value="1" class="btn-buya">SUBMIT</button>  </p> </div>
</form></div>




















<?php


if (isset($_POST['submitdetails'])){
		 $name=$_POST['name'];
		 $mail=$_POST['email'];
		 $mob=$_POST['mobile']; 
		 $quantity=$_POST['quantity'];
		 
// Get the array of dates from the form
$datesArray = $_POST['dates'];
$datesString = implode(',', $datesArray);



$insert = mysqli_query($con,"UPDATE rentals SET name='$name' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE rentals SET email='$mail' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE rentals SET phone='$mob' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE rentals SET days='$quantity' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE rentals SET dates='$datesString' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con));

echo"<script>alert('Rental details uploaded successfully!');</script>";
header("Refresh: 0; url=category.php");
}
include "footer.php"; ?>