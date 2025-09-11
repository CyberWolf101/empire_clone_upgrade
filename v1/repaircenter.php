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
       <p><select class="form-control" name="purchase">
       <option value="" selected> - Was this item purchased from us? -</option>    
       <option value="yes" > Yes </option> 
       <option value="no"> No </option> 
        </select></p>
       <p><input type="text" class="form-control" placeholder="*How long has it been used before it became faulty?" name="duration" required></p>
	   <p><textarea class="form-control" placeholder="*Full Description (Extent of damage)" name="describe" required></textarea></p>
	   <p><input type="file"  name="product"  class="form-control" required /></p>
	   
	   <p><b>CUSTOMER DETAILS</b></p>
	   <p><input type="text" class="form-control" placeholder="*Customer Name" name="name" required></p>
	   <p><input type="text" class="form-control" placeholder="*Customer Email" name="email" required></p>
       <p><input type="tel" class="form-control" placeholder="*Customer Phone Number" name="phone" required></p>
	   <p><input type="submit" value="Submit" name="repair" class="submitn" style="text-align:center;"/></p></form>
	   
	   <br>
	   <span>To track your Repair Request.<a href="repairtrack.php" style="color:#FFC700;">click here</a></span>
	   
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