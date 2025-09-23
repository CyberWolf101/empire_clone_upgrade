<?php include "header.php";

/// Delete
if(isset($_GET['categoryid'])){
    $order_id = $_GET['categoryid'];
    $insert = mysqli_query($con,"UPDATE saloon_orders SET status='completed' where id='$order_id'") or die ('Could not connect: ' .mysqli_error($con)); 
   echo "<script>  alert('This order has been successfully marked as successful!'); window.location.href = 'viewkitchen.php?order=$order_id'; // Refresh the current page
</script>";

    exit(); // Make sure to exit the script after the alert
}



if (isset($_GET['order'])){
$saloon=$_GET['order'];

$sql = "SELECT * from saloon_orders where id='$saloon' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$type=$row["bookingtype"]; 
 $kit=$row["saloonkit"];
 $customername=$row["name"];
 $customerphone=$row["phone"];
 $date=$row["date"];
 $total_all=$row["total_amount"];
 $location=$row["type"];
 $method=$row["method"];
 $pr=$row['preorder'];
$prdate=$row['preorder_date'];
 
 $status=$row['status'];
 $section=$row['section'];

//color
if ($status=="no"){
  $bg="badge-warning";
  $status="booking";
}
else if ($status=="processing"){
  $bg="badge-primary";
}
else if ($status=="cancelled"){
  $bg="badge-danger";
}
else if ($status=="processed" || $status=="completed"){
  $bg="badge-success";
}
}
    
    

if($pr=="1"){
$prtext="<span class='badge badge-primary' style='text-transform:capitalize;'>Preorder</span><br>$prdate";
}else{
$prtext="<span class='badge badge-warning' style='text-transform:capitalize;'>Instant Order</span>";
}


}

else{
header("location:dashboard.php");
}

?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
             <h1 class="h5 mb-0 text-gray-800">Order ID #<?php echo $saloon;?></h1>
             <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Details</li>
             </ol>
             </div>


 
    
    
    
    
           <!-- Row -->
<div class="row justify-content-center mb-8">          


            <!-- Datatables -->
            <div class="col-lg-12">
                
                
                
<p><?php echo $prtext; ?></p>             
<p><span class='badge <?php echo $bg; ?>'><?php echo $status; ?></span><br>
Customer Details <br>
Name: <?php echo $customername; ?> <br>
Phone: <?php echo $customerphone; ?> </p>
<p>Date:  <?php echo $date; ?><br>
Payment Method: <?php echo $method; ?></p>   
                
                
              <div class="card mb-4">

            
            <?php  
$bot = "SELECT all* from delta_cart where id='$saloon' ";
$bot2 = mysqli_query($con,$bot);
if (mysqli_affected_rows($con) > 0){
?>
          
                  

          
            <!-- Datatables -->
          
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-warning">Customer Cart</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary">
                    <thead class="thead-light">
                      <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
$sql = "SELECT * from delta_cart where id='$saloon' ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {

echo "
                         <tr>
                         <td>".$row['itemname']."</td>
                        <td>&#8358;".$row['unitprice']."</td>	
                        <td>".$row['quantity']."</td>
                        <td>&#8358;".$row['totalprice']." </td>
                        </tr>";

        
}
?> 
                      
                    </tbody>
                  </table>
          
          
              <?php } ?> <br> 
               <center><h4 class="font-weight-bold">GRAND TOTAL: &#8358;<?php echo $total_all; ?> </h4>
                <?php if($status =="processed"){?><p><form action='' method='get' onsubmit='return confirm("Are you sure you want to mark this order as completed?");'>
		        <input type='text' name='categoryid' value='<?php echo $saloon; ?>' required hidden>  
                <input type='submit' name='delete' value='Mark As Completed' class='btn btn-sm btn-primary' ></form></p> <?php } ?> </center> 
        
               </div>
               </div>
               </div>
          








    </div>
          
          
          
<?php  include "footer.php"; ?>