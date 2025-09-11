<?php include "header.php"; include"members_submit.php"; ?>
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

h5{
text-align:left;
color:#FFC700;
font-size:15px;
    
}
</style>
		
		  
		  <div style="margin-top:100px; ; color:#FFFFFF; font-size:16px;">
		<div class="row justify-content-center" align="center">
        <form method="post" action="" enctype="multipart/form-data">
		<p><b>MEMBERSHIP DETAILS</b></p>
		<p style="text-transform:capitalize;">Fill in the required information below,choose package and proceed</p>
        <div class="col-lg-6 col-md-12">
    
    <p><select class="form-control" name="type"required>
	<option selected="selected" value="">Choose Membership Package</option>
	<option value="Monthly Membership">Monthly Membership</option>
	<option value="Quarterly Membership">Quarterly Membership</option>
	<option value="Yearly Membership">Yearly Membership</option>
	</select>
	</p>
	
	<p><select class="form-control" name="quantity" id="quantitySelect">
	<option selected="selected" value="">-Select No of People-</option>
	<?php for ($i=1; $i<=5; $i++) {?>
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
        memberDiv.innerHTML = `<h5>MEMBER ${i}</h5>
            <p><input type="text" class="form-control" name="name[]" placeholder="Name.." required/></p>
            <p><input type="email" class="form-control" name="email[]" placeholder="Email Address.." required/></p>
            <p><input type="text" class="form-control" name="mobile[]" placeholder="Phone Number.." required/></p>
            <p><input type="file" name="file[]" class="form-control" required /></p>`;

        container.appendChild(memberDiv);
    }
}

// Add an event listener to the quantity dropdown
document.getElementById('quantitySelect').addEventListener('change', function () {
    const selectedQuantity = parseInt(this.value);
    createMemberDivs(selectedQuantity);
});

   </script>

	

	  <p><input type="checkbox"  required/> <label> <a href="terms.php" style="color:#FFC700; font-size:14px;">Terms and Conditions</a></label></p>
	  <p><input type="submit" value="PROCEED" class="form-control btn-buya" name="submitdetails" /></p>
	</div>
	</form>
	</div></div>
		  

 		  
		  
		
  
		  
		  
		  
		  
		  
		  </div>
		   
     <?php include "footer.php"; ?>