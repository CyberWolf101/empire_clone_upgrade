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
<p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0 5px;">
<tbody>
<?php 

if($preorder=="1"){
$sql = "SELECT * from delta_soups ORDER By preorder DESC";    
}else{
$sql = "SELECT * from delta_soups where preorder='0' ORDER By name";    
}

$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$imageURL='../kitchen/'.$row["picture"]; 
						
						
echo'
    <tr class="ter mx-3">
	<td class="check"><img src="'.$imageURL .'" class="img"/></td>
	<td class="check" style="width:40%;" ><span>'.$row['name'].'</span><br><span style="font-size:14px;">&#8358;' . $row['price'] . '.00</span></td>
	<td class="check" style="width:40%;" ><a href="details.php?category='.$row['id'].'" class="submitn" style="float:right;">ORDER NOW</a></td></tr>'
	;
	}
	
	?>
	

	</tbody>
	</table></p>
	
              
<div class="btn-wrap">
<a href="../index.php" class="submitn">HOME</a></div>
</div>
</div>

  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->







<?php include "footer.php"; ?>