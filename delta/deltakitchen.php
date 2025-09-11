<?php include "header.php"; ?>
 
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto; ">
        <div class="section-title" style="color:#FFFFFF;">
          <h5 style="text-transform:uppercase;">SECTIONS - DELTA KITCHEN</h5>
        </div>

        <div class="row">
      <div class="col-lg-12 col-md-12">
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
              <h3>CHOOSE MENU SECTION</h3>
             <p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate;  border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th></th><th></th><th></th></thead>
        	<tbody>
	<?php 

	
$sql = "SELECT all* from sub where gen='0015' && id!='0330' && id!='0340' ORDER By name ASC LIMIT 1000";
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
	<input type="hidden" value="'.$orid.'" name="orig" /><button type="submit" name="submit" value="submit" class="submitn" style="float:right;">ORDER</button></form></td></tr>'
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