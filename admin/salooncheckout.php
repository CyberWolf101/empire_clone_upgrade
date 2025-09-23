<?php include "header.php";

/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $del = mysqli_query($con,"DELETE from appointments where s='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script> window.location.href = 'salooncheckout.php';</script>";
    exit(); // Make sure to exit the script after the alert
}

/// Delete
if(isset($_GET['foodid'])){
    $service_delete = $_GET['foodid'];
    $del = mysqli_query($con,"DELETE from refreshments where s='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script> window.location.href = 'salooncheckout.php';</script>";
    exit(); // Make sure to exit the script after the alert
}




if (isset($_COOKIE['bookingID'])){
$saloon=$_COOKIE['bookingID'];


$sql = "SELECT * from saloon_orders where id='$saloon' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$type=$row["bookingtype"]; 
 $kit=$row["saloonkit"];
 $username=$row["name"];
}}

else{
header("location:bookings.php");
}


if (isset($_POST['kit'])) {
// Checkbox is checked
$saloonkitValue = $_POST['kit'];
if($saloonkitValue=="1"){ $saloonkitValue=$kit_price; }
$insert = mysqli_query($con, "UPDATE saloon_orders SET saloonkit ='$saloonkitValue' WHERE id = '$saloon'") or die('Could not connect: ' . mysqli_error($con));
}

$today=date("Y-m-d");

//services                         
$som = "SELECT sum(price) from appointments where id='$saloon'";
$som2 = mysqli_query($con,$som);
while($ros = mysqli_fetch_array($som2))
$total_services=$ros[0];


//refreshments
$sam = "SELECT sum(totalprice) from refreshments where orderid='$saloon' ";
$sam2 = mysqli_query($con,$sam);
while($row = mysqli_fetch_array($sam2))
$total_items=$row[0];

//total services booked
$extrac= mysqli_query($con,"SELECT * from appointments where id='$saloon'");
$count_services = mysqli_num_rows($extrac);
		            
		            
//Grand Total
$total_all=$total_services+$total_items+$kit;
$insert = mysqli_query($con,"UPDATE saloon_orders SET total_amount='$total_all' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con)); 

?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
             <h1 class="h5 mb-0 text-gray-800">Booking ID #<?php echo $saloon;?></h1>
             <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
          </div>


 
    
    
    
    
           <!-- Row -->
<div class="row justify-content-center mb-8">          
<div class="col-lg-12" style="margin-top:2%;">
<center><p><a href="refreshment.php"  class='btn btn-sm btn-warning'>Add Refreshments</a>
<a href="booking.php"  class='btn btn-sm btn-warning'>Add Services</a></p></center></div>

            <!-- Datatables -->
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-warning">Services Cart</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary">
                    <thead class="thead-light">
                      <tr>
                        <th>Service</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Staff</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
$sql = "SELECT * from appointments where id='$saloon' ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {

echo "
                         <tr>
                         <td>".$row['servicename']."</td>
                         <td>".$row['start_time']." - ".$row['end_time']." </td>	
                        <td>&#8358;".$row['price']."</td>	
                        <td>".$row['staffname']."</td>	
                        <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this service (".$row['servicename'].")?\");'>
		                <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Service' class='btn btn-sm btn-danger' ></form></td>	
                        </tr>";

        
}
?> 
                      
                    </tbody>
                  </table>
            
            
            
            <?php 
$bot = "SELECT all* from refreshments where orderid='$saloon' ";
$bot2 = mysqli_query($con,$bot);
if (mysqli_affected_rows($con) > 0){
?>
          
                  

          
            <!-- Datatables -->
          
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-warning">Refreshment Cart</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary">
                    <thead class="thead-light">
                      <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
$sql = "SELECT * from refreshments where orderid='$saloon' ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {

echo "
                         <tr>
                         <td>".$row['item']."</td>
                        <td>&#8358;".$row['unitprice']."</td>	
                        <td>".$row['quantity']."</td>
                        <td>&#8358;".$row['totalprice']." </td>
                        <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this item (".$row['item'].")?\");'>
		                <input type='text' name='foodid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Item' class='btn btn-sm btn-danger' ></form></td>	
                        </tr>";

        
}
?> 
                      
                    </tbody>
                  </table>
          
          
   <?php } ?>  
   
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-warning">Extra details</h6>
                  </div>
   
                 <p> <form id="myForm" method="post">
                  <label> Purchase a pedicure spa kit (Additional &#8358;500)</label><br>
                  <input type="radio" name="kit" value="1" <?php if($kit > 0){ echo "checked";} ?> id="kitCheckboxYes"/> <label>Yes</label> 
                  <input type="radio" name="kit" value="0" <?php if($kit=="0"){ echo "checked";} ?> id="kitCheckboxNo" /> <label>No</label> 
                 </form></p>
               
               
                <center><h4 class="font-weight-bold">GRAND TOTAL: &#8358;<?php echo $total_all; ?> </h4></center>
   
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-warning">Customer details</h6>
                  </div>
                  <form method="post">
                  <p><select id="customerType" class="form-control" name="customertype" required>
                  <option value="" selected>- Select Customer -</option>
                  <option value="new">New Customer</option>
                  <option value="old">Regular Customer</option>
                 </select></p>
                 
                
   
            <div id="newCustomerFields" style="display: none;">
            <input type="text" id="name" class="form-control"    placeholder="Enter customer name" name="customername" >
            <br>
            <input type="email" id="email" class="form-control" placeholder="Enter customer email(optional)" name="customeremail">
            <br>
            <input type="tel" id="phone" class="form-control"   placeholder="Enter customer phone number" name="customerphone">
            </div>
        
            <div id="oldCustomerFields" style="display: none;">
            <select id="oldCustomer" name="customer" class="select2-single-placeholder form-control" style="width:100%;">
            <option value="" selected>- Select Regular Customer -</option>
             <?php 
$sql = "SELECT * from saloon_orders where name!='' GROUP By name";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2)){
echo'<option value="'.$row['name'].'">'.$row['name'].'</option>';
}
?> 
            </select>
            </div>
   
                 
               
               
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                 <h6 class="m-0 font-weight-bold text-warning">Select Payment Method</h6>
                </div>
               <p><input type="radio" name="method" value="pos" required/> <label> POS</label>
               <input type="radio" name="method" value="cash" required/> <label> Cash</label>
               <input type="radio" name="method" value="transfer" required/> <label> Bank Transfer</label></p>
   
               <p style="text-align:center;"><input type='submit' name='pay' value='Complete Booking' class='btn btn-primary' ></form>	</p>
               </div>
               </div>
               </div>
          

























         <script>
         document.getElementById('customerType').addEventListener('change', function() {
            var selectedValue = this.value;
            var newCustomerFields = document.getElementById('newCustomerFields');
            var oldCustomerFields = document.getElementById('oldCustomerFields');

            if (selectedValue === 'new') {
                newCustomerFields.style.display = 'block';
                oldCustomerFields.style.display = 'none';
            } else if (selectedValue === 'old') {
                newCustomerFields.style.display = 'none';
                oldCustomerFields.style.display = 'block';
            }
        });
 
      
    const kitCheckboxYes = document.getElementById('kitCheckboxYes');
    const kitCheckboxNo = document.getElementById('kitCheckboxNo');
    const myForm = document.getElementById('myForm');

    kitCheckboxYes.addEventListener('change', function() {
        if (this.checked) {
            myForm.submit();
        }
    });

    kitCheckboxNo.addEventListener('change', function() {
        if (this.checked) {
            myForm.submit();
        }
    });
</script>
    </div>
          
          
          
<?php include"saloonpay.php"; include "footer.php"; ?>