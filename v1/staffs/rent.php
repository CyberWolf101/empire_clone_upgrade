<?php include "header2.php"; ?>
<body >

 

  <style>
  .guy{
  margin:0px;
  padding:0px;
  background-color:#FFFFFF;
  color:#000000;
  font-weight:600;
  }
  
  </style>

  <main id="main">
 
   <section id="pricing" class="pricing" style="margin-botttom:100px;">
      <div class="container" data-aos="fade-up">

        <div class="row content" align="center">
          <div class="col-lg-12" style="padding:0px; margin:auto; width:100%; ">
	
		   <form method="post" action=""> 
		 <p> <label>VIEW DAY</label>
			 <input type="date" class="form-control" name="dar" required /></p>
		 <p> <input type="submit"  name="submit" value="Search" class="btn-buy"  style="background-color:#FFC700; border-radius:5px; color:#FFFFFF; border:none;" /></p>
		  </form>
	<?php 
		     include "connect_to_mysqli.php";
		     if (isset($_POST['submitt']))
{

$idee =  $_POST['idn']; $teed =  $_POST['tdn'];  $teet =  $_POST['ddn'];
$fa="Ongoing";
$insert = mysqli_query($con,"UPDATE cart SET app= '$fa' where id='$idee' && timef='$teed' && date='$teet' ") or die ('Could not connect: ' .mysqli_error($con)); 


					echo'<p style="color:#FFC700; text-align:center;">Appointmnet Underway!</p>';
					}
					 
					 
					 ?>	
		 
		  <p style="overflow:auto; margin-bottom:50%; "><table class='table table-condensed '  width='100%' border="0" cellspacing='0' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					<th data-column-id='employee_name'  width='100px'>SN</th>
					<th data-column-id='employee_name'  width='100px'>Booking No</th>
					<th data-column-id='employee_name'  width='100px'>Client</th>
					<th data-column-id='employee_name'  width='100px'>Service</th>
					<th data-column-id='employee_name'  width='100px'>From</th>
					<th data-column-id='employee_name'  width='100px'>To</th>
					<th data-column-id='employee_salary'  width='100px'>Status</th>
					<th data-column-id='employee_salary'  width='100px'>Date</th>
                   <th data-column-id='employee_salary'  width='100px'></th>
					
					
				</tr>
			</thead>
		  <?php
		  
	  include "connect_to_mysqli.php";
	if(isset($_POST['submit']))
{
	
	$from = $_POST['dar'];

}
	if ($from=="")
	{	 $sql = "SELECT * from cart where staff ='".$name."' && status='Paid' ORDER BY date DESC";
		  $sql2 = mysqli_query($con,$sql);
		    $i=1;
		  while ($row = mysqli_fetch_array($sql2) ) {
   
    $ap=$row['app'];
   if($ap=='Confirmed')
   {
   $coke='<form method="post" action=""><input type="text" class="form-control"  value="'.$row["id"].'" name="idn" required hidden ><input type="text" class="form-control"  value="'.$row["timef"].'" name="tdn" required hidden >
<input type="text" class="form-control"  value="'.$row["date"].'" name="ddn" required hidden ><button type="submit" class="submitn" name="submitt">
Start</button>';
}
else
{
$coke='';

}


echo"

<tr bgcolor='#fff'><td width='100px' >" . $i++ . "</td><td width='100px'>" . $row['id'] . "</td>
<td width='100px' >" . $row['name'] . "</td><td width='100px' >" . $row['service'] . "</td><td width='100px'>" . $row['timef'] . "</td>
<td width='100px'>" . $row['timet'] . "</td>
<td width='100px'><span>" . $row['app'] . "</span></td>
<td width='100px'>" . $row['date'] . "</td>
               ";
			   echo '<td width="100px" >' . $coke . '</td></tr>';  
}
}

else{
			 $sql = "SELECT * from cart where date='$from' && staff='".$name."' && status='Paid' ORDER BY date DESC";
		  $sql2 = mysqli_query($con,$sql);
			
			 if (mysqli_affected_rows($con) == 0)
			  {
			  
				echo "<center>You don't have any appointments within that period</center>";
				 
			  } 
			   else 
			   {
		

while ($row = mysqli_fetch_array($sql2)) {
    $sta = $row['status'];
    
     $ap=$row['app'];
   if($ap=='Confirmed')
   {
   $coke='<td width="100px" ><form method="post" action=""><input type="text" class="form-control"  value="'.$row["id"].'" name="idn" required hidden ><input type="text" class="form-control"  value="'.$row["timef"].'" name="tdn" required hidden >
<input type="text" class="form-control"  value="'.$row["date"].'" name="ddn" required hidden ><button type="submit" class="submitn" name="submitt">
Start</button></td>';
}
else
{
$coke='';

}

     
   
echo"

<tr bgcolor='#fff'><td width='100px' >" . $i++ . "</td><td width='100px'>" . $row['id'] . "</td>
<td width='100px' >" . $row['name'] . "</td><td width='100px' >" . $row['service'] . "</td><td width='100px'>" . $row['timef'] . "</td>
<td width='100px'>" . $row['timet'] . "</td>
<td width='100px'><span>" . $row['app'] . "</span></td>
<td width='100px'>" . $row['date'] . "</td></tr>
               ";
			      echo '<td width="100px" >' . $coke . '</td></tr>';     
}



				   
					 }
		 
	
	  }
	 
		 
	
	  ?> </table></p>
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
         
      </div></div></div>
    </section>
  
  
  


  </main><!-- End #main -->
  <?php include "footer2.php"; ?>
 