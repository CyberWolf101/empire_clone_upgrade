<?php  include "header.php";  include "user_details.php"; ?>


 <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
          <div class="container" style="width:100%; margin:auto; ">
      <div style="margin-top:100px; color:#FFFFFF;">
	  <div class="justify-content-center" align="center">
      <form method="post" action="user_details.php">
	  <p><b>ENTER YOUR INFORMATION TO PROCEED</b></p>
	
    <div class="col-lg-4">
	<p><input type="text" class="form-control" name="name" placeholder="Your Name.." required/></p>
	<p><input type="text" class="form-control" name="email" placeholder="Your Email.." required/></p>
	<p><input type="text" class="form-control" name="phone" placeholder="Your Phone Number.." required/></p>
	<input type="hidden" class="form-control" name="ran" value="<?php echo $ran; ?>" required/>
	<p><input type="submit" value="SUBMIT" name="submit" class="form-control btn-buya"/></p>
	</div></form></div></div>
		  

 		  
		  
		
  
		  
		  
		  
		  
		  
	</div></section>
		   
      <?php  include "footer.php"; ?>