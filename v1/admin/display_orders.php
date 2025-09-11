<?php
include "markorder.php";


$sql = "SELECT sum(price*quantity) as total,name,date,status,phone,order_id,app from delta_cart WHERE app='Confirmed' GROUP BY order_id ORDER BY date DESC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {


$ord=$row['order_id'];
$date=$row['date'];
$status=$row['status'];

 
 

if($status=="Collected"){
 $showus=$status;
}
else 
{
   $showus="<a href='#delete" . $row['s'] . "' data-toggle='modal'  ><button style='background-color:red; border:none;' class='btn btn-sm btn-primary shadow-sm' >Mark as Collected</button></a>
			
		
	<div class='modal fade' id='delete" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'Mark Order as Collected?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to mark this order as collected(" . $row['orderid'] . ")?</p>
	    <p><form action='' method='post' >
        <input type='text' name='orderid' value='" . $row['s'] . "' required hidden />  
        <button style='background-color:red;' value='del' class='btn btn-sm btn-primary shadow-sm' type='submit' name='submark'>Yes</button></form>	
        </p>
        <p><button class='btn btn-sm btn-primary shadow-sm' data-dismiss='modal'>No</button></p>
		 
          </div>
      </div>
    </div>
  </div>
 </div> 
</div>

             
            </div>"; 
    
}


echo "
   <tr bgcolor='#fff'>
   <td width='200px' >" . $ord . "</td>
   <td width='200px' >" . $row['name'] . "</td>
   <td width='200px'>&#8358; " . $row['total'] ."</td>
   <td width='200px'>" . $row['date'] ."</td>
   <td width='200px'>" . $row['status']."</td>
   <td>$showus</td> 
   <td><form action='delta_reciept.php' method='post'>
   <input type='text' name='ordid' value='" . $row['order_id'] . "' required hidden>  
	<input type='submit' name='submin' value='Print Receipt' class='submitn' ></form></td>
			</tr>


";




















}


?>

