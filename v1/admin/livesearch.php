<?php
session_start();
$orid=$_SESSION['ider'];
include "connect_to_mysqli.php";

//get the q parameter from URL
$q=$_GET["q"];

	$sql = "SELECT all* from food WHERE name LIKE '%".$q."%' ORDER By name DESC LIMIT 1000";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 $imageURL='../food/'.$row["file_name"];		
	        $ide = $row['cart'];
			$no = $row['nom'];
						
				$data=date("Y-m-d");			
				
				if ($no > 1)
				{
				$show = 'Quantity<input class="form-control" type="number"  max="'.$row['nom'].'" min="1" name="va" value="1" /><br>
	<input type="text" value="'.$orid.'" class="form-control" name="idea" hidden />
	<input type="date" name="dear" min="'.$data.'" value="'.$data.'" class="form-control" required hidden/>
    <button type="submit" name="submin" class="btn-buya" >Add To Cart</button>';    
				}
				else
				{
				    
			$show='<p style="color: #FFC700;">Out Of Stock</p>';
				}

echo'<form action="" method="post">
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['id'].'" name="food" hidden />&nbsp;&nbsp;&nbsp;
	<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['name'].'</span><br>&#8358;'.$row['price'].'.00</td>
	<td class="check" style="font-size:16px;">'.$show.'
	</td></tr></form>';
	

//output the response
echo $response;
}
?>


