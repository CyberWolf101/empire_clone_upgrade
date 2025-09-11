<?php include"header.php";  ?>
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
		     <div class="col-lg-6">
		       <p><b>TRACK REPAIR REQUEST</b></p>
	      <form  method="get" action="repair_progress.php">
	     <p><input type="text" class="form-control" placeholder="*Enter your Tracking ID here" name="repairid" required></p>
	     <p><input type="submit" value="Track" name="submittrack" class="submitn" style="text-align:center;"/></p></form>
	   <p><a href="index.php"  class="form-control btn-buya">BACK TO HOME</a> </p>
	   </div></div></div>
	   	   
       <?php include "footer.php"; ?>