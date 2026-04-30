<?php ob_start();
// session_save_path("/tmp");
// session_start();
session_save_path("../sessions");

// Only start session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Release session lock early if you don't need to write anymore
session_write_close();

//<?php session_start(); ob_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="favicon.png" rel="icon">
  <title>Login - CHBLUXURYEMPIRE</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-login">
  <!-- Login Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <p><img src="favicon.png" style="width:20%; height:auto;" /></p>
                    <h1 class="h4 text-gray-900 mb-4">CHBLUXURYEMPIRE Control Panel</h1>
                    <p style="color:#FFC700;"><?php include "login.php"; ?></p>
                  </div>
                  <form class="user" method="post">
                    <div class="form-group">
                      <input type="email" class="form-control" name="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address" required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" name="password" id="exampleInputPassword" placeholder="Enter Password" required>
                    </div>
                    <div class="form-group">
                     <input type="submit" value="Sign In" style="background-color:#000; border:none;" class="btn btn-primary btn-block" name="login" />
                    </div></form>
                  <div class="text-center">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Login Content -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html>