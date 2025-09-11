<?php session_start();
$re=$_SESSION['shan'];
include "connect_to_mysqli.php";

//get the q parameter from URL
$q=$_POST["q"];

	$sql = "SELECT all* from foods WHERE name LIKE '%".$q."%' ORDER By name DESC LIMIT 1000";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		
	
    $data=date("Y-m-d");			
					
     $response='<form method="post" action="other.php" id="kayd">
	<tr><td width="200px" ><input type="text" value="'.$row['name'].'" name="name" readonly style="border:0; outline:0;" /></td>
	<td width="200px" >'.$row['phone'].'</td>
	<input type="text" class="form-control" name="ran" value="'.$re.'" hidden  required />
	<input type="date" name="dear" min="'.$data.'" value="'.$data.'"   required hidden/ >
    <td><input type="submit" value="SELECT" name="submit" class="btn-buya " /></td></tr>
    </form>
     ';

//output the response
echo $response;
}
?>




