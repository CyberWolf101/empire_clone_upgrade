<?php include "header.php"; ?>

    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
    <div class="container" style="width:100%; margin:auto; ">
    <div class="section-title" style="color:#FFFFFF;">
    <h4 style="text-transform:uppercase; font-size:16px; color:#FFC700;">E-GIFT SPACE PACKAGES</h4>


<p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0 5px;">
<tbody>
<?php 

$sql = "SELECT * from gift_packages  ORDER By name";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$imageURL='../giftpackage/'.$row["picture"]; 
						
						
echo'
    <tr class="ter mx-3">
	<td class="check"><img src="'.$imageURL .'" class="img"/></td>
	<td class="check" style="width:40%;" ><span>'.$row['name'].'</span><br><span style="font-size:14px;">&#8358;' . $row['price'] . '.00</span></td>
	<td class="check" style="width:40%;" ><a href="details.php?category='.$row['id'].'" class="submitn" style="float:right;">VIEW PACKAGE</a></td></tr>'
	;
	}
	
	?>
	

	</tbody>
	</table></p>

               <div class="row">
               <div class="col-lg-12 col-md-12">
			   <p><div class="btn-wrap" style="text-align:center;">
			   <a href="../index.php" class="submitn">BACK TO HOME</a></div>
               </div>
               </div></p>
               
  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->

 <?php include"footer.php";  ?>