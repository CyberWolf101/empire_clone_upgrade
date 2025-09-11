<?php  include "header.php"; include "skip.php"; ?>

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
      <form method="post" action="user_details.php">
		<p><b>PERSONAL DETAILS</b></p>
		<?php
		$re=$_POST['sub'];
		?>
<div class="col-lg-4">
	<p><input type="text" class="form-control" name="name" placeholder="Your Name.." required/></p>
	<p><input type="text" class="form-control" name="email" placeholder="Your Email.." required/></p>
	<p><input type="text" class="form-control" name="phone" placeholder="Your Phone Number.." required/></p>
	<input type="hidden" class="form-control" name="ran" value="<?php echo $re; ?>" required/>
	<input type="date" name="dear" min="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>"class="form-control" class="form-control" required hidden/>
	<p><input type="submit" value="SUBMIT" name="submit" class="form-control btn-buya"/></p>
	</div></form></div></div>
		  

 		  
		  
		
  
		  
		  
		  
		  
		  
		  </div>
		   
      <?php  include "footer.php"; ?>