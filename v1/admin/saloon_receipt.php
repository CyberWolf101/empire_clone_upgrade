<?php  
ob_start(); 
session_start(); 
$sa=$_SESSION['id'];
?><html><head>
<title>Receipt</title>
</head>

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
  

   
   
   <a href="#" class="btn btn-primary con" style="background-color:#FF339A;  color:white; font-size:14px;   float:right; border:none;  outline:none;" onclick="window.print()" >
   <i class="icon-download"></i> Print </a>
 <?php
    include "connect_to_mysqli.php";
	
    $sql = "SELECT * from cart where id ='$sa' ORDER BY service ASC";
	$sql2 = mysqli_query($con,$sql);
    while ($row = mysqli_fetch_array($sql2)) {
    $name=$row['name'] ;
    $mob=$row['phone'] ;
    $em=$row['email'] ;
    $dem=$row['date'] ;
    $no=$row['nom'] ;
}
    
    ?>
    
    <div style="width:50%; height:50vh; margin:auto;">
    <p style='text-align:left;'>
	<img src='http://chbluxuryempire.com/admin/kayd.png' width='80px' height='80px' style='margin-top:13px;'/><br>
	<span >BookingID : <?php echo $sa; ?>
	<br>Date:  <?php echo $dem; ?></span> </p>
	
	<p>Customer Details <br>
	Name: <?php echo $name; ?> <br>
	Phone: <?php echo $mob; ?> <br>
	Email: <?php echo $em; ?> </p>
	
	<p>No of People : <?php echo $no; ?></p>
     <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='2' data-toggle='bootgrid'>

<thead><tr bgcolor="#CCCCCC">
	                <th data-column-id='employee_name'  width='50px'>Service</th>
					<th data-column-id='employee_name'  width='50px'>Per Unit</th>
					<th data-column-id='employee_name'  width='50px'>Units</th> 
					<th data-column-id='employee_name'  width='50px'>Total</th> 
 <?php

	      
    	  $sql = "SELECT * from cart where id='$sa' && status='Paid' ORDER BY service ASC";
		  $sql2 = mysqli_query($con,$sql);
		  $i=1;
while ($row = mysqli_fetch_array($sql2)) {
    $name=$row['name'] ;
    $mob=$row['phone'] ;
    $nom=$row['nom'] ;
    $pa=$row['price'] ;

 
 
echo"

<tr bgcolor='#fff'><td width='50px'>" . $row['service'] . "</td>
<td width='50px' >" . $row['price'] . "</td><td width='100px'>1</td>
<td width='50px'>" . $row['price'] . "</td></td><tr>";

               
}
    
    ?>
	<br>
    <th></th><th>FOOD</th><th></th>
 <?php
 
	       
    	   $sql = "SELECT * from enter where orderid='$sa' && price !='0' ";
		   $sql2 = mysqli_query($con,$sql);
		   $i=1;
while ($row = mysqli_fetch_array($sql2)) {
echo"

<tr bgcolor='#fff'><td width='100px'>" . $row['id'] . "</td>
<td width='50px' >" . $row['price'] . "</td><td width='50px'>" . $row['no'] . "</td>
<td width='50px'>" . $row['priced'] . "</td></td><tr>";

               
}

  
        $sq = "SELECT all* from food where name='Spa' ";
		$sq2 = mysqli_query($con,$sq);
	   while($ro = mysqli_fetch_array($sq2))
				  {
				  $kit= $ro['price'];
				  }	
				  $extract_user = mysqli_query($con,"SELECT all* from kit where id='$sa'");
		$coun = mysqli_num_rows($extract_user); 
		
		 if ($coun >= 1) { 
		 $cont='<br>With '.$coun.' Pedicure Spa Kit ('.$kit.'naira each) ';
		 }
		 else
		 {
		 $cont='';
		 }
			 
		 $kite=$kit*$coun;
			
$sql = "SELECT sum(price) from cart where id='$sa' && timef !='' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))

$pa=$row[0];
$p=$pa+ $kite;
$sql = "SELECT sum(priced) from enter where orderid='$sa' ";
$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))

$k=$row[0];

$tot=$k+$p;

  ?>				
  
  
     </table></div>
	  </p>
	 <p style="text-align:center;"><i><span style="text-align:center; font-size:10px"><?php echo $cont; ?></span></i></p>
     <p style="text-align:center; font-weight:900;">Grand Total :<?php echo $tot; ?> naira</p>  
     <p style="text-align:center;"><i>Thank you for your patronage</i></p>
      <a href="dashboard.php" style="text-align:center;" class="con">Done</a>
      </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
</body>
</html>