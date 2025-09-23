<?php
include "../connect.php"; 

// Get the search query from the AJAX request
if (isset($_POST['query'])) {
    $query = $_POST['query'];

$sql = "SELECT * from food_menu WHERE item LIKE '%".$query."%' LIMIT 1000";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
			   $imageURL='../orishirishi/'.$row["file_name"];
			   $quantity = $row['quantity'];		
				
				if ($quantity > 0)
				{
				$show = 'Quantity<input class="form-control" type="number"  max="'.$row['quantity'].'" min="1" name="value" value="1" /><br>
                       <button type="submit" name="addtocart" class="btn-buya" >Add To Cart</button>';    
				}
				else
				{
				$show='<p style="color: #FFC700;">Out Of Stock.</p>';
				}

echo'<form action="" method="post">
    <tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check">
	<input type="radio" style="pointer-events:none;" value="'.$row['s'].'" name="food" hidden />
	<span>'.$row['item'].'</span><br>&#8358;'.$row['price'].'.00<br>
	<span class="text-warning">Available stocks: '.$row['quantity'].' pieces</span></td>
	<td class="check" style="font-size:14px;">'.$show.'
	</td></tr></form>';
	}
}
?>
