<?php include "header.php";

/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $del = mysqli_query($con,"DELETE from refreshments where s='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script> window.location.href = 'refreshment.php';</script>";
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

$today=date("Y-m-d");

?>

<style>
.ter{
background-color:#fff;
padding:0 5px;
}
.check{
padding:2%;
font-size:12px;
width:25%;
}
.check span{

font-size:13px;
font-weight:700;

}
.img{
max-width:30%;
max-height:30%;
border-radius:50%;
}

.btn-buya {
  display: inline-block;
  padding:6px;
  border:none;
  color: #fff;
  font-size: 10px;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
  font-weight: 800;
  transition: 0.3s;
  background:#FEBF01;
  
}

#clocs
{
display:none;}

#cloch{
display:none;
}

.koy{
color:#FFC700;    
}

.submita{
  
  background: #FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px;
  font-size: 14px;
  font-weight: 600;
  outline:none;
  border:none;
  float:right;
  margin-bottom:10%;
 
 
}

.submita:hover{
  background: #000000;
  color: #fff;
  outline:none;
  border:none;
  margin-bottom:10%;
}
</style>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
             <h1 class="h5 mb-0 text-gray-800">Booking ID #<?php echo $saloon;?></h1>
             <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Start Booking</li>
            </ol>
          </div>
          
           <!-- Row -->
          <div class="row">          
    
    
<?php include "foodcart.php"; ?>
<script>
$(document).ready(function() {
    $('#searchInput').on('input', function() {
        var query = $(this).val();
        
        // Perform an AJAX request when user types
        $.ajax({
            type: 'POST',  // You can use GET or POST based on your needs
            url: 'search.php',  // Replace with the URL of your server-side script
            data: { query: query },
            success: function(response) {
                // Display the search results in a div
                $('#searchResults').html(response);
            }
        });
    });
});
</script>


 
          
<div align="center" class="col-lg-12">
<input type="text" id="searchInput"  class="form-control" placeholder="Search by item name here...">
<p><form enctype="multipart/form-data" method="post">
<table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
<thead>
<th></th><th></th></thead>
<tbody id="searchResults">
	    
</tbody>
</table>
</div>














<?php 

//addtocart
if (isset($_POST['addtocart'])){
    
$item =  $_POST['food'];
$value = $_POST['value'];

$sqk = "SELECT * from food_menu where s='$item'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp)){
$itemname = $rowe['item'];
$itemprice = $rowe['price'];
}

$bot = "SELECT * from refreshments where orderid='$saloon' && itemid='$item'";
$bot2 = mysqli_query($con,$bot);
if (mysqli_affected_rows($con) == 0){

$totalvalue=$value*$itemprice;
$submit = mysqli_query($con,"insert into refreshments(orderid,itemid,item,unitprice,quantity,totalprice,status) values ('$saloon','$item','$itemname','$itemprice','$value','$totalvalue','processing')") or die ('Could not connect: ' .mysqli_error($con));
}




else{

$sqk = "SELECT * from refreshments where orderid='$saloon' AND itemid='$item'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp)){
$quantity = $rowe['quantity'];
$rowfood = $rowe['s'];
}

$newquantity=$quantity+$value;
$totalvalue=$newquantity*$itemprice;


$insert = mysqli_query($con,"UPDATE refreshments SET totalprice= '$totalvalue' where s='$rowfood'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE refreshments SET unitprice= '$itemprice' where s='$rowfood'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE refreshments SET quantity= '$newquantity' where s='$rowfood'") or die ('Could not connect: ' .mysqli_error($con)); 
}
header("location:refreshment.php");
}


include "footer.php"; ?>