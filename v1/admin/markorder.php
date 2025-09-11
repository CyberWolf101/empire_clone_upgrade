<?php
if(isset( $_POST['submark'])){ 
$order= $_POST['orderid'];

//Update
$insert = mysqli_query($con,"UPDATE delta_cart SET status='Collected' where s='$order'") or die ('Could not connect: ' .mysqli_error($con)); 

echo "<script>
 alert('Order Successfully Marked as Collected!');
</script>";




}
    ?>