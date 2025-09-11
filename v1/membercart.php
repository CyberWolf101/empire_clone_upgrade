<?php include "header.php";
$ran=$_SESSION['idea'];
$_SESSION['idea']=$ran;
?>
<main>
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
max-width:30%;
max-height:30%;
border-radius:50%;
}
.submitn{
  
  background: #FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px;
  font-size: 14px;
  font-weight: 600;
  outline:none;
  border:none;

 
 
}

.submitn:hover{
  background: #000000;
  color: #fff;
  outline:none;
  border:none;
}

.submita{
  
  background: #FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px;
  font-size: 14px;
  font-weight: 600;
  outline:none;
  border:none;
  float:right;
 
 
}

.submita:hover{
  background: #000000;
  color: #fff;
  outline:none;
  border:none;
}

 .btn-buya {
  display: inline-block;
  padding:5px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 12px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#FFC700;
  margin-bottom:20px;
  float:right;

  
}
.btn-buya:hover {
  display: inline-block;
   padding:5px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 12px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#000000;
  margin-bottom:20px;

  
}


.koy{
color:#FFC700;    
}

.badge {
  padding-left: 9px;
  padding-right: 9px;
  -webkit-border-radius: 9px;
  -moz-border-radius: 9px;
  border-radius: 9px;
}

.label-warning[href],
.badge-warning[href] {
  background-color: #c67605;
}
#lblCartCount {
    font-size: 14px;
    background: #ff0000;
    color: #fff;
    padding: 0 5px;
    vertical-align: top;
    margin-left: -10px; 
}
</style>
 <section id="pricing" class="pricing section-bg" style="margin-top:10px; background-color:none;  border:none;">
<h4 style="font-size:15px; background-color:#FFC700; ">ADD DESIRED SERVICES TO CART</h4>
<a href="memberpay.php"><button class="submita" name="submit" type="button">PROCEED TO PAYMENT</button></a>
</section>
<p style="text-align:center;"><i class="fa fa-shopping-cart fa_custom fa-3x koy"></i><span class='badge badge-warning' id='lblCartCount'> 

				<?php		$sql = "SELECT count(*) As 'total'
						FROM meme where  id='$ran'
						 ";
		 $sql2 = mysqli_query($con,$sql);
		 $dad = mysqli_fetch_assoc($sql2);
		 
 
           $kany=$dad['total'];
            echo $kany;
           ?>
           </span></p>
		<div class="row">
     	<div class="col-lg-12 col-md-12">
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		 <p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto;  border-spacing:0px 10px;">
			 <thead>
			 <th></th><th></th></thead>
	<tbody>
	<?php 
include "connect_to_mysqli.php";
   
  $sql = "SELECT all* from member where id='$ran'";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$cot = $row['type'];
				 	
				 		if($cot=="Monthly Membership")
{
    $nim=1;
}
else if($cot=="Quarterly Membership")
{
    $nim=3;
}
else if($cot=="Yearly Membership")
{
    $nim=12;
}
				  }
				  
		  
				  
				  
				  
				  
				  
				  
$sql = "SELECT all* from see ORDER By s ASC LIMIT 100";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		
						$po=$row['price'];
						
					$per=$nim*$po;	
					

echo'<form action="" method="post">
<tr class="ter mx-1" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><span>'.$row['name'].'</span><br>&#8358;'.$row['price'].'.00</td>
	<td class="check" style="font-size:16px; ">
	<input type="text" value="'.$ran.'" class="form-control" name="idea" hidden />
	<input type="text" value="'.$row['name'].'" class="form-control" name="nade" hidden />
	<input type="text" value="'.$per.'" class="form-control" name="pade" />
	<input type="submit" name="submin" class="btn-buya" value="Add To Cart"/>  
	</td></tr></form>';
	}
	
	
	?>
	
	

	</tbody>
	</table></p></div></div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Added to Cart!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:#FFFFFF;"></button>
      </div>
      <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
        <p style="font-weight:600; font-size:13px;">Service successfully added to cart.<br>Press ok to add more or Proceed to payment directly</p>
		<p><button class="submitn"  data-bs-dismiss="modal">Ok</button></p>
         <p style="color:black;" style="margin-bottom:30px;"> <form action="memberpay.php" method="post" >
         <input type="text" name="sub" value="<?php echo $ran;?>" hidden /><input type="submit" name="submit" value="Proceed to Payment" class="submitn"> </form></p>
          
      </div>
    </div>
  </div>
</div></div>	
		   
  
<?php 
 if (isset($_POST['submin']))
	 {
	      $nam =  $_POST['nade']; 
	      $per = $_POST['pade'];

 $submit = mysqli_query($con,"insert into meme(id,name,price) values ('$ran','$nam','$per')") or die ('Could not connect: ' .mysqli_error($con));
  
			echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#myModal").modal("show");
			});
	     	</script>';

					}
					 
			 include "footer.php";		 
					 ?>

