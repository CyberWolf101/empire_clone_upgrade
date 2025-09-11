<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>ChbLuxuryEmpire</title>
        <meta content="" name="description">
        <meta
        content="" name="keywords">

        <!-- Favicons -->
        <link href="assets/img/favicon.png" rel="icon">
        <link
        href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
        <link href="assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
        <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
        <link
        href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

        <!-- Template Main CSS File -->
        <link href="assets/css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/index.css">
        <!-- <link rel="stylesheet" href="styling.css"> -->
    </head>

    <body>
	        <style>
      @import url("https://fonts.googleapis.com/css?family=Amatic+SC");

      .btn-anim {
        border: none;
        display: block;
        text-align: center;
        cursor: pointer;
        text-transform: uppercase;
        outline: none;
        overflow: hidden;
        position: relative;
        color: #fff;
        font-weight: 400;
        font-size: 15px;
        background-color: #ffc700;
        padding: 17px 60px;
        border-radius: 5px;
        margin: 0 auto;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      }

      .btn-anim span {
        position: relative;
        z-index: 1;
      }

      .btn-anim:after {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 490%;
        width: 140%;
        background: #000;
        -webkit-transition: all 0.5s ease-in-out 100ms;
        transition: all 0.5s ease-in-out 100ms;
        -webkit-transform: translateX(-110%) translateY(-25%) rotate(45deg);
        transform: translateX(-110%) translateY(-25%) rotate(45deg);
      }

      .btn-anim:before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 490%;
        width: 140%;
        background: #fff;
        -webkit-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;
        -webkit-transform: translateX(-110%) translateY(-25%) rotate(45deg);
        transform: translateX(-110%) translateY(-25%) rotate(45deg);
      }

      .btn-anim:hover:before,
      .btn-anim:hover:after {
        -webkit-transform: translateX(-9%) translateY(-25%) rotate(45deg);
        transform: translateX(-9%) translateY(-25%) rotate(45deg);
		color:#fff;
      }

      .link {
        font-size: 20px;
        margin-top: 30px;
      }

      .link a {
        color: #000;
        font-size: 25px;
      }
    </style>
        <header class="header">
            <div class="d-flex justify-content-center" style="height:80vh;">
                <div class="menu" style="position: absolute; top: 10%; left: 50%; cursor: pointer;">
                    <i class='bx bx-x-circle' style="color: white;"></i>
                </div>
                <div style="position: absolute; top: 20%; height: 80vh;" class="soft">
                    <ul style="list-style: none; color: #FFC700; font-weight: 600;">
					<?php
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from cater ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
	echo'
 <li><form action="main/sub.php" method="post"><input type="hidden" value="'.$row['id'].'" name="cate" /><button type="submit" name="submit" value="submit" style="background:none; outline:none; border:none; color: #FFC700; text-transform:uppercase; font-weight: 600;">'.$row['name'].'</button></li></form><br>';
	}
	
	?>
					
               <li><a href="fad.php" style="color: #FFC700;">ORISHIRISHI</a></li><br>        
               <li><a href="con.php" style="color: #FFC700;">CONTACT</a></li><br>       
                    </ul>
                </div>
    
            </div>
        </header>
        <main>
        <main class="pt-3">
            <div id="main">
                <div style="width: 90%;">
                    <div class="text-center">
                        <a href="index.php"><img src="./assets/img/luxury/logo_luxury.png" alt="" class="img"></a>
                    </div>
                    <div class="mt-2">
                        <h3 class="text-center type"></h3>
                    </div>

                    <div class="text-center header-menu">
                        <i class='bx bx-menu text-center' style="color: white;"></i>
                    </div>  
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

.submitn{
  
  background: #FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px;
  font-size: 14px;
  font-weight: 600;
  outline:none;
  border:none;
  float:right;
 
 
}

.submitn:hover{
  background: #000000;
  color: #fff;
  outline:none;
  border:none;
}

 .btn-buya {
  display: inline-block;
  padding:5px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 12px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#FFC700;
  margin-bottom:20px;
  float:right;

  
}
.btn-buya:hover {
  display: inline-block;
   padding:5px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 12px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#000000;
  margin-bottom:20px;

  
}
p
{
color:white;
}
</style>
 <section id="pricing" class="pricing section-bg" style="margin-top:10px; background-color:none;  border:none;">
<h4 style="font-size:15px; background-color:#FFC700; ">CHBLUXURY EMPIRE - YOUR ONE STOP BODY CARE HOME</h4>
</section>
      <div class="container">

        <div class="row">
        
          <div class="col-lg-12 " data-aos="fade-right">
		 <p> <h2 align="center" style="color:#FFC700; font-weight:bolder;">TERMS AND CONDITIONS</h2></p><br>
            <h3 style="color:#FFC700;">MEMBERSHIP</h3>
            <p>
Our VIP membership is designed to serve our customers better, also to ensure our operation efficiency strictly for services only, 
thereby reducing the stress of having to make payments all the time. With our VIP membership, you can actually make payments ahead
for a given period of time.</p>
<p>For Instance, if you need our pedicure & manicure services or the barbing services or any of our services for a 
month, week, or year you can actually pay ahead of these periods this will give you the access to walk into the shop everyday until your subscription expires, 
without having to make payments over and over again for that period of your subscription. Moreover, this means that if you pay 50,000 naira for
microblading service for a month you will be given the access to walk into the shop for only microblading service everyday for that month without having to pay again.
            </p>
 <p>
      These terms and conditions of service apply to the purchase of services by the customer each of which is identified here in our website.
Our terms and conditions states that if you decide to purchase any of the membership be it the the monthly membership, quarterly membership 
or the yearly membership, your card will be activated for that period alone which implies that you can walk into the shop anytime and any day 
without having to pay again for that period.
            </p>
            <p>And if apparently you purchased a specific period of service and you don't walk in to get your service done 
for that period, this means that you can no longer get the service you purchased after the period you purchased had elapsed, which means you will have to
renew it to be able to use our service again. Moreover your membership subscription can't be used by any other person aside yourself, so be it your friends, 
family or kids that wants any of our service, he or she would have to purchase he's or her membership subscription separately for he or she service to be done.</p>

<p>No terms, conditions or warranties other than these identified in this webpage and no agreement or understanding, oral or written, in any way purporting to modify 
the terms and conditions whether contained in customers purchased order or shipping release forms, or elsewhere, shall be binding on CHB LUXURY EMPIRE unless hereafter 
made in writing and signed by CHB LUXURY EMPIRE authorized representative. Customers is hereby notified of CHB LUXURY EMPIRE
express rejection of any terms inconsistent with this Agreement or to any other terms proposed by customer in accepting CHB LUXURY EMPIRE agreement.
</p>
		</div></div>

 

      </div>
    </section><!-- End About Section -->
    </section><!--  Book Section  -->  
  
  
		   
        </main>
        <!-- End #main -->

        <!-- Vendor JS Files -->
        <script src="assets/vendor/aos/aos.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
        <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
        <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
        <script src="assets/vendor/php-email-form/validate.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


        <!-- header.js -->
        <script src="./assets/js/header.js"></script>

        <!-- Type.js -->
        <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
        <script>
            var typed = new Typed('.type', {
                strings: [
                    "Tell someone the difference ^1500", "Experience Luxurious Services, Second To None ^1500", "Ever Been At CHB Luxury Empire ^1500"
                ],
                backspeed: 60,
                typespeed: 60,
                loop: true
            });
        </script>

        <!-- Particle.js -->
        <script src="./assets/js/particles.js"></script>
        <script src="./assets/js/app.js"></script>

        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>

    </body>

</html>
