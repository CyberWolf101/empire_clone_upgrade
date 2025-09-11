<?php include "header.php";

if (isset($_GET['category'])){
$category=$_GET['category'];
}
else{
header("Location: " . $_SERVER['HTTP_REFERER']);
}
  
$sql = "SELECT * from gift_packages where id='$category' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$name=$row["name"];
$price=$row["price"];
$categories = explode(',', $row['services']); 
$categoryNames = array();
}
?>

 
<script>
function check()
{
document.getElementById('results').scrollIntoView();
}
</script>
  <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
    <div class="container" style="width:100%; margin:auto; ">
    <div class="section-title" style="color:#FFFFFF;">
    <h4 style="text-transform:uppercase;">PACKAGE - <?php echo $name; ?> (&#8358;<?php echo $price; ?>) </h4>
     </div>

    <div class="row">
    <div class="col-lg-12 col-md-12">
    <div class="box" data-aos="zoom-in" data-aos-delay="100">
        
        
    <div class="btn-wrap" style="margin-bottom:30px;">
    <?php if($count_vs > 0) { ?><span><a class="btn-buya" href="cart.php">VIEW CART</a></span><?php } ?></div>




    <p><form action="" method="post">
    <table id="result" width="95%" border="0"  cellspacing='0' style="border-collapse:separate;  font-size:13px; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
	<thead><th colspan="3">For tbis package,you get a one time session of the services below for a one time payment of &#8358;<?php echo $price; ?></th></thead>
	<tbody>

<?php

foreach ($categories as $value) {
    $sq = "SELECT * FROM services WHERE id = '" . $value . "'";
    $sq2 = mysqli_query($con, $sq);
    
    while ($rom = mysqli_fetch_array($sq2)) {
        $categoryname = $rom['name'];
        // Add each category name to the array
        $categoryNames = $categoryname;}

        echo '
        <tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click(); check();\' >
        <td class="check"><span>' .  $categoryNames. '</span></td></tr>';

}
?>

	</tbody>
	</table>
	
	
	
	
	
	


			
			   <div class="btn-wrap">
               <div class="btn-wrap" align="center">
               <a href="index.php" class="submitn">BACK TO MAIN</a>
			   <button type="submit" name="submit" value="submit" class="submitn">ADD PACKAGE TO CART</button><br />
			   </form></div></div>
               </div>
               </div>

  
	


	

       
        </div>
        </section><!-- End Pricing Section -->


<?php 

if (isset($_POST['submit'])) {
    $bot = "SELECT * FROM voucher_cart WHERE orderid='$saloon' AND itemname='$name'";
    $bot2 = mysqli_query($con, $bot);
    
    if (mysqli_num_rows($bot2) == 0) {
        // Insert a new record when no matching record is found
        $submit = mysqli_query($con, "INSERT INTO voucher_cart(`orderid`, `item`, `itemname`, `price`, `quantity`, `totalprice`) 
        VALUES ('$saloon', '$category', '$name', '$price', 1, '$price')") or die('Could not connect: ' . mysqli_error($con));
    } else {
        $newquantity = "";
        $totalvalue = "";
        $rowfood = ""; // Initialize $rowfood here
        
        $sqk = "SELECT * FROM voucher_cart WHERE orderid='$saloon' AND itemname='$name'";
        $sqlp = mysqli_query($con, $sqk);
        
        while ($rowe = mysqli_fetch_array($sqlp)) {
            $quantity = $rowe['quantity'];
            $rowfood = $rowe['s'];
        }
        
        $newquantity = $quantity + 1;
        $totalvalue = $newquantity * $price;
        
        // Update existing records
        $update1 = mysqli_query($con, "UPDATE voucher_cart SET totalprice= '$totalvalue' WHERE s='$rowfood'") or die('Could not connect: ' . mysqli_error($con));
        $update2 = mysqli_query($con, "UPDATE voucher_cart SET price= '$price' WHERE s='$rowfood'") or die('Could not connect: ' . mysqli_error($con));
        $update3 = mysqli_query($con, "UPDATE voucher_cart SET quantity= '$newquantity' WHERE s='$rowfood'") or die('Could not connect: ' . mysqli_error($con));
    }

    header("location: cart.php");
}


include"footer.php";  ?>