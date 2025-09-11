<html>
<head><title>Delta Kitchen Receipt</title></head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap');

body{
font-family: 'Poppins';  
font-weight:100;
width:80mm;
font-size:11px;
margin:0;
}

th
{
    font-size:10px;
}

td{
    font-size:10px;
}

@media print {
.con{
display: none;
    }
@page { size: auto;  margin: 0mm; }
</style>
<body>
  
  
   
  
   
   
   
   <a href="#" class="btn btn-primary con" style="background-color:#173b6c;  color:white; font-size:14px;   float:right; border:none;  outline:none;" onclick="window.print()" >
   <i class="icon-download"></i> Print </a>
 <?php
 	include "connect_to_mysqli.php";
 	
 	
	$sa =  $_POST['ordid'];
    $sql = "SELECT sum(price*quantity) as total,name,email,phone,date FROM delta_cart where order_id='$sa' ORDER BY meal_id ASC";
	$sql2 = mysqli_query($con,$sql);
    while ($row = mysqli_fetch_array($sql2)) {
    $name=$row['name'] ;
    $mob=$row['phone'] ;
    $em=$row['email'] ;
    $dem=$row['date'] ;
    $tot=$row['total'] ;
}
    
    ?>
    
    <div style="width:50%; height:50vh; margin:auto;">
    <p style='text-align:left;'>
	<img src='https://chbluxuryempire.com/admin/kayd.png' width='80px' height='80px' style='margin-top:13px;'/><br>
	<span >ReceiptID : <?php echo $sa; ?>
	<br>Date:  <?php echo $dem; ?></span> </p>
	
	<p>Customer Details <br>
	Name: <?php echo $name; ?> <br>
	Phone: <?php echo $mob; ?> <br>
	Email: <?php echo $em; ?> </p>
	
     <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='2' data-toggle='bootgrid'>
     <thead>
				<tr  bgcolor="#CCCCCC">
	
					
					<th data-column-id='employee_name'  width='50px'>Item</th>
					<th data-column-id='employee_name'  width='50px'>Price</th>
					<th data-column-id='employee_name'  width='50px'>Quantity</th> 
					<th data-column-id='employee_name'  width='50px'>Total</th> 
 <?php
 
	      $sa =  $_POST['ordid'];
    	  $sql = "SELECT * from delta_cart where order_id ='$sa' && status='Paid' ORDER BY meal_id ASC";
		  $sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
    $name=$row['name'] ;
    $mob=$row['phone'] ;
    $nom=$row['quantity'] ;
    $pa=$row['price'] ;

    $ptotal=$nom*$pa;
 
echo"

<tr bgcolor='#fff'><td width='50px'>" . $row['meal_id'] . "</td>
<td width='50px' >" . $row['price'] . "</td><td width='100px'>" . $row['quantity'] . "</td>
<td width='50px'>" . $ptotal . "</td></td><tr>";

               
}
    
    ?>
	<br>
       </table></div>
	  </p>
	  <p style="text-align:center;"><i><span style="text-align:center; font-size:10px"><?php echo $cont; ?></span></i></p>
       <p style="text-align:center; font-weight:900;">Grand Total :<?php 
      echo $tot;
      ?> naira</p>  
   <p style="text-align:center;"><i>Thank you for your patronage</i></p>
      <a href="deltaorders.php" style="text-align:center;" class="con">Done</a>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
</body>






































</html>