<?php include "header.php"; ?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800">Submit Repair Request</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>
          
         
        
        <div style="color:red; text-align:center;" >
		<p><?php include "addrepair.php"; ?> </p></div>
		
		
       <form  method="post" enctype="multipart/form-data" >
       <p><input type="text" class="form-control" placeholder="*Equipment Name" name="item" required></p>
       <p><input type="text" class="form-control" placeholder="*How long has it been used before it became faulty?" name="duration" required></p>
	   <p><textarea class="form-control" placeholder="*Full Description (Extent of damage)" name="describe" required></textarea></p>
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
	   <p><input type="submit" value="Submit Request Details" name="repair" class="btn btn-warning" style="text-align:center;"/></p></form>


   
          
          
          
<?php include "footer.php"; ?>