<html>
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
    
    
<p><a href="#" class="btn btn-primary con" style="background-color:#FF339A;  color:white; font-size:14px; padding:5px 10px; margin-top:10px;  float:right; border:none;  outline:none;" onclick="window.print()" >
<i class="icon-download"></i> Print Reciept</a></p>
<?php
include "../connect.php";



if(isset($_GET['order'])){
$order =  $_GET['order'];
$sql = "SELECT * from saloon_orders where id ='$order'";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$type=$row["bookingtype"]; 
 $kit=$row["saloonkit"];
 $customername=$row["name"];
 $customerphone=$row["phone"];
 $date=$row["date"];
 $total=$row["total_amount"];
 $location=$row["type"];
}}

else{
    header("location:dashboard.php");
}

    ?>
    
    
<div style="width:80%; height:50vh; margin:auto;">
<p style='text-align:left;'>
<img src='favicon.png' width='100px' height='80px' style='margin-top:13px;'/><br>
<span >BookingID : <?php echo $order; ?>
<br>Date:  <?php echo $date; ?></span> </p>
	
<p>Customer Details <br>
Name: <?php echo $customername; ?> <br>
Phone: <?php echo $customerphone; ?> </p>



<div class="overflow-auto">



<table class='table table-condensed table-hover table-striped' width='300px' border="0" cellspacing='2' data-toggle='bootgrid'>
<thead>
<tr  bgcolor="#CCCCCC">
<th data-column-id='employee_name' >Item</th>
<th data-column-id='employee_name' >Quantity</th>
<th data-column-id='employee_name' >Price</th>
</thead>
 <?php
$sql = "SELECT * from delta_cart where id='$order'";
$sql2 = mysqli_query($con, $sql);

// Check if there are rows in the result set
if (mysqli_num_rows($sql2) > 0) {
    $name = [];
    $surname = [];
    $address = [];

while ($row = mysqli_fetch_array($sql2)) {

echo"<tr bgcolor='#fff'>
<td width='100px'>" . $row['itemname'] . "</td>
<td width='20px'>" . $row['quantity'] . "</td>
<td width='20px'>&#8358;" . $row['totalprice'] . "</td>
<tr>";

               
}}?> 
     

  
</table></div></p>
<div style="width:300px;"><center>
<p style="font-weight:900;">Grand Total: &#8358;<?php echo $total; ?> </p>  
<p style=""><i>Thank you for your patronage</i></p>
<a href="dashboard.php" class="con">Done</a></center>
</div></div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
<script type="text/javascript">
  window.onload = function() {
    window.print();
  };
  function goBack() {
  window.history.back();
}
</script>    
    
</body>
</html>