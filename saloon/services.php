<?php include "header.php";

if (isset($_GET['category'])){
$category=$_GET['category'];
}
else{
header("Location: " . $_SERVER['HTTP_REFERER']);
}
  
$sql = "SELECT * from sub_category where id='$category' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$name=$row["name"];
$describe=$row["describe"];
$main=$row["main_category"];
$imageURL='../subcategory/'.$row["file_name"]; 
}


if($type=="0"){echo header("location:booking.php?category=$category");} 
?>

 
<script>
function check()
{
document.getElementById('results').scrollIntoView();
}
</script>
  <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
    <div class="container" style="width:100%; margin:auto; ">
    <div class="section-title" style="color:#FFFFFF;">
    <h4 style="text-transform:uppercase;">SERVICES- <?php echo $name; ?></h4>
     </div>

    <div class="row">
    <div class="col-lg-12 col-md-12">
    <div class="box" data-aos="zoom-in" data-aos-delay="100">
    <p><form action="" method="post">
    <table id="result" width="95%" border="0"  cellspacing='0' style="border-collapse:separate;  font-size:13px; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
	<thead><th colspan="3">Choose a Service</th></thead>
	<tbody>

<?php
$sql = "SELECT * from services where sub_category='$category' ORDER By name";
$sql2 = mysqli_query($con, $sql);
$numRows = mysqli_num_rows($sql2);

if ($numRows > 0) {
    while ($row = mysqli_fetch_array($sql2)) {
        $imageURL = '../services/' . $row["file_name"];
        $originalPrice= $row['price'];
        $newPrice = $originalPrice + ($originalPrice * ($walkinIncrease / 100));

        echo '
        <tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click(); check();\' >
        <td class="check"><input type="radio" style="pointer-events:none;" value="' . $row['id'] . '"  name="service" required /></td>
        <td class="check"><span>' . $row['name'] . '</span>
        <br>' . $row['duration'] . 'mins</td>
        <td class="check" style="font-size:16px;">&#8358;' . $row['price'] . '.00<br>
        <strike style="color:gray;">&#8358;' . $newPrice. '.00</strike></td></tr>';
    }
} else {
    echo '<tr><td colspan="4" >No service registered for this section. <a href="subcategory.php?category='.$main.'">Go back to main</a></td></tr>';
}
?>

	</tbody>
	</table>
	
	
	
	
	
	
	
	
<p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; font-size:13px; outline:none; margin:auto; border-spacing:0px 10px;">
<thead><th>CHOOSE DATE</th><th></th></thead>
<tbody>
<tr class="ter mx-3" >
<td class="check">Date</td>
<td class="check" colspan="2"><i>click to choose date</i><span>
<input type="date" name="date"  min="<?php echo date('Y-m-d', strtotime((date('H') >= 18 ? '+1 day' : 'now'))); ?>" class="form-control" title="Please enter Alphabets." required/></span></td>
</tr>
</tbody>
</table></p>
	
	
<p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; font-size:13px; outline:none; margin:auto; border-spacing:0px 10px;">
<thead><th width="200px">CHOOSE STAFF</th><th></th></thead><tbody>
<?php 
$sql = "SELECT * from staff where section='$main' ORDER By name";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2)){

$imageURL='../staff/'.$row["file_name"];
$data=$row['time'];
$status=$row['status'];
						
if($status == "busy"){ $available ='Busy'; }
else { $available ='Available'; }
					

echo' 
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['id'].'" name="staff" required  />
<input type="hidden"  value="'.$row['name'].'" name="staffname" required  />
&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
<td class="check"><span>'.$row['name'].'<br>'.$row['gen'].'</span></td>
<td class="check">'.$available.'</td></tr>'; }
?>

    <tr class="ter mx-3" onclick='this.querySelector("input[type=radio]").click()' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="random" name="staff" required  />&nbsp;&nbsp;&nbsp;<img src="149071.png" class="img"/></td>
	<td class="check"><span>Random Staff</span></td>
	<td class="check"></td></tr>
	</tbody>
	</table></p>
	
    <?php if($kit==0){ ?>
	<div style="width:95%; margin:auto; background:white; padding:10px; font-size:13px; font-weight:500;">
	<p><label><input type="checkbox" name="spakit" value="yes" /> Click here  to purchase a personal spa kit</label></p>
	</div><?php } ?>
	

			
			   <div class="btn-wrap">
               <div class="btn-wrap" align="center">
               <a href="subcategory.php?category=<?php echo $main;?>" class="submitn">BACK TO MAIN</a>
			   <button type="submit" name="submit" value="submit" class="submitn">PROCEED</button><br />
			   </form></div></div>
               </div>
               </div>

  
	


	

       
        </div>
        </section><!-- End Pricing Section -->


<?php 

if(isset($_POST['submit'])){
$service=$_POST['service'];
$date=$_POST['date'];
$staff=$_POST['staff'];
$staffname=$_POST['staffname'];
$spakit=$_POST['spakit'];

//service details
$sqk = "SELECT * from services where id='$service'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
{
$servicename = $rowe['name'];
$price = $rowe['price'];
$duration = $rowe['duration'];
}


//random staff				
if ($staff=="random"){
$sqk = "SELECT * from staff where section='$category' && status='available'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
{ $staff= $rowe['name'];}}


//spa kit
if($spakit=="yes"){$insert = mysqli_query($con,"UPDATE saloon_orders SET saloonkit='$kitprice' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con));}

$end="0";
$start="0";

$submit = mysqli_query($con,"insert into appointments(id, service,servicename, price,duration ,start_time, end_time, date,staff,staffname,lateservice,latefee,status) values 
('$saloon','$service','$servicename','$price','$duration','$end','$start','$date','$staff','$staffname','0','0','processing')") or die ('Could not connect: ' .mysqli_error($con));

header("location:selecttime.php");
}

include"footer.php";  ?>