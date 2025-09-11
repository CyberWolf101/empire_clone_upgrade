<?php include "header.php";

if (isset($_GET['category'])){
$category=$_GET['category'];
}
else{
header("Location: " . $_SERVER['HTTP_REFERER']);
}
?>

 
<script>
function check()
{
document.getElementById('results').scrollIntoView();
}
</script>
<style>
.ter{
background-color:#fff;
padding:0 10px;
}
.check{
padding:2%;
font-size:12px;
width:25%;
}
.check span{

font-size:13px;
font-weight:500;

}
.img{
width:30%;
height:auto;
border-radius:50%;
}
.submitn{
  background: #FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px;
  font-size: 10px;
  font-weight: 600;
  outline:none;
  border:none;
 
}

.submitn:hover{
  background: #000000;
  color: #fff;
  outline:none;
  border:none;
}

		  .btn-buya {
  display: inline-block;
  padding:10px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#FFC700;
  margin-bottom:20px;
  width:300px;
  
}
	  .btn-buya:hover {
  display: inline-block;
  padding:10px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#000000;
  margin-bottom:20px;
  width:300px;
  
}

.advert{
background:#FFC700;
width:100%;
height:40px;
font-weight: 800;
font-size:14px;
color:#fff;
padding:10px;
}
#clocs
{
display:none;}

#cloch{
display:none;
}
</style>

    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
          <div class="container" style="width:100%; margin:auto; ">
          <div class="section-title" style="color:#FFFFFF;">
          <h2>RENTAL FOR BEAUTY AND SKILL TRAINING</h2>
          </div>

            <div class="row">
             <div class="col-lg-12 col-md-12">
		     <p style="color:#FEBF01;">Prices are per day</p>
             <div class="box" data-aos="zoom-in" data-aos-delay="100">
			 </div></div>
			
               <div class="col-lg-12 col-md-12">
              <div class="box" data-aos="zoom-in" data-aos-delay="100">
                  


             <div id="main"><form method='post' class='user-form'>
             <table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px; padding:10px 0px;">
        	 <tbody>
     <?php if($counts=="0"){ ?>
     <tr class="ter mx-3" onclick='this.querySelector("input[type=checkbox]").click();' >
	<td class="check"><input type="checkbox" style="pointer-events:none;"  value="1" name="hall" required />
	<span>&nbsp; &nbsp; &nbsp; &nbsp;  Rental Hall</span><br></td>
    <td colspan="2" class="check" style="font-size:16px">&#8358; <?php echo $rentprice; ?></td>
    </tr> <?php } ?>
 <tr><td colspan="3"><h5>AVAILABLE ITEMS</h5></td></tr> 
<?php 
  
$sql = "SELECT * FROM rental_items WHERE FIND_IN_SET('$category', category) > 0";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
 {
				 					
		

echo'<tr class="ter mx-3" onclick=\'this.querySelector("input[type=checkbox]").click();\' >
	<td class="check"><input type="checkbox" style="pointer-events:none;"  value="'.$row['s'].'" name="item[]"  />
	<span>&nbsp; &nbsp; &nbsp; &nbsp;  '.$row['name'].'</span><br></td>
    <td class="check" style="font-size:16px">&#8358;'.$row['price'].'.00</td>
     <td class="check" style="font-size:16px;">
    Quantity <input class="form-control" type="number" min="1" value="5" name="quantity['.$row['s'].']" placeholder="1" /></td>
    </tr>';



	}
	
	?>
	
	
		</tbody>
     	</table></div>
				

   
               <div class="btn-wrap" align="center" style="margin-bottom:40px;">
			   <button type="submit" name="submit" value="add" class="submitn btn-buya">ADD TO CART</button><br />
			   </form></div>
   
    </div>
    </section><!-- End Pricing Section -->

<?php
if (isset($_POST['submit'])) {

//hall
if (isset($_POST['hall'])) {
$hall=$_POST['hall'];
$hallprice=$rentprice*$days;

$submit = mysqli_query($con, "INSERT INTO rental_cart(`id`, `item`, `itemname`, `itemprice`, `quantity`, `days`, `total`) 
VALUES ('$saloon','0','Rental Hall','$rentprice','1','$days','$hallprice')") or die ('Could not connect: ' . mysqli_error($con));
}




//items
if (isset($_POST['item'])) {
    $selectedItem = $_POST['item'];
    
    
        for ($i = 0; $i < count($selectedItem); $i++) {
        $itemID = $selectedItem[$i];
        $itemValue = $_POST['quantity'][$itemID];

    // Fetch information about the selected protein from your database.
    $sqk = "SELECT * FROM rental_items WHERE s= '$itemID'";
    $sqlp = mysqli_query($con, $sqk);
    if ($sqlp) {
        while ($rowe = mysqli_fetch_array($sqlp)) {
            $itemname = $rowe['name'];
            $itemprice = $rowe['price'];
        }

        $bot = "SELECT * FROM rental_cart WHERE id='$saloon' AND item='$itemID'";
        $bot2 = mysqli_query($con, $bot);

        if (mysqli_affected_rows($con) == 0) {
            $itemtotal =($itemValue * $itemprice)*$days;
            $submit = mysqli_query($con, "INSERT INTO rental_cart(`id`, `item`, `itemname`, `itemprice`, `quantity`, `days`, `total`) 
            VALUES ('$saloon','$itemID','$itemname','$itemprice','$itemValue','$days','$itemtotal')") or die ('Could not connect: ' . mysqli_error($con));
        } else {
            $newquantity = "";
            $totalvalue = "";

            $sqk ="SELECT * FROM rental_cart WHERE id='$saloon' AND item='$itemID'";
            $sqlp = mysqli_query($con, $sqk);
            
            while ($rowe = mysqli_fetch_array($sqlp)) {
                $quantity = $rowe['quantity'];
                $rowfood = $rowe['s'];
            }

            $newquantity = $quantity + $itemValue;
            $totalvalue = ($newquantity * $itemprice) *$days;

            $insert = mysqli_query($con, "UPDATE rental_cart SET total= '$totalvalue' WHERE s='$rowfood'") or die ('Could not connect: ' . mysqli_error($con));
            $insert = mysqli_query($con, "UPDATE rental_cart SET itemprice= '$itemprice' WHERE s='$rowfood'") or die ('Could not connect: ' . mysqli_error($con));
            $insert = mysqli_query($con, "UPDATE rental_cart SET days= '$days' WHERE s='$rowfood'") or die ('Could not connect: ' . mysqli_error($con));
            $insert = mysqli_query($con, "UPDATE rental_cart SET quantity= '$newquantity' WHERE s='$rowfood'") or die ('Could not connect: ' . mysqli_error($con));
        }
    }
}}










   
header("location:cart.php");
}

include"footer.php";  ?>