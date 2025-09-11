<?php
include "connect_to_mysqli.php";

         $ran=$_POST['idea'];




  	     $sql = "SELECT count(*) As 'total' FROM delta_cart where order_id='$ran' && order_id!=''";
		 $sql2 = mysqli_query($con,$sql);
		 $dad = mysqli_fetch_assoc($sql2);
		 $kany=$dad['total'];
         echo $kany;











?>

