<?php include "header.php";

if (isset($_GET['category'])){
$category=$_GET['category'];
}
else{
header("location: ../index.php");
}
  
$sql = "SELECT * from category where id='$category' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$name=$row["name"];}

?>




 <!-- ======= Pricing Section ======= -->
<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
<div class="container" style="width:100%; margin:auto; ">
<div class="section-title" style="color:#FFFFFF;">
	<?php include '../becomeMember.php'?>
<h5 style="text-transform:uppercase;">SUBCATEGORIES - <?php echo $name; ?></h5>
</div>

<div class="row">
<div class="col-lg-12 col-md-12">
<div class="box" data-aos="zoom-in" data-aos-delay="100">
<p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0 5px;">
<tbody>
<?php 

$sql = "SELECT * from sub_category where main_category='$category' ORDER By name";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$imageURL='../subcategory/'.$row["file_name"]; 
						
						
echo'
    <tr class="ter mx-3">
	<td class="check"><img src="'.$imageURL .'" class="img"/></td>
	<td class="check" style="width:40%;" ><span>'.$row['name'].'</span><br></td>
	<td class="check" style="width:40%;" ><a href="subcategory_detail.php?category='.$row['id'].'" class="submitn" style="float:right;">READ MORE</a></td></tr>'
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







<?php include "footer.php"; ?>