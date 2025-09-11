<?php include"header.php";  include "rental_center.php"; ?>
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

</style>
		
		  
		 <div style="margin-top:100px; color:#FFFFFF;">
		<div class="justify-content-center" align="center">
		<p><b>SUBMIT A RENTAL REQUEST</b></p>
		<p style="text-transform:capitalize;">Enter the details correctly and extensively</p>
		<p><?php echo $fail ?></p>
        <div class="col-lg-6">
       <form  method="post" enctype="multipart/form-data" >
       <p><input type="text" class="form-control" placeholder="Your Fullname*" name="name" required></p>
       <p><input type="email" class="form-control" placeholder="Your email* <?php echo  $err1; ?>" name="email" required></p>
     
        <p><input placeholder="Type Date" type="text" onfocus="(this.type = 'date')"  id="date" name="date" style="width:100%;"  required ></p>
        <?php echo $er; ?>
	   <p><select  class="form-control" name="reason" >
	   <option value="" selected>- Select Reason -</option>    
	   <option>For beauty rental</option>   
	   <option>Skills Training rental</option> 
	   </select></p>
	    <p><input type="tel" class="form-control" placeholder="Phone number*" name="phone" required></p>
	     <p><textarea  class="form-control" placeholder="Give Reason(if your reason is not stated above)" name="reasons" ></textarea></p>
	    <p><input type="number" class="form-control" placeholder="How many hours*" name="hour" required></p>
	    <p><input type="number" class="form-control" placeholder="How many people*" name="people" required></p>
	    <p><input type="submit" value="Submit" name="submit" class="submitn" style="text-align:center;"/></p></form>
       </form>
	   <p><a href="index.php"  class="form-control btn-buya">BACK TO HOME</a> </p>
	   </div></div></div>
		  

 		  
		  
		
  
		  
		  
		  
		  
		  
		  </div>
		   
       <?php include "footer.php"; ?>