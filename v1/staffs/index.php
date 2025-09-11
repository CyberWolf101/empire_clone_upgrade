<?php include "header.php"; include "logus.php"; ?>
<body >

  

  <main id="main">
  
  
    <!-- ======= Track and Book Section ======= -->
    <section id="about" class="about">
      <div class="container" data-aos="fade-up">

        <div class="row content" align="center";>
          <div class="col-lg-4" style="padding:20px; margin: auto;
width: 100%;">
		<p><button style=" border:0; color: #FFFFFF; outline:0;  background: #FFC700;border-bottom:2px solid  #FFC700;">SIGN IN</button></p>
		 <p><?php global $as; echo "$as"; ?><br>
<?php global $we; echo "$we"; ?></p>
<form method="post">
<p><input type="email" class="form-control"  placeholder="*Enter Email Address" name="email" required></p>
<p><input type="password" class="form-control"  placeholder="*Enter Password" name="pass" required></p>
<p><input type="submit" name="submit" value="Login"  class="submitn" required /></p></form><br><br>

      </div>
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
         
      </div>
    </section><!--  Book Section  -->  
  
  


  </main><!-- End #main -->
  <?php include "footer.php"; ?>

 