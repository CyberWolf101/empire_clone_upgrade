<?php include "header.php";

/// Delete
if(isset($_GET['categoryid'])){
    $order_id = $_GET['categoryid'];
    $insert = mysqli_query($con,"UPDATE members SET status='invalid' where s='$order_id'") or die ('Could not connect: ' .mysqli_error($con)); 
   echo "<script>  alert('Membership Ended successfully!'); window.location.href = 'viewmember.php?order=$order_id'; // Refresh the current page
   </script>";

    exit(); // Make sure to exit the script after the alert
}



if (isset($_GET['order'])){
$saloon=$_GET['order'];

$sql = "SELECT * from members where s='$saloon' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$startdate=$row["start_date"]; 
 $enddate=$row["end_date"];
 $customername=$row["name"];
 $customerphone=$row["phone"];
 $email=$row["email"];
 $type=$row["type"];
 $card=$row["cardno"];
 
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
             <h1 class="h5 mb-0 text-gray-800">Member ID #<?php echo $card;?></h1>
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
<p>Type : <?php echo $type; ?></p> 

<p>From: <?php echo $startdate; ?><br>
To: <?php echo $enddate; ?></p>   
                
                
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
$sql = "SELECT * from member_packages where cardno='$card' ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo "
                         <tr>
                          <td> ".$i++." </td>
                         <td>".$row['servicename']."</td>		
                        </tr>";

        
}
?> 
                      
                    </tbody>
                  </table>
            
            
            
                <br> 
               <center><?php if ($status=="superadmin" & $stats =="valid"){?>
                 <p><form action='' method='get' onsubmit='return confirm("Are you sure you want end this member membership?");'>
		        <input type='text' name='categoryid' value='<?php echo $saloon; ?>' required hidden>  
                <input type='submit' name='delete' value='End Membership' class='btn btn-sm btn-danger w-100' ></form></p> <?php } ?></center> 
        
               </div>
               </div>
               </div>
          








    </div>
          
          
          
<?php  include "footer.php"; ?>