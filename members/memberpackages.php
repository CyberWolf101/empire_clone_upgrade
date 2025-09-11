<?php include "header.php";

if (isset($_GET['card'])){
$card=$_GET['card'];
}
else{
header("Location: " . $_SERVER['HTTP_REFERER']);
}

$sql = "SELECT * from members where cardno='$card' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$type=$row["type"];
$name=$row["name"];
$email=$row["email"];
$phone=$row["phone"];
}

//total services booked
$extrac= mysqli_query($con,"SELECT * from members where cardno='$card'");
$count_members = mysqli_num_rows($extrac);


 if($type=="Monthly Membership")
{ $day=31;  $month=1;}
else if($type=="Quarterly Membership")
{ $day=31*4; $month=4;}
else if($type=="Yearly Membership")
{$day=31*12; $month=12;}

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
          <h2>MEMBERSHIP SERVICE PACKAGES</h2>
          </div>

            <div class="row">
             <div class="col-lg-12 col-md-12">
		     <p style="color:#FEBF01;">Each service price is the monthly price</p>
             <div class="box" data-aos="zoom-in" data-aos-delay="100">
			 </div></div>
			
               <div class="col-lg-12 col-md-12">
              <div class="box" data-aos="zoom-in" data-aos-delay="100">
                  


             <div id="main"><form method='post' class='user-form'>
             <table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
        	 <tbody>
 <tr><td colspan="2"><h5>SERVICES</h5></td></tr> 
<?php 
  
$sql = "SELECT * FROM packages ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
 {
				 					
		

echo'<tr class="ter mx-3" onclick=\'this.querySelector("input[type=checkbox]").click();\' >
	<td class="check"><input type="checkbox" style="pointer-events:none;"  value="'.$row['id'].'" name="service[]"  />
	<span>&nbsp; &nbsp; &nbsp; &nbsp;  '.$row['service'].'</span><br></td>
    <td class="check" style="font-size:16px">&#8358;'.$row['price'].'.00</td>
    </tr>';



	}
	
	?>
	
	
		</tbody>
     	</table></div>
				

   
               <div class="btn-wrap" align="center" style="margin-bottom:40px;">
			   <input type="text" style="display:none;" value="<?php echo $card; ?>" name="card" />
			   <button type="submit" name="submit" value="add" class="submitn btn-buya">ADD TO PACKAGES</button><br />
			   </form></div>
   
    </div>
    </section><!-- End Pricing Section -->

<?php
if (isset($_POST['submit'])) {
$card = $_POST['card'];
    
    

if (isset($_POST['service'])) {
$selectedservice = $_POST['service'];
    
    
for ($i = 0; $i < count($selectedservice); $i++) {
$service= $selectedservice[$i];
$sqk = "SELECT * from packages where id='$service'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
{
$servicename= $rowe['service'];
$serviceprice = $rowe['price'];
}
$totalprice=($serviceprice*$month)*$count_members;
$submit = mysqli_query($con, "INSERT INTO member_packages(`cardno`, `service`,`servicename`,`price`,`month`,`people`,`total`) VALUES ('$card','$service','$servicename','$serviceprice','$month','$count_members','$totalprice')") or die ('Could not connect: ' . mysqli_error($con));
}}    
header("location:membercart.php?card=$card");
}

include"footer.php";  ?>