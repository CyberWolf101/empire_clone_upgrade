<?php include "header.php";


if (isset($_GET['category'])){
$category=$_GET['category'];
}
else{
header("Location: " . $_SERVER['HTTP_REFERER']);
}
  
$sql = "SELECT * from training where id='$category' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$name=$row["name"];
$describe=$row["description"];
}


?>

 
<script>
function check()
{
document.getElementById('results').scrollIntoView();
}
</script>
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
font-weight:500;

}
.img{
width:30%;
height:auto;
border-radius:50%;
}
.submitn{
  background: #FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px;
  font-size: 10px;
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

		  .btn-buya {
  display: inline-block;
  padding:10px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#FFC700;
  margin-bottom:20px;
  width:300px;
  
}
	  .btn-buya:hover {
  display: inline-block;
  padding:10px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#000000;
  margin-bottom:20px;
  width:300px;
  
}

.advert{
background:#FFC700;
width:100%;
height:40px;
font-weight: 800;
font-size:14px;
color:#fff;
padding:10px;
}
#clocs
{
display:none;}

#cloch{
display:none;
}
</style>

    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
          <div class="container" style="width:100%; margin:auto; ">
          <div class="section-title" style="color:#FFFFFF;">
          <?php if ($count_services > 0) {?><p style="float:right;"><a class="btn-buya" href="cart.php">VIEW CART</a></p><?php  } ?>
          <h2><?php echo $name; ?></h2>
          </div>

            <div class="row">
             <div class="col-lg-12 col-md-12">
		     <p style="color:#FEBF01;">Choose a duration</p>
             <div class="box" data-aos="zoom-in" data-aos-delay="100">
			 </div></div>
			
               <div class="col-lg-12 col-md-12">
              <div class="box" data-aos="zoom-in" data-aos-delay="100">
                  


             <div id="main"><form method='post' class='user-form'>
             <table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px; padding:10px 0px;">
        	 <tbody>
    
<?php 
  
$sql = "SELECT * FROM durations where category='$category'";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
 {
				 					
		
echo'<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click();\' >
	<td class="check"><input type="radio" style="pointer-events:none;"  value="'.$row['s'].'" name="item"  required/></td>
	<td class="check"><span>&nbsp; &nbsp; &nbsp; &nbsp;  '.$row['duration'].'</span></td>
    <td class="check" style="font-size:16px">&#8358;'.$row['price'].'.00</td>
    </tr>';



	}
	
	?>
	
	
		</tbody>
     	</table></div>
				

   
               <div class="btn-wrap" align="center" style="margin-bottom:40px;">
			   <button type="submit" name="submit" value="add" class="btn-buya">NEXT</button><br />
			   </form></div>
   
    </div>
    </section><!-- End Pricing Section -->

<?php
if (isset($_POST['submit'])) {
$itemID=$_POST['item'];
    

    $sqk = "SELECT * FROM durations WHERE s= '$itemID'";
    $sqlp = mysqli_query($con, $sqk);
    if ($sqlp) {
    while ($rowe = mysqli_fetch_array($sqlp)) {
            $trainingname= $rowe['duration'];
            $itemprice = $rowe['price'];
        }}

    $submit = mysqli_query($con, "INSERT INTO academy_cart(`id`, `training`, `trainingname`, `duration`, `durationname`, `price`) 
    VALUES ('$saloon','$category','$name','$itemID','$trainingname','$itemprice')") or die ('Could not connect: ' . mysqli_error($con));
        

header("location:cart.php");
}

include"footer.php";  ?>