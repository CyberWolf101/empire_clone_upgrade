<?php include "header.php";

/// Delete
if(isset($_GET['categoryid'])){
    $order_id = $_GET['categoryid'];
    $insert = mysqli_query($con,"UPDATE vocuher_orders SET status='invalid' where s='$order_id'") or die ('Could not connect: ' .mysqli_error($con)); 
   echo "<script>  alert('Voucher marked as used successfully!'); window.location.href = 'viewmember.php?order=$order_id'; // Refresh the current page
   </script>";

    exit(); // Make sure to exit the script after the alert
}



if (isset($_GET['order'])){
$saloon=$_GET['order'];

$sql = "SELECT * from voucher_orders where s='$saloon' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
 $customername=$row["ownername"];
 $customerphone=$row["ownerphone"];
 $email=$row["owneremail"];
 $card=$row["orderid"];
 
 $stats=$row['status'];

//color
if ($stats=="invalid"){
  $bg="badge-warning";
  $status="booking";}
else if ($stats=="valid" || $status=="completed"){
  $bg="badge-success";
}}}

else{
header("location:dashboard.php");
}

?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
             <h1 class="h5 mb-0 text-gray-800">Voucher ID #<?php echo $card;?></h1>
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


 
                
                
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-warning">Services Cart</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary">
                    <thead class="thead-light">
                      <tr>
                         <th>S/N</th>
                        <th>Service</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
$sqls = "SELECT * from voucher_cart where orderid='$card' ORDER BY s ASC";
$sql2s = mysqli_query($con,$sqls);
$i=1;
while ($rows = mysqli_fetch_array($sql2s)) {
$per=$rows['item'];


$sql = "SELECT * from gift_packages where id='$per'";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
$categories = explode(',', $row['services']); 
$categoryNames = array();
 $in=1;
foreach ($categories as $value) {
    $sq = "SELECT * FROM services WHERE id = '" . $value . "'";
    $sq2 = mysqli_query($con, $sq);
    while ($rom = mysqli_fetch_array($sq2)) {
        $categoryname = $rom['name'];
        $categoryNames[] = ($in) . '. ' . $categoryname;
        // Add each category name to the array
     $in++;
    }}}




echo "
                         <tr>
                          <td> ".$i++." </td>
                         <td>".$rows['itemname']."<br><span class='text-warning'>";
                         
                         echo implode("<br>", $categoryNames);
                         
                         echo"</span></td>		
                        </tr>";

        
}
?> 
                      
                    </tbody>
                  </table>
            
            
            
                <br> 
               <center>
                 <?php if($stats =="valid"){?> <p><form action='' method='get' onsubmit='return confirm("Are you sure you want to mark this voucher as used?");'>
		        <input type='text' name='categoryid' value='<?php echo $saloon; ?>' required hidden>  
                <input type='submit' name='delete' value='Mark Voucher As Used' class='btn btn-sm btn-danger w-100' ></form></p> <?php } ?></center> 
        
               </div>
               </div>
               </div>
          








    </div>
          
          
          
<?php  include "footer.php"; ?>