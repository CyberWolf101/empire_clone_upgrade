<?php include "header.php";

if (isset($_GET['category'])){
$category=$_GET['category'];
}
else{
header("Location: " . $_SERVER['HTTP_REFERER']);
}
  
$sql = "SELECT * from delta_soups where id='$category' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$name=$row["name"];
$describe=$row["describe"];
$meal_category=$row["additional"];
$price=$row["price"];
$imageURL='../kitchen/'.$row["picture"]; 
}

?>

 
<script>
function check()
{
document.getElementById('results').scrollIntoView();
}
</script>
<style>
.btn-buya{
font-size:12px;
border-radius:0;
padding:5px 2px;
width:150px;
}
</style>
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
          <div class="container" style="width:100%; margin:auto; ">
          <div class="section-title" style="color:#FFFFFF;">
          <h2>PACKAGE - <?php echo $name; ?></h2>
          </div>

            <div class="row">
             <div class="col-lg-3 col-md-4">
		     <p style="color:#FEBF01;">Select your package</p>
             <div class="box" data-aos="zoom-in" data-aos-delay="100">
			 </div></div>
			
               <div class="col-lg-9 col-md-8">
              <div class="box" data-aos="zoom-in" data-aos-delay="100">
                  

<div class="btn-wrap" style="margin-bottom:30px;">
<span style="float:left;"><a class="btn-buya" href="index.php">OTHER SECTIONS</a></span> 
<?php if($count_all > 0) { ?><span><a class="btn-buya" href="cart.php">VIEW CART</a></span><?php } ?></div>


             <div id="main"><form method='post' class='user-form'>
             <table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
        	 <tbody>

<?php if($preorder=="1"){ ?><tr><td colspan="4"><h5><?php echo $name; ?></h5></td></tr> 
<?php 


    echo'<tr class="ter mx-3" onclick=\'this.querySelector("input[type=checkbox]").click();\' >
	<td class="check">
    &nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
    <td class="check"><span>'.$name.'</span><br></td>
    <td class="check" style="font-size:16px">&#8358;'.$price.'.00</td>
    <td class="check" style="font-size:16px;">
    Quantity <input class="form-control" type="number" min="1" value="1" name="dishquantity" placeholder="1"  required/></td>
    </tr>';
}
?>        	     
        	     
        	     
        	     
 <tr><td colspan="4"><h5>PROTEINS SECTION</h5></td></tr> 
<?php 
  
$sql = "SELECT * FROM delta_protein WHERE FIND_IN_SET('$category', soup_category) > 0";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
 {
				 		$imageURL='../kitchen/'.$row["picture"];			
		

echo'<tr class="ter mx-3" onclick=\'this.querySelector("input[type=checkbox]").click();\' >
	<td class="check"><input type="checkbox" style="pointer-events:none;"  value="'.$row['id'].'" name="protein[]"  />
    &nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
    <td class="check"><span>'.$row['name'].'</span><br></td>
    <td class="check" style="font-size:16px">&#8358;'.$row['price'].'.00</td>
    <td class="check" style="font-size:16px;">
    Quantity <input class="form-control" type="number" min="1" value="1" name="proteinquantity['.$row['id'].']" placeholder="1" /></td>
    </tr>';



	}
	
	?>
	
	
		</tbody>
     	</table></div>
				
						
 <div>
<table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
<tbody>
<tr><td colspan="4"><h5>ADDITIONAL MEALS</h5> </td></tr>
<?php 

    $sql = "SELECT * from delta_meals where category='$meal_category' ";
    $sql2s = mysqli_query($con, $sql);
    while ($rows = mysqli_fetch_array($sql2s)) {
        $imageURL='../kitchen/'.$rows["picture"];

      echo '
        <tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click();\' >
        <td class="check"><input type="radio" style="pointer-events:none;" value="'.$rows['id'].'"  name="selected_meal[]" required />&nbsp;&nbsp;&nbsp;
        <img src="'.$imageURL .'" class="img"/></td>
        <td class="check"><span>' . $rows['name'] . '</span></td>
        <td class="check" style="font-size:16px;">&#8358;' . $rows['price'] . '.00</td>
        <td class="check" style="font-size:16px;">Quantity<input class="form-control"  value="1" type="number" min="1" name="mealquantity['.$rows['id'].']" placeholder="1" /></td>
        </tr>';
    }


	?>
	
	
		</tbody>
     	</table></div>

   
   
   
   
   
               <div class="btn-wrap" align="center" style="margin-bottom:40px;">
			   <input type="text" style="display:none;" value="<?php echo $category; ?>" name="soup" />
			   <button type="submit" name="submit" value="submit" class="submitn btn-buya">ADD TO CART</button><br />
			   </form></div>
   
    </div>
    </section><!-- End Pricing Section -->

<?php
if (isset($_POST['submit'])) {
$category = $_POST['soup'];

$dishno=1;

if (isset($_POST['dishquantity'])) {
$dishno = $_POST['dishquantity']; 
}


//soup 
$sql = "SELECT * from delta_soups where id='$category' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$name=$row["name"];
$describe=$row["describe"];
$price=$row["price"];
$imageURL='../kitchen/'.$row["picture"]; 
}


$dishprice=$dishno*$price;
$bot = "SELECT * from delta_cart where id='$saloon' AND itemno='$category'";
$bot2 = mysqli_query($con,$bot);
if (mysqli_affected_rows($con) == 0){
$submit = mysqli_query($con,"insert into delta_cart(`id`, `itemno`, `itemname`, `unitprice`, `quantity`, `totalprice`, `status`) values 
('$saloon','$category','$name','$price','1','$dishprice','processing')") or die ('Could not connect: ' .mysqli_error($con));
}

else{
$sqk = "SELECT * from delta_cart where id='$saloon' AND itemno='$category'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp)){
$s_quantity = $rowe['quantity'];
$s_rowfood = $rowe['s'];
}

$soupnewquantity="";      
$s_totalvalue=""; 

$soupnewquantity=$s_quantity+$dishno;
$s_totalvalue=$soupnewquantity*$price;
    
$insert = mysqli_query($con,"UPDATE delta_cart SET totalprice= '$s_totalvalue' where s='$s_rowfood'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE delta_cart SET unitprice= '$price' where s='$s_rowfood'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE delta_cart SET quantity= '$soupnewquantity' where s='$s_rowfood'") or die ('Could not connect: ' .mysqli_error($con));     
}







//protein handle
if (isset($_POST['protein'])) {
    $selectedProtein = $_POST['protein'];
    
    
        for ($i = 0; $i < count($selectedProtein); $i++) {
        $proteinID = $selectedProtein[$i];
        $proteinValue = $_POST['proteinquantity'][$proteinID];

    // Fetch information about the selected protein from your database.
    $sqk = "SELECT * FROM delta_protein WHERE id = '$proteinID'";
    $sqlp = mysqli_query($con, $sqk);

    if ($sqlp) {
        while ($rowe = mysqli_fetch_array($sqlp)) {
            $proteinname = $rowe['name'];
            $proteinprice = $rowe['price'];
        }

        $bot = "SELECT * FROM delta_cart WHERE id='$saloon' AND itemno='$proteinID'";
        $bot2 = mysqli_query($con, $bot);

        if (mysqli_affected_rows($con) == 0) {
            $proteintotal = $proteinValue * $proteinprice;

            $submit = mysqli_query($con, "INSERT INTO delta_cart(`id`, `itemno`, `itemname`, `unitprice`, `quantity`, `totalprice`, `status`) VALUES ('$saloon','$proteinID','$proteinname','$proteinprice','$proteinValue','$proteintotal','processing')") or die ('Could not connect: ' . mysqli_error($con));
        } else {
            $newquantity = "";
            $totalvalue = "";

            $sqk = "SELECT * FROM delta_cart WHERE id='$saloon' AND itemno='$proteinID'";
            $sqlp = mysqli_query($con, $sqk);
            
            while ($rowe = mysqli_fetch_array($sqlp)) {
                $quantity = $rowe['quantity'];
                $rowfood = $rowe['s'];
            }

            $newquantity = $quantity + $proteinValue;
            $totalvalue = $newquantity * $proteinprice;

            $insert = mysqli_query($con, "UPDATE delta_cart SET totalprice= '$totalvalue' WHERE s='$rowfood'") or die ('Could not connect: ' . mysqli_error($con));
            $insert = mysqli_query($con, "UPDATE delta_cart SET unitprice= '$proteinprice' WHERE s='$rowfood'") or die ('Could not connect: ' . mysqli_error($con));
            $insert = mysqli_query($con, "UPDATE delta_cart SET quantity= '$newquantity' WHERE s='$rowfood'") or die ('Could not connect: ' . mysqli_error($con));
        }
    }
}}








if (isset($_POST['selected_meal'])) {
$index = $_POST['selected_meal'][0]; 
$mealQuantity = $_POST['mealquantity'][$index];

$sqk = "SELECT * from delta_meals where id='$index'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
{
$mealname = $rowe['name'];
$mealprice = $rowe['price'];
}
  
$mealtotal=$mealQuantity*$mealprice;

$bot = "SELECT * from delta_cart where id='$saloon' AND itemno='$index'";
$bot2 = mysqli_query($con,$bot);
if (mysqli_affected_rows($con) == 0){
$submit = mysqli_query($con,"insert into delta_cart(`id`,`itemno`, `itemname`, `unitprice`, `quantity`, `totalprice`, `status`) values 
('$saloon','$index','$mealname','$mealprice','$mealQuantity','$mealtotal','processing')") or die ('Could not connect: ' .mysqli_error($con));
}

else{
    
 $newquantity="";      
 $totalvalue=""; 
  
$sqk = "SELECT * from delta_cart where id='$saloon' AND itemno='$index'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp)){
$quantity = $rowe['quantity'];
$rowfood = $rowe['s'];
}


$newquantity=$quantity+$mealQuantity;
$totalvalue=$newquantity*$mealprice;
    
$insert = mysqli_query($con,"UPDATE delta_cart SET totalprice= '$totalvalue' where s='$rowfood'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE delta_cart SET unitprice= '$mealprice'   where s='$rowfood'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE delta_cart SET quantity= '$newquantity' where s='$rowfood'") or die ('Could not connect: ' .mysqli_error($con));         
    
}}







































//go to cart
header("location:cart.php");
}

include"footer.php";  ?>