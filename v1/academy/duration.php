<?php include"header.php";
$orid= $_SESSION['cate'];
$ran=$_SESSION['idea'];

$sql = "SELECT all* from sub where id='$orid' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
				  {
				 		$nam=$row["name"];
					    $gene=$row["gen"];
						}
?>

    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto; ">
        <div class="section-title" style="color:#FFFFFF;">
          <h4 style="text-transform:uppercase;">TRANING - <?php echo $nam; ?></h4>
        </div>

             <div class="row">
             <div class="col-lg-12 col-md-12">
             <div class="box" data-aos="zoom-in" data-aos-delay="100">
         <p><form action="check_out.php" method="post"><table id="result" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; 
         font-size:13px; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th>Choose a Duration</th><th></th><th></th></thead>
	<tbody>

	<?php 
  
$sql = "SELECT all* from baby where gen='$orid' ORDER By name DESC LIMIT 100";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../baby/'.$row["file_name"];
						$ide = $row['gen'];
						
						
					

echo'
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click(); check();\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['id'].'"  name="duration" required /></td><td class="check"><span>'.$row['name'].'</span></td>
	<td class="check" style="font-size:16px;">
	&#8358;'.$row['price'].'.00</td></tr>';
	}
	
	
	?>
	
	

	</tbody>
	</table>
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