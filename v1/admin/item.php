<?php include"header.php" ?>
 
<main id="main">

  <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>STORE ORDERS</h2>
          <p>Here are all the logs of every paid item. Print receipts here</p>
        </div>
  	<?php 
		     include "connect_to_mysqli.php";
		     if (isset($_POST['submin']))
{

$idee =  $_POST['idn']; 
$fa="Delivered";
$insert = mysqli_query($con,"UPDATE foods SET app= '$fa' where id='$idee' ") or die ('Could not connect: ' .mysqli_error($con)); 


					echo'<p style="color:#FFC700; text-align:center;">Delivered!</p>';
					}
					 
					 
					 ?>	
		 
<p><center><form action="" method="post"><input type="text" Placeholder="Booking ID" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p>

	   <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'  width='200px'>No</th>
					<th data-column-id='employee_name'  width='200px'>Receipt No</th>
					<th data-column-id='employee_name'  width='200px'>Client</th>
					<th data-column-id='employee_name'  width='200px'>MOP</th>
					<th data-column-id='employee_name'  width='200px'></th>
					
					
				
					
				</tr>
			</thead>
		  <?php
		  
	  
	include "connect_to_mysqli.php";
	  $sa =$_POST['kayd'];
	 
	 
	    if((!$sa))
	  {
		   
			 $sql = "SELECT DISTINCT id,name,app,meth from foods where status='Paid' && meth!='Card' ORDER BY date DESC";
		  $sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {

$ap=$row['app'];

echo"

<tr bgcolor='#fff'><td width='200px' >" . $i++ . "</td><td width='200px'>" . $row['id'] . "</td>
<td width='200px' >" . $row['name'] . "</td><td width='200px' >" . $row['meth'] . "</td>
<td><form action='receps.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='submit' name='submin' value='Print Receipt' class='submitn' ></form></td>	
</tr>
</tr>

	<div class='modal fade' id='delete". $row['id'] ."' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Ready to Deliver?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Ready to Deliver to (" . $row['name'] . ")?!</p>
	    <p><form action='' method='post' >
        <input type='text' name='idn' value='" . $row['id'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin'>Yes</button></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
          </div>
      </div>
    </div>
  </div>
 </div> 
</div>
               ";  
				
}}

else
{
	$sql = "SELECT DISTINCT id,name,app,meth from foods where id='$sa' && status ='Paid' && meth!='Card' ";
		  $sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo"

<tr bgcolor='#fff'><td width='200px' >" . $i++ . "</td><td width='200px'>" . $row['id'] . "</td>
<td width='200px' >" . $row['name'] . "</td><td width='200px' >" . $row['meth'] . "</td>
<td><form action='receps.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='submit' name='submin' value='Print Receipt' class='submitn' ></form></td>	
</tr>
</tr>
	<div class='modal fade' id='delete". $row['id'] ."' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Ready to Deliver?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Ready to Deliver to (" . $row['name'] . ")?!</p>
	    <p><form action='' method='post' >
        <input type='text' name='idn' value='" . $row['id'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin'>Yes</button></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
          </div>
      </div>
    </div>
  </div>
 </div> 
</div>
               ";  
				
}
}
				   
					
	
	  
	 
		 
	
	  ?> 
	  </table></div>
	  </p>
      

      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->
  <?php include"footer.php" ?>