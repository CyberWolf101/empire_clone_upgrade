<?php include"header.php" ?>
<main id="main">
<style>
.box{
    background:black;
    color:white;
    padding:30px;
}    
    
    
    
</style>
    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Walk In-Booking - Category</h2>
          <p>Choose Category</p>
        </div>
</section>
<section id="pricing" class="pricing">
<div class="row">
<div class="col">   
<div class="box">
   <div class="box px-4 py-5 mx-2 text-center col-lg-5 col-md-5 mt-5">
          <div>
          <h4 class="mb-3" style="text-transform:uppercase;" >'.$row["name"].'</h4>
           <img src="categ/'.$row["file_name"].'"  alt="" />  
                                <p class="mt-4">
                                  '.$row["des"].'
                                </p> <div class="mt-4">
								<div class="button_container">
                            <form action="main/sub.php" method="post"><input type="hidden" value="'.$row['id'].'" name="cate" />
                            <button type="submit" name="submit" value="submit"  class="btn-anim"><span>CLICK TO BOOK</span></button> 
                            </form></div>
                            </div>
                        </div>
                        </div> 
    
    
</div></div>  



</div>  
    </section><!-- End About Section -->

   
  </main><!-- End #main -->
  <?php include"footer.php" ?>