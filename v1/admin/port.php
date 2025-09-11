<?php include"header.php" ?>
 

  <main id="main">
 <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

       
        
        
        
        
        
        
        
        
    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Services</h2>
          <p> Sales Report</p>
        </div>
  
<p><center><form action="" method="post"><input type="date" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p><center><h3>Total Sales : ₦
         <?php
          include "connect_to_mysqli.php";
           if(empty($_POST['kayd']))
	  {
         $rent=date("Y-m-d"); // current date
$sql = "SELECT sum(price) from cart where status='Paid' && date ='$rent'";
$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))

$k=$row[0];
if ($k=="")
{
echo '0';    
}
else{
echo $k;
}
}
else
{
$dare=$_POST['kayd'];    
    
 $sql = "SELECT sum(price) from cart where status='Paid' && date ='$dare'";
$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))

$k=$row[0];
if ($k=="")
{
echo '0';    
}
else{
echo $k;
}   
    
}
  ?>   
            
            
        </h3></center></p>
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
				<th data-column-id='employee_name'  width='200px'>Date</th>
					<th data-column-id='employee_name'  width='200px'>Amount</th>
					  <th data-column-id='employee_name'  width='200px'>Service</th>
				     <th data-column-id='employee_name'  width='200px'>Units</th>
				
			        
				
					
				</tr>
			</thead>
		  <?php
  
$rent=date("Y-m-d"); // current date		   
 include "connect_to_mysqli.php";
	
	     if(empty($_POST['kayd']))
	  {
       
        $sql = "SELECT date as dater, service as name, Count(service) As most, 
        SUM(price) as amount FROM cart WHERE status='Paid' && date='$rent' GROUP BY SERVICE ORDER BY service DESC  ";
		  $sql2 = mysqli_query($con,$sql);
	
while ($row = mysqli_fetch_array($sql2)) {
   
if($row)
{
echo "<tr bgcolor='#fff'><td width='200px' >" . $row['dater'] . "</td><td width='200px'>₦" . $row['amount']."</td>
<td width='200px'>" . $row['name']."</td><td width='200px'>" . $row['most']."</td></tr>";


} 	}}

else{
		   
$dare=$_POST['kayd'];
$sql = "SELECT date as dater, service as name, Count(service) As most, 
        SUM(price) as amount FROM cart WHERE date ='".$dare."' && status='Paid' GROUP BY SERVICE ORDER BY service DESC ";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {

echo "<tr bgcolor='#fff'><td width='200px' >" . $row['dater'] . "</td><td width='200px'>₦" . $row['amount']."</td>
<td width='200px'>" . $row['name']."</td><td width='200px'>" . $row['most']."</td></tr>";
				
}



}
	  ?> 
	  </table></div>
	  </p>
 
 <div style="text-align:center;" > <a href="carte.php" class="btn-buy"><i class="dwn"></i><u>Export For Services</u></a> </div>
  <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Refreshement </h2>
          <p> Sales Report </p>
        </div>
  
<p><center><form action="" method="post"><input type="date" name="kay" /><input type="submit" value="Search" /></form></center></p>
<p><center><h3>Total Sales : ₦
         <?php
          if(empty($_POST['kay']))
	  {
	      $rent=date("Y-m-d"); // current date
$sql = "SELECT sum(priced) from enter where status='Paid' && date='$rent' ";
$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))

$k=$row[0];
if ($k=="")
{
echo '0';    
}
else{
echo $k;
}}

else{
    $dare=$_POST['kay'];
  $sql = "SELECT sum(priced) from enter where status='Paid' && date='$dare' ";
$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))

$k=$row[0];
if ($k=="")
{
echo '0';    
}
else{
echo $k;
}  
}

  ?>   
            
            
        </h3></center></p>
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'  width='200px'>Date</th>
					<th data-column-id='employee_name'  width='200px'>Amount</th>
				     <th data-column-id='employee_name'  width='200px'>Item</th>
				     <th data-column-id='employee_name'  width='200px'>Quantity</th>
			        
				
					
				</tr>
			</thead>
		  <?php

		   
 include "connect_to_mysqli.php";
	$rent=date("Y-m-d"); // current date
	     if(empty($_POST['kay']))
	  {
       
        $sql = "SELECT date as dater, id as name, Count(id) As most, 
        SUM(priced) as amount FROM enter WHERE status='Paid' && price!='0' && date='$rent' GROUP BY ID ORDER BY id DESC ";
		  $sql2 = mysqli_query($con,$sql);
	
while ($row = mysqli_fetch_array($sql2)) {
   
if($row)
{
echo "<tr bgcolor='#fff'><td width='200px' >" . $row['dater'] . "</td><td width='200px'>₦" . $row['amount']."</td>
<td width='200px'>" . $row['name']."</td><td width='200px'>" . $row['most']."</td></tr>";


} 	}}

else{
		   
$dare=$_POST['kay'];
  $sql = "SELECT date as dater, id as name, Count(id) As most, 
        SUM(priced) as amount FROM enter WHERE date ='".$dare."' && status='Paid' && price!='0' GROUP BY ID ORDER BY id DESC ";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {

echo "<tr bgcolor='#fff'><td width='200px' >" . $row['dater'] . "</td><td width='200px'>₦" . $row['amount']."</td>
<td width='200px'>" . $row['name']."</td><td width='200px'>" . $row['most']."</td></tr>";
				
}



}
	  ?> 
	  </table></div>
	  </p>     
<div style="text-align:center;" >
        <a href="entere.php" class="btn-buy"><i class="dwn"></i><u>Export For Items</u></a>
        
    </div>
      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>