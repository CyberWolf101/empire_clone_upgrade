<?php include"header.php";  session_start();  ?>
<main id="main">
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


</style>
<?php
$orid= $_SESSION['hord'];
$ran=$_SESSION['iron'];

 include "connect_to_mysqli.php";
        $bot = "SELECT all* from trep where id ='$ran' ";
		$bot2 = mysqli_query($con,$bot);
		if (mysqli_affected_rows($con) == 0)
			  {

 $_SESSION['ider']=$ran;
 $_SESSION['hord']=$orid;
echo header("location:book.php");		
} 			

else
{
    echo "";
}
  
$sql = "SELECT all* from sub where id='$orid' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$nam=$row["name"];
					    $gene=$row["gen"];
						}




 ?>
 
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto; ">
        <div class="section-title" >
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
		 $count = mysqli_num_rows($sql2);
         if ($count <= 0) {
             echo '
                 <tr class="ter mx-3">
             	<td class="check" style="font-size:10px;" width="200px">
                No service available to choose from</td><td></td><td></td></tr>
                
                
              <style>
              .disable{
               display:none;   
              }
              </style>
                
                
                
                
                
                
                
                ';
                
         }
	else{
	    

		while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../baby/'.$row["file_name"];
						$ide = $row['gen'];
						$disable="";

echo'
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click(); check();\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['id'].'"  name="baby"required /></td><td class="check"><span>'.$row['name'].'</span><br>'.$row['time'].'mins</td>
	<td class="check" style="font-size:16px;">
	&#8358;'.$row['price'].'.00</td></tr>';
	}
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
	<td class="check" colspan="2"><i>click to choose date</i><span><input type="date" name="dear" min="<?php echo date("Y-m-d"); ?>"  class="form-control" required/></span></td>
</tr>
	
	
	</tbody>
	</table></p>
	
	
	 <p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; font-size:13px; outline:none; margin:auto; border-spacing:0px 10px;">
	 <thead>
	<th width="200px">CHOOSE TIME</th><th></th></thead>
	<tbody>

    <tr class="ter mx-3">
	<td class="check"><span>Chooose Time</span></td>
	<td class="check"><input type="Time" name="gene" class="form-control" required /></td>
	<td class="check"></td></tr>'	
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
			   <button type="submit" name="submit" value="next" class="submitn disable"  >NEXT</button>
			   <br><br>
			   <button type="submit" name="submit" value="addc" class="submitn">ADD MORE SERVICES</button>
			   </form></div></div>
            </div>
          </div>

  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->
  <?php include"footer.php" ?>