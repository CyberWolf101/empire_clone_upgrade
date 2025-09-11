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
font-weight:700;

}
.img{
max-width:30%;
max-height:30%;
border-radius:50%;
}


</style>
 <?php
 $orid=$_POST['cate'];
 $ran=$_POST['ran'];
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
          <h2></h2>
        </div>

        <div class="row">
   
			
          <div class="col-lg-12">
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
             <p><form action="" method="post"><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th></th><th></th><th></th></thead>
	<tbody>

<tr><td></td><td style="font-weight:900;">CHOOSE STAFF</td><td></td></tr>
	<tr class="ter mx-3" onclick=\"this.querySelector('input[type=checkbox]').click()\" >
	<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from staff where id='$orid' ORDER By name DESC LIMIT 20";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../staff/'.$row["file_name"];
						$ide = $row['cart'];
						
						
					

echo'
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['name'].'" name="staff"  />&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['name'].'<br>'.$row['gen'].'</span></td>
	<td class="check"></td></tr>'
	;
	}
	
	?>
	

	</tbody>
	</table>
	</p>
	
              
			  <div class="btn-wrap">
               <input type="submit" name="submit" class="btn-buy" value="NEXT"/> </div>
            </div>
          </div>
</form>
  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->

 <?php include"footer.php";  ?>