<?php include"header.php";  include "repair_center.php"; ?>
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
  
}</style>
		
		  
		 <div style="margin-top:100px; color:#FFFFFF;">
		<div class="justify-content-center" align="center">
		<p><b>SUBMIT A REPAIR REQUEST</b></p>
		<p style="text-transform:capitalize;">Enter the details correctly and extensively</p>
        <div class="col-lg-6">
       <form  method="post" enctype="multipart/form-data" >
       <p><input type="text" class="form-control" placeholder="*Equipment Name" name="item" required></p>
       <p><input type="text" class="form-control" placeholder="*How long has it been used before it became faulty?" name="duration" required></p>
	   <p><textarea class="form-control" placeholder="*Full Description (Extent of damage)" name="describe" required></textarea></p>
	   <p>
	       
<p>
  <label>Attach Product Image</label>
  <input
    type="file"
    name="product"
    class="form-control"
    accept="image/*" required
  />
</p>


    <select class="form-control" name="purchase" id="purchase-select">
    <option value="" selected> - Was this item purchased from us? -</option>
    <option value="yes"> Yes </option>
    <option value="no"> No </option>
    </select>
    </p>
<p id="file-upload-section" style="display: none;">
  <label>Attach Receipt/Invoice Picture</label>
  <input
    type="file"
    name="invoice"
    class="form-control"
    accept="image/*" 
  />
</p>

<script>
  // Get the select element and file input element by their IDs
  const purchaseSelect = document.getElementById("purchase-select");
  const fileUploadSection = document.getElementById("file-upload-section");

  // Add an event listener to the select element to toggle the file input visibility
  purchaseSelect.addEventListener("change", function () {
    if (purchaseSelect.value === "yes") {
      // Show the file input and make it required
      fileUploadSection.style.display = "block";
      document.querySelector("input[name='product']").required = true;
    } else {
      // Hide the file input and remove the required attribute
      fileUploadSection.style.display = "none";
      document.querySelector("input[name='product']").required = false;
    }
  });
</script>

	   
	   <p><b>CUSTOMER DETAILS</b></p>
	   <p><input type="text" class="form-control" placeholder="*Customer Name" name="name" required></p>
	   <p><input type="text" class="form-control" placeholder="*Customer Email" name="email" required></p>
       <p><input type="tel" class="form-control" placeholder="*Customer Phone Number" name="phone" required></p>
	   <p><input type="submit" value="Submit" name="repair" class="submitn" style="text-align:center;"/></p></form>
	   
	
	   <span><b>To track your Repair Request.<a href="repairtrack.php" style="color:#FFC700; text-decoration:underline;"> Click here</a></b></span><br><br>
	   <p><a href="index.php" class="submitn w-100">BACK TO HOME</a></p><br><br>
	   
	   <!-----
	      <p><b>TRACK REPAIR REQUEST</b></p>
	      <form  method="get" action="repair_progress.php">
	     <p><input type="text" class="form-control" placeholder="*Enter your Tracking ID here" name="repairid" required></p>
	     <p><input type="submit" value="Track" name="submittrack" class="submitn" style="text-align:center;"/></p></form>
	   <p><a href="index.php"  class="form-control btn-buya">BACK TO HOME</a> </p>
	   
	   ------>
	   </div></div></div>
		  

 		  
		  
		
  
		  
		  
		  
		  
		  
		  </div>
		   
       <?php include "footer.php"; ?>