<?php include"head.php" ; ?>

   <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

          <div class="section-title">
          <h2>All Delta Kitchen Orders</h2>
          <p>Here are all the delta kitchen orders. Print receipts here</p>
          </div>
  
          <p><table class="table table-bordered" id="myTable" width='100%' border="0" style="color:black; font-size:13px; font-weight:500;"  cellspacing='10' data-toggle='bootgrid'>
           <thead><tr bgcolor="#173b6c" style="color:white;">
                    <th>ORDER ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <th>Receipt</th>
                </tr>
            </thead>
            <tbody>
              <?php include "display_orders.php"; ?>
            </tbody>
        </table></p>
      

      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

 <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>\
  <script type="text/javascript" src="datatables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#myTable').DataTable();
});
</script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/typed.js/typed.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>
</html>