<?php include "header.php";

/// Delete
if(isset($_GET['categoryid'])){
    $order_id = $_GET['categoryid'];
    $insert = mysqli_query($con,"UPDATE rentals SET status='completed' where orderid='$order_id'") or die ('Could not connect: ' .mysqli_error($con)); 
   echo "<script>  alert('Rental service successfully marked as completed!'); window.location.href = 'viewrental.php?order=$order_id'; // Refresh the current page
   </script>";

    exit(); // Make sure to exit the script after the alert
}



if (isset($_GET['order'])){
$saloon=$_GET['order'];

$sql = "SELECT * from rentals where orderid='$saloon' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$dates=$row["dates"]; 
 $days=$row["days"];
 $customername=$row["name"];
 $customerphone=$row["phone"];
 $email=$row["email"];
 
 $stats=$row['status'];

//color
//color
if ($stats=="no"){
  $bg="badge-warning";
  $status="booking";
}
else if ($stats=="processing"){
  $bg="badge-primary";
}
else if ($stats=="cancelled"){
  $bg="badge-danger";
}
else if ($stats=="processed" || $stats=="completed"){
  $bg="badge-success";
}}}

else{
header("location:dashboard.php");
}

?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
             <h1 class="h5 mb-0 text-gray-800">Rental ID #<?php echo $saloon;?></h1>
             <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Details</li>
             </ol>
             </div>


 
    
    
    
    
           <!-- Row -->
<div class="row justify-content-center mb-8">          


            <!-- Datatables -->
            <div class="col-lg-12">
                
                
                
             
<p><span class='badge <?php echo $bg; ?>'><?php echo $stats; ?></span><br>
Customer Details <br>
Name: <?php echo $customername; ?> <br>
Email: <?php echo $email; ?> <br>
Phone: <?php echo $customerphone; ?> </p>
<p>Day(s) : <?php echo $days; ?></p> 

<p>Dates <?php echo $dates; ?></p>   
                
                
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-warning">Rental Cart</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary">
                    <thead class="thead-light">
                      <tr>
                         <th>S/N</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
$sql = "SELECT * from rental_cart where id='$saloon' ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo "
                         <tr>
                          <td> ".$i++." </td>
                         <td>".$row['itemname']."</td>	
                         <td>".$row['quantity']."</td>
                         <td>&#8358;".$row['total']."</td>
                        </tr>";

        
}
?> 
                      
                    </tbody>
                  </table>
            
            
            
                <br> 
               <center><?php if ($status=="superadmin" || $status=="subadmin" && $stats =="processed"){?>
                 <p><form action='' method='get' onsubmit='return confirm("Are you sure you want mark this rental as completed?");'>
		        <input type='text' name='categoryid' value='<?php echo $saloon; ?>' required hidden>  
                <input type='submit' name='delete' value='Mark as Completed' class='btn btn-sm btn-danger w-100' ></form></p> <?php } ?></center> 
               </div>
               </div>
               </div>
          








    </div>
          
          
          
<?php  include "footer.php"; ?>