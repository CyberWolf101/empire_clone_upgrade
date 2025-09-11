<?php include "connect_to_mysqli.php"; 

if(empty($_POST['category'])){
$sql = "SELECT all* from delta_kitchen where status='available' ORDER By name ASC";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
$imageURL='food/'.$row["picture"];
echo'<form action="" method="post">
    <tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['s'].'" name="meal" hidden />&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['name'].'</span><br> &#8358;'.$row['price'].'.00</td>
	<td class="check" style="font-size:16px;">Quantity<input class="form-control" type="number" min="1" name="quantity" value="1" /><br>
	<input type="text" value="'.$id.'" class="form-control" name="id" hidden />
    <button type="submit" name="submitt" class="btn-buya" >Add To Cart</button>
	</td>
	</tr></form>'; 
}
}






else{
$cat=$_POST['category'];
$sql = "SELECT all* from delta_kitchen where status='available'&& category='$cat' ORDER By name ASC";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
$imageURL='food/'.$row["picture"];
echo'<form action="" method="post">
    <tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['s'].'" name="meal" hidden />&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['name'].'</span><br> &#8358;'.$row['price'].'.00</td>
	<td class="check" style="font-size:16px;">Quantity
	<input class="form-control" type="number" min="1" name="quantity" value="1" /><br>
	<input type="text" value="'.$id.'" class="form-control" name="id" hidden />
    <button type="submit" name="submitt" class="btn-buya" >Add To Cart</button>
	</td>
	</tr></form>'; 
} 

}











?>