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
  
}</style>
		
		  
		  <div style="margin-top:100px; color:#FFFFFF;">
		<div class="justify-content-center" align="center">
      <form method="post" enctype="multipart/form-data">
		<p><b>PERSONAL DETAILS</b></p>
		<p style="text-transform:capitalize;">Fill in the required information below,choose package and proceed</p>
        <div class="col-lg-4">
	<p><input type="text" class="form-control" name="name" placeholder="Your First Name.." required/></p>
    <p><input type="text" class="form-control" name="last" placeholder="Your Last  Name.." required/></p>
    <p><input type="email" class="form-control" name="email" placeholder="Your Email.." required/></p>
    <p><input type="text" class="form-control" name="mob" placeholder="Your Phone Number.." required/></p>
    <p><select class="form-control" name="cater"required>
	   <option selected="selected" value="">Choose Membership Package</option>
	     <option value="Monthly Membership">Monthly Membership</option>
	    <option value="Quarterly Membership">Quarterly Membership</option>
		 <option value="Yearly Membership">Yearly Membership</option>
	   </select>
	   </p>
	   <p> <select class="form-control" name="qua">
			  <option selected="selected" value="">-Select No of People-</option>
			  <?php
    for ($i=1; $i<=5; $i++)
    {
        ?>
            <option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php
    }
?></select></p>
<p><label>User 1(required)</label><input type="file" name="file" class="form-control" required /></p>
<p><label>User 2(optional)</label><input type="file" name="fila" class="form-control" /></p>
<p><label>User 3(optional)</label><input type="file" name="filo" class="form-control" /></p>
<p><label>User 4(optional)</label><input type="file" name="fili" class="form-control" /></p>
<p><label>User 5(optional)</label><input type="file" name="filu" class="form-control" /></p>
	     <input type="text" class="form-control" name="dat" id="currentDate"  readonly hidden  required />
	
	<script>
  var today = new Date();
  var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
  document.getElementById("currentDate").value = date;
  </script>
	  <p><input type="checkbox" /><a href="terms.php" style="color:#FFC700; font-size:12px;">Terms and Conditions</a></p>
	  <p><input type="submit" value="REGISTER" name="submit" class="form-control btn-buya" name="mob" /></p>
	</div></form></div></div>
		  

 		  
		  
		
  
		  
		  
		  
		  
		  
		  </div>
		   
     <?php include "footer.php"; ?>