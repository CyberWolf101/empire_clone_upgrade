<?php include"header.php";?>
<!---...This is for Microlashing section - single.. --->
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


</style>
 <?php
  session_start();
 $ran=$_SESSION['ider'];
 $gan=$_SESSION['more'];
 $orid=$_POST['cate'];
if ($orid == "")
{
$orid=$gan;
}
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from cater where id='$orid' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$nam=$row["name"];
						}


 ?>
 
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto; ">
        <div class="section-title" style="color:#FFFFFF;">
          <h5 style="text-transform:uppercase;">SUB CATEGORIES - <?php echo $nam; ?></h5>
        </div>

        <div class="row">
      <div class="col-lg-12 col-md-12">
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
              <h3>CHOOSE SUBCATEGORY</h3>
             <p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate;  border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th></th><th></th><th></th></thead>
	<tbody>
	<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from sub where gen='$orid' ORDER By name DESC LIMIT 100";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../sub/'.$row["file_name"];
						$ide = $row['gen'];
						
						
					

echo'
<tr class="ter mx-3">
	<td class="check">&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"style="width:30%;" ><span>'.$row['name'].'</span><br></td>
	<td class="check"><form action="detail.php" method="post"><input type="hidden" value="'.$row['id'].'" name="cate" />
	<input type="hidden" value="'.$ran.'" name="ran" />
	<input type="hidden" value="'.$orid.'" name="orig" /><button type="submit" name="submit" value="submit" class="submitn" style="float:right;">READ MORE</button></form></td></tr>'
	;
	}
	
	?>
	

	</tbody>
	</table>
	
              
			  <div class="btn-wrap">
               <a href="../index.php" class="submitn">HOME</a></div>
            </div>
          </div>

  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->

 <?php include"footer.php";  ?>