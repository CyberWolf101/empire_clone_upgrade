<?php

include "connect_to_mysqli.php";

    $pqty=$_POST['quantity'];
    $pid=$_POST['sad'];

    $insert = mysqli_query($con,"UPDATE delta_cart SET quantity='$pqty'  where s='$pid' ") or die ('Could not connect: ' .mysqli_error($con)); 
    


?>
