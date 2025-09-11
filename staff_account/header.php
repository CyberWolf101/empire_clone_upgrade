<?php  ob_start(); session_start(); 

date_default_timezone_set('Africa/Lagos');
include "../connect.php"; 
$code = "";
if(isset($_COOKIE['staffID'])) {
$code = $_COOKIE['staffID'];
} 
  
$date=date("Y-m-d");
	  
	        $check = "select * from staff where email ='".$code."' ";
	        $query = mysqli_query($con,$check);
	        if (mysqli_affected_rows($con) == 0)
		     {
		     $_SESSION['previous_page'] = $_SERVER['REQUEST_URI']; 
		     echo header("location:index.php");
            }else {
		    $sql = "SELECT * from staff where email='".$code."'  ";
			$sql2 = mysqli_query($con,$sql);
			while($row = mysqli_fetch_array($sql2))
				    
					{
						$id = $row["id"];   					
					    $name = $row['name'];
					    $email = $row['email'];
					    $status = $row['status'];
						$pass= $row['password'];
						$media=$row['file_name'];
					  
					}
                
                
               if($media==""){ $media="img/boy.png"; }
               else{ $media="../staff/".$media;                   }}

				  
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="favicon.png" rel="icon">
  <link href="favicon.png" rel="apple-touch-icon">
  <title>Staff - CHBLUXURYEMPIRE</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
   <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css">
       <!-- include the jQuery library -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.js"></script> 

</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon">
          <img src="favicon.png">
        </div>
        <div class="sidebar-brand-text mx-3" style="text-transform:uppercase;"><?php echo $status; ?></div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      <hr class="sidebar-divider">
     
       <li class="nav-item">
        <a class="nav-link" href="history.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>All Appointments</span>
        </a>
      </li>
      
      
      <hr class="sidebar-divider">
      <div class="version" id="version-ruangadmin"></div>
    </ul>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
          <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <ul class="navbar-nav ml-auto">
            <div class="topbar-divider d-none d-sm-block"></div>
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" src="<?php echo $media; ?>" style="max-width: 60px">
                <span class="ml-2 d-none d-lg-inline text-white small"><?php echo $name; ?></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </nav>
        <!-- Topbar -->
        
        
        
         <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          
<style>
.text-primary{
color:#000 !important;
}

.btn-primary{
background: #000 !important;
border:none !important;
}

a{
color:#FFC700;
}

a:hover{
color:#000;
}

body{
  color:#000 !important;  
 font-family: 'Poppins';
}

.page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: #FFC700;
    border-color:#FFC700;
}
.page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #FFC700;
    background-color: #fff;
    border: 1px solid #FFC700;
    -webkit-box-shadow: 0 .125rem .25rem 0 rgba(58,59,69,.2)!important;
    box-shadow: 0 .125rem .25rem 0 rgba(58,59,69,.2)!important;
}
.form-control{
	font-size:13px;
	border-radius: 0;
	font-weight:500;
	color:black;
}
</style>