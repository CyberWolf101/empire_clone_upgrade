<?php include"header.php";  include"reviews.php"; ?>
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
        <form method="post" enctype="multipart/form-data">
		<p><b>SUBMIT A REVIEW</b></p>
		<p style="text-transform:capitalize;"> DROP YOUR FIRST TIME REVIEW, GET FEATURED</p>
        <div class="col-lg-6">
	    <p><input type="text" class="form-control" name="name" placeholder="Your Name.." required/></p>
	    <p> <select id="cars" class="form-control"  name="state" required>
	   <option value="" selected="selected">- Your Location -</option>
              <option value="Abuja FCT">Abuja FCT</option>
              <option value="Abia">Abia</option>
              <option value="Adamawa">Adamawa</option>
              <option value="Akwa Ibom">Akwa Ibom</option>
              <option value="Anambra">Anambra</option>
              <option value="Bauchi">Bauchi</option>
              <option value="Bayelsa">Bayelsa</option>
              <option value="Benue">Benue</option>
              <option value="Borno">Borno</option>
              <option value="Cross River">Cross River</option>
              <option value="Delta">Delta</option>
              <option value="Ebonyi">Ebonyi</option>
              <option value="Edo">Edo</option>
              <option value="Ekiti">Ekiti</option>
              <option value="Enugu">Enugu</option>
              <option value="Gombe">Gombe</option>
              <option value="Imo">Imo</option>
              <option value="Jigawa">Jigawa</option>
              <option value="Kaduna">Kaduna</option>
              <option value="Kano">Kano</option>
              <option value="Katsina">Katsina</option>
              <option value="Kebbi">Kebbi</option>
              <option value="Kogi">Kogi</option>
              <option value="Kwara">Kwara</option>
              <option value="Lagos">Lagos</option>
              <option value="Nassarawa">Nassarawa</option>
              <option value="Niger">Niger</option>
              <option value="Ogun">Ogun</option>
              <option value="Ondo">Ondo</option>
              <option value="Osun">Osun</option>
              <option value="Oyo">Oyo</option>
              <option value="Plateau">Plateau</option>
              <option value="Rivers">Rivers</option>
              <option value="Sokoto">Sokoto</option>
              <option value="Taraba">Taraba</option>
              <option value="Yobe">Yobe</option>
              <option value="Zamfara">Zamfara</option>
            </select></p>
     <p><input type="file" name="file"  class="form-control" required/></p>	 
	<p><textarea class="form-control" name="mob" placeholder="Your Review.."  required ></textarea></p>
	<p><input type="submit" value="SUBMIT" name="submit" class="form-control btn-buya" /></p>
	<p><a href="index.php"  class="form-control btn-buya">BACK TO HOME</a> </p>
	</div></form></div></div>
		  

 		  
		  
		
  
		  
		  
		  
		  
		  
		  </div>
		   
       <?php include "footer.php"; ?>