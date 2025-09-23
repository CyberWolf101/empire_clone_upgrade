<?php include "header.php";




if (isset($_GET['giftcard'])){
$saloon=$_GET['giftcard'];

$sql = "SELECT * from giftcard where giftcardno='$saloon' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
 $customername=$row["ownername"];
 $email=$row["owneremail"];
}}
else{
header("location:dashboard.php");
}

?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
             <h1 class="h5 mb-0 text-gray-800">Giftcard ID #<?php echo $saloon;?></h1>
             <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Details</li>
             </ol>
             </div>


 
    
    
    
    
           <!-- Row -->
<div class="row justify-content-center mb-8">          


            <!-- Datatables -->
            <div class="col-lg-12">
                
                
                
             
<p>
Customer Details <br>
Name: <?php echo $customername; ?> <br>
Email: <?php echo $email; ?> <br>


 
                
                
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-warning">Giftcard History</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary">
                    <thead class="thead-light">
                      <tr>
                        <th>Amount Deducted</th>
            <th>Amount Left</th>
            <th>Transaction ID</th>
            <th>Date</th>
            <th>Description</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
$sqls = "SELECT * from giftcard_history where giftcardno='$saloon' AND status='processed' ORDER BY s ASC";
$sql2s = mysqli_query($con,$sqls);
while ($row = mysqli_fetch_array($sql2s)) {


                echo "<tr>";
                echo "<td>₦" . $row["amount_deducted"] . "</td>";
                echo "<td>₦" . $row["amount_left"] . "</td>";
                echo "<td>" . $row["orderid"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
                 echo "<td>" . $row["description"] . "</td>";
                echo "</tr>";
        
}
?> 
                      
                    </tbody>
                  </table>
            
            
            
                <br> 
              
  
        
               </div>
               </div>
               </div>
          








    </div>
          
          
          
<?php  include "footer.php"; ?>