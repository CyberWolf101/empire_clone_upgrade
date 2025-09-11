<?php include"header.php";   include "repair_center.php";


$id=$_SESSION['repair_id']
?>
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
        <p><b>THANK YOU!</b></p>
		<p>Thank you for choose CHB luxury repair center. Here is your tracking code <span style='color:#FFC700;'><?php echo $id; ?></span><br>
		We will get back to you in the next 24-72hrs if your device can be fixed or not.
		However this is your repair tracking code: <?php echo $id; ?> <a style='color:#FFC700;' href="http://chbluxuryempire.com/repair_progress.php?repairid=<?php echo $id; ?>"><u>Click Here</u> </span>
		to track your repair</p>
	   <p><a href="index.php"  class="form-control btn-buya">BACK TO HOME</a> </p>
	   </div></div></div>
		  

 		  
		  
		
  t
		  
		  
		  
		  
		  
		  </div>
		   
       <?php include "footer.php"; ?>