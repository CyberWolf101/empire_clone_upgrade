<?php include "header.php";

$saloon=substr(md5(mt_rand()), 0, 6);
$date=date("Y-m-d");
$submit = mysqli_query($con,"insert into rentals(orderid,name,email,phone,days,dates,total_amount,status,paystatus,date)
values ('$saloon','','','','','','','','','$date')") or die ('Could not connect: ' .mysqli_error($con));
setcookie("rentalID", $saloon, time() + (10 * 365 * 24 * 60 * 60));

$sql = "SELECT * from delta_soups where id='$category' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$name=$row["name"];
$describe=$row["description"];
$imageURL='../subcategory/'.$row["picture"]; 
}


 ?>

    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
    <div class="container" style="width:100%; margin:auto; ">
    <div class="section-title" style="color:#FFFFFF;">
    <h4 style="text-transform:uppercase; font-size:16px; color:#FFC700;">GALLERY - RENTAL SPACE AND ITEMS</h4>
<p>
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            // Get media
            $sql = "SELECT * from rental_gallery order by orderno ASC ";
            $sql2 = mysqli_query($con, $sql);
            $i = 0;
            while ($rowa = mysqli_fetch_array($sql2)) {
                if ($i == 0) {
                    $kayd = "active";
                } else {
                    $kayd = "";
                }
                $media = $rowa["picture"];
                ?>
                <div class="carousel-item <?php echo $kayd; ?>">
                    <img class="d-block w-100 img-fluid" src="../gallery/<?php echo $media; ?>" alt="Image <?php echo $i; ?>">
                </div>
                <?php $i++;
            } ?>
        </div>
        <ol class="carousel-indicators">
            <?php
            $sql = "SELECT * from rental_gallery order by orderno ASC ";
            $sql2 = mysqli_query($con, $sql);
            $i = 0;
            while ($rowa = mysqli_fetch_array($sql2)) {
                if ($i == 0) {
                    $kayd = "active";
                } else {
                    $kayd = "";
                }
                ?>
                <li data-bs-target="#carouselExampleControls" data-bs-slide-to="<?php echo $i; ?>"
                    class="<?php echo $kayd; ?>"></li>
                <?php $i++;
            } ?>
        </ol>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</p>


               <div class="row">
               <div class="col-lg-12 col-md-12">
			   <p><div class="btn-wrap" style="text-align:center;">
               <a href="rental.php" class="submitn">CLICK TO BOOK</a>
			   <a href="../index.php" class="submitn">BACK TO HOME</a></div>
               </div>
               </div></p>
               
  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->

 <?php include"footer.php";  ?>