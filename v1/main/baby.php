<?php include"header.php";?>
<!---...This is for Microlashing section - single.. --->
<style>


.ter{
background-color:#fff;
padding:0 10px;
}
.check{
padding:4%;
font-size:12px;
width:33%;
}
.check span{

font-size:12px;
font-weight:500;

}
.img{
max-width:33%;
max-height:33%;
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

.btn-buya {
  display: inline-block;
  padding:6px;
  border:none;
  color: #fff;
  font-size: 12px;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
  font-weight: 800;
  transition: 0.3s;
  background:#FEBF01;
  float:right;
  
}

</style>
 <?php
$orid= $_SESSION['hord'];
$ran=$_SESSION['iron'];
$gift=$_SESSION['gift'];

 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from sub where id='$orid' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
				  {
				 		$nam=$row["name"];
					    $gene=$row["gen"];
						}

$bot = "SELECT all* from trep where id ='$ran' ";
$bot2 = mysqli_query($con,$bot);
if (mysqli_affected_rows($con) == 0)
{
 session_start(); 
 $_SESSION['ider']=$ran;
 $_SESSION['hord']=$orid;
 $_SESSION['gift']=$gift;
 
echo header("location:book.php");		
} 			

else
{
    echo "";
}



 ?>
 
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto; ">
        <div class="section-title" style="color:#FFFFFF;">
          <h4 style="text-transform:uppercase;">SERVICES- <?php echo $nam; ?></h4>
        </div>

        <div class="row">
      <div class="col-lg-12 col-md-12">
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
         <p><form action="dete.php" method="post"><table id="result" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; 
         font-size:13px; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th>Choose a Service</th><th></th><th></th></thead>
	<tbody>
	    <script>
function check()
{
document.getElementById('results').scrollIntoView();
}
   
    </script>
	<?php 
  
$sql = "SELECT all* from baby where gen='$orid' ORDER By name DESC LIMIT 100";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
				 		$imageURL='../baby/'.$row["file_name"];
						$ide = $row['gen'];
						
						
					

echo'
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click(); check();\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['id'].'"  name="baby"required /></td><td class="check"><span>'.$row['name'].'</span><br>'.$row['time'].'mins</td>
	<td class="check" style="font-size:16px;">
	&#8358;'.$row['price'].'.00</td></tr>';
	}
	
	
	?>
	
	

	</tbody>
	</table>
	
	
	
	
	
	
	
	
	         <p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; font-size:13px; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th>CHOOSE DATE</th><th></th></thead>
	<tbody>
<tr class="ter mx-3" >
<td class="check">Date</td>
<td class="check" colspan="2"><i>click to choose date</i><span><input type="hidden" name="gift" value="<?php echo $gift; ?>"><input type="date" name="dear" min="<?php echo date("Y-m-d"); ?>"  class="form-control" required/></span></td>
</tr>
</tbody>
</table></p>
	
	
	         <p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; font-size:13px; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th width="200px">CHOOSE STAFF</th><th></th></thead>
	         <tbody>
<?php  $sql = "SELECT all* from staff where id='$gene' ORDER By name DESC LIMIT 5";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../staff/'.$row["file_name"];
						$data=$row['time'];
						$star=$row['status'];
						
						
						if ($star == "busy")
		 {
		   	$ava='Will be available  at '.$data.'';
		}
		 else
		 {
		 $ava='Available';
		 }
					

echo' 
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['name'].'" name="staff" required  />&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['name'].'<br>'.$row['gen'].'</span></td>
	<td class="check">'.$ava.'</td></tr>'
	;
	}
	
	?>
    <tr class="ter mx-3" onclick='this.querySelector("input[type=radio]").click()' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="random" name="staff" required  />&nbsp;&nbsp;&nbsp;<img src="149071.png" class="img"/></td>
	<td class="check"><span>Random Staff</span></td>
	<td class="check"></td></tr>'	
    <input type="hidden" value="<?php echo $gene;?>" name="gene" />
	</tbody>
	</table></p>
	
        <?php
		
	
	if ($gene=="002")
		{
		echo '
			<div style="width:95%; margin:auto; background:white; padding:10px; font-size:15px; font-weight:500;">
			<p><b>Would you like to purchase a personal spa kit?</b></p>
	 <p>
	<label><input type="radio" size="30" name="rad" value="Yes" required />Yes</label><br>
	<label><input type="radio" size="30" name="rad" value="No" required />No</label>
    </p>
	</div>';
	}
	
	else
	{ echo"";
	}
	
	?>      
			
			
			
			
			
			   <div class="btn-wrap">
               <div class="btn-wrap" align="center">
			   <input type="hidden" value="<?php echo $ran; ?>" name="idea" />
			   <button type="submit" name="submit" value="submit" class="submitn">NEXT</button><br />
			   </form></div></div>
            </div>
          </div>

  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->

 <?php include"footer.php";  ?>