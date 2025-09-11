<html><head><title>Receipt</title></head>
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
</style>
<style>
@media print {
    .con{
      display: none;
    }
    @page { size: auto;  margin: 0mm; }
    </style>
<body>
     <a href="#" class="btn btn-primary con" style="background-color:#FF339A;  color:white; font-size:14px;  float:right; border:none;  outline:none;" onclick="window.print()" >
    <i class="icon-download"></i> Print 
    									</a>
 <?php
 	include "connect_to_mysqli.php";
	     $sa =  $_POST['ordid'];
    	 $sql = "SELECT * from foods where id ='$sa'";
		  $sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
    $name=$row['name'] ;
    $mob=$row['phone'] ;
    $em=$row['email'] ;
    $dem=$row['date'] ;
     $say=$row['s'] ;
    
}
    
    ?>
    
    

    <?php
    // For walk in customers only
    			 if (isset($_POST['met']))
 {
    $sa =  $_POST['ordid'];
    $sad =  $_POST['met'];
    
   $insert = mysqli_query($con,"UPDATE foods SET app= 'Confirmed' where id='$sa'") or die ('Could not connect: ' .mysqli_error($con)); 
   $insert = mysqli_query($con,"UPDATE foods SET status='Paid' where id='$sa'") or die ('Could not connect: ' .mysqli_error($con));  
   $insert = mysqli_query($con,"UPDATE foods SET meth= '$sad' where id='$sa'") or die ('Could not connect: ' .mysqli_error($con));  
   $insert = mysqli_query($con,"UPDATE enter SET status= 'Paid' where orderid='$sa'") or die ('Could not connect: ' .mysqli_error($con));  
    
}
    
    ?>
    
    
    
    
    
    <div style="width:50%; height:50vh; margin:auto;">
    <p style='text-align:left;'>
	<img src='https://chbluxuryempire.com/admin/kayd.png' width='80px' height='80px' style='margin-top:13px;' /><br>
	<span >ReceiptNo : <?php echo $say; ?>
	<br>Date:  <?php echo $dem; ?></span> </p>
	
	<p>Customer Details<br>
	Name: <?php echo $name; ?> <br>
	Phone: <?php echo $mob; ?> <br>
	Email: <?php echo $em; ?> </p>
     <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='2' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
	
					
					<th data-column-id='employee_name'  width='50px'>Item</th>
					<th data-column-id='employee_name'  width='50px'>Per Unit</th>
					<th data-column-id='employee_name'  width='50px'>Unit</th> 
					<th data-column-id='employee_name'  width='50px'>Total</th> 
 
	<br>
 <?php
 	include "connect_to_mysqli.php";
	  $sa =  $_POST['ordid'];
    	 $sql = "SELECT * from enter where orderid='$sa' ";
		  $sql2 = mysqli_query($con,$sql);
		  $i=1;
while ($row = mysqli_fetch_array($sql2)) {
  
 $re=$row['price'] ;
 $de=$row['no'] ;
 $per=$re*$de;
echo"

<tr bgcolor='#fff'><td width='100px'>" . $row['id'] . "</td>
<td width='50px' >" . $row['price'] . "</td><td width='50px'>" . $row['no'] . "</td>
<td width='50px'>" . $per. "</td></td><tr>";

               
}
    
    ?> 
     <?php
$sql = "SELECT sum(priced) from enter where orderid='$sa' ";
$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))

$k=$row[0];

$tot=$k;

  ?>				
  
  
     </table></div>
	  </p>
	  <p style="text-align:center;"><i><span style="text-align:center; font-size:13px"><?php echo $cont; ?></span></i></p>
    <p style="text-align:center; font-weight:900;">Grand Total :<?php 
echo $tot;
  ?>   naira</p>  
   <p style="text-align:center;"><i>Thank you for your patronage</i></p> <br><br>
      <a href="items.php" style="text-align:center;" class="con">Back</a>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
</body>






































</html>