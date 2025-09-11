<?php include"header.php"; 




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




echo '<script type="text/javascript">
$(document).ready(function(){
$("#myModal").modal("show");
});
</script>';}
					 



?>

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
font-weight:700;

}
.img{
max-width:50%;
max-height:50%;
border-radius:50%;
}

.btn-buya {
  display: inline-block;
  padding:6px !important;
  border:none;
  color: #fff;
  font-size: 10px !important;
  text-transform:uppercase;
  font-family: "Poppins", sans-serif;
  font-weight: 600;
  transition: 0.3s;
  background:#FEBF01;
  margin:4px;
  max-width:120px;
  
  
}


.btn-buya:hover {
  display: inline-block;
  padding:6px !important;
  border:none;
  color: #fff;
  font-size: 12px !important;
  text-transform:uppercase;
  font-family: "Poppins", sans-serif;
  font-weight: 800;
  transition: 0.3s;
  background:#000;
  
  
}

.form-control{
height:40px;
border-radius:none !important;
}
</style>

 <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
    <div class="container" style="width:100%; margin:auto; ">
    <div class="section-title" style="color:#FFFFFF;">
    <h2>REFRESHMENTS</h2>
    <p>Get refreshed with our food options</p>
    </div>

        <div class="row">
        <div class="col-lg-4 col-md-4">
		<p style="color:#FEBF01;">Food,Snacks,Drinks and much more..</p>
        <div class="box" data-aos="zoom-in" data-aos-delay="100">
		</div></div>
			
            <div class="col-lg-12 col-md-12">
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
            <p  style="text-align:center; margin-top:8px;"><button onclick="showAllItems()" id="clocsButtonAll" value="all" type="button" class="btn-buya">ALL ITEMS</button>
<?php
$sql = "SELECT * from food_categories ORDER BY s";
$sql2 = mysqli_query($con, $sql);
while($row = mysqli_fetch_array($sql2)) {
    echo '<button onclick="showCategory(\''.$row['name'].'\')" id="clocsButton'.$row['name'].'" value="'.$row['name'].'" type="button" class="btn-buya">'.$row['name'].'</button>';
}
?>

<script>
    function showCategory(category) {
        // Hide all items first
        let items = document.querySelectorAll('.ter');
        items.forEach(item => {
            item.style.display = 'none';
        });

        // Show items of the selected category
        let categoryItems = document.querySelectorAll('.' + category);
        categoryItems.forEach(item => {
            item.style.display = 'table-row';
        });
    }

    function showAllItems() {
        // Show all items
        let items = document.querySelectorAll('.ter');
        items.forEach(item => {
            item.style.display = 'table-row';
        });
    }
</script>

       
</p>
			  
             <p><form enctype="multipart/form-data" method="post">
            <table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead><th></th><th></th><th></th></thead><tbody><div>
  
<?php 
  
$sql = "SELECT * from food_menu ORDER BY item";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
				 		$imageURL='../orishirishi/'.$row["file_name"];
						$quantity = $row['quantity'];		
				
				if ($quantity > 1)
				{
				$show = 'Quantity<input class="form-control" type="number"  max="'.$row['quantity'].'" min="1" name="value" value="1" /><br>
                       <button type="submit" name="addtocart" class="btn-buya" >Add To Cart</button>';    
				}
				else
				{
				$show='<p style="color: #FFC700;">Out Of Stock</p>';
				}

echo'<form action="" method="post">
    <tr class="ter mx-3 '.$row['type'].'" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check">
	<input type="radio" style="pointer-events:none;" value="'.$row['s'].'" name="food" hidden />
	<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['item'].'</span><br>&#8358;'.$row['price'].'.00</td>
	<td class="check" style="font-size:14px;">'.$show.'
	</td></tr></form>';
	}
	
	?>
						
						
						
						
						
							
						
						
						
						
						
						
						
						
	
	
	

	</tbody>
	</table>
	
<div class="btn-wrap">
<a href="cart.php" name="submit" class="btn-buy" >Procced to Payment </a></div>
</div></p></p>
</div>
</form>
  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Added to Cart!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:#FFFFFF;"></button>
      </div>
      <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
        <p style="font-weight:600; font-size:13px;">Your item has been successfully added to cart.<br>Press ok to continue or Proceed to payment directly</p>
			<p><button class="submitn"  data-bs-dismiss="modal">Ok,Add More To Cart</button></p>
         	<p><a href="cart.php" ><button class="submitn" >Proceed to Payment</button></a></p> 
          
      </div>
    </div>
  </div>
</div></div>		



















<?php include"footer.php"; ?>