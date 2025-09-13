<?php session_start();
$orid=$_SESSION['ider'];
include"header.php" ;
include "connect_to_mysqli.php";
  
$sql = "SELECT all* from cart where id='$orid' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$nam=$row["name"];
						$em=$row["email"];
						$mb=$row["phone"];
						}?>
<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/vendor/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
<style>


.ter{
background-color:#fff;
padding:0 5px;
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
.btn-buya {
  display: inline-block;
  padding:6px;
  border:none;
  color: #fff;
  font-size: 10px;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
  font-weight: 800;
  transition: 0.3s;
  background:#FEBF01;
  
}

#clocs
{
display:none;}

#cloch{
display:none;
}

.koy{
color:#FFC700;    
}
.badge {
  padding-left: 9px;
  padding-right: 9px;
  -webkit-border-radius: 9px;
  -moz-border-radius: 9px;
  border-radius: 9px;
}

.label-warning[href],
.badge-warning[href] {
  background-color: #c67605;
}
#lblCartCount {
    font-size: 14px;
    background: #ff0000;
    color: #fff;
    padding: 0 5px;
    vertical-align: top;
    margin-left: -10px; 
}

.submita{
  
  background: #FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px;
  font-size: 14px;
  font-weight: 600;
  outline:none;
  border:none;
  float:right;
  margin-bottom:10%;
 
 
}

.submita:hover{
  background: #000000;
  color: #fff;
  outline:none;
  border:none;
  margin-bottom:10%;
}
</style>
        <div class="section-title">
           <h2>ORISHIRISHI</h2>
          <p>Order for Walk in Customers</p>
        </div>
  
<div class="row">
</div>
			
<?php 
	    if (isset($_GET['submitn']))
	 {
        $ren=$_GET['sub'];
		session_start();
		$_SESSION['shan']=$ren;
   echo header("location:orders.php");
	 }
   ?>
   
   
<?php 
		     include "connect_to_mysqli.php";
		    if (isset($_POST['submin']))
	 {$nam =  $_POST['food']; $ord =  $_POST['idea']; $val = $_POST['va'];
	$med = $_POST["dear"];

$sqk = "SELECT all* from food where id='$nam'";
		$sqlp = mysqli_query($con,$sqk);
			 while($rowe = mysqli_fetch_array($sqlp))
				  {
				$see = $rowe['name'];
				$per = $rowe['price'];
				$me = $rowe["nom"];
			
				}

$pre=$per*$val;

					   $submit = mysqli_query($con,"insert into enter(id,orderid,price,no,priced,status,date) values ('$see','$ord','$per','$val','$pre','','$med')") or die ('Could not connect: ' .mysqli_error($con));
   $kab=$me-$val;
   $submit = mysqli_query($con,"insert into stock(id,des,nom) values ('$nam','minus','$val')") or die ('Could not connect: ' .mysqli_error($con));	    
   $insert = mysqli_query($con,"UPDATE food SET nom= '$kab' where id='$nam'") or die ('Could not connect: ' .mysqli_error($con)); 	
					echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#myModal").modal("show");
			});
		</script>';

					}
					 
					 
					 ?></p>
					   <p> <?php 
		     include "connect_to_mysqli.php";
		     if (isset($_POST['submitt']))
{

$nam =  $_POST['drink']; $ord =  $_POST['idea']; $val = $_POST['va'];
$med = $_POST["dear"];


$sqk = "SELECT all* from food where id='$nam'";
		$sqlp = mysqli_query($con,$sqk);
			 while($rowe = mysqli_fetch_array($sqlp))
				  {
				  $see = $rowe['name'];
				$per = $rowe['price'];
				 $me = $rowe["nom"];
				
				}

$pre=$per*$val;

					   $submit = mysqli_query($con,"insert into enter(id,orderid,price,no,priced,status,date) values ('$see','$ord','$per','$val','$pre','','$med')") or die ('Could not connect: ' .mysqli_error($con));
   $kab=$me-$val;
   $submit = mysqli_query($con,"insert into stock(id,des,nom) values ('$nam','minus','$val')") or die ('Could not connect: ' .mysqli_error($con));	    
   $insert = mysqli_query($con,"UPDATE food SET nom= '$kab' where id='$nam'") or die ('Could not connect: ' .mysqli_error($con)); 	
					echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#myModal").modal("show");
			});
		</script>';
					}
					 
					 
					 ?>
   
   
 <div class="col-lg-12 col-md-8">
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
            <p><a href="payback.php" name="submit" class="submita" class="btn-buy" >Procced to Payment </a>  </p>
           
            
            
     <script>
function showResult(str) {
  if (str.length==0) {
    document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="0px";
    return;
  }
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("livesearch").innerHTML=this.responseText;
      document.getElementById("livesearch").style.border="1px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","livesearch.php?q="+str,true);
  xmlhttp.send();
}
</script>       
            
            
            
            
              <p><center>
               <form><input type="text" size="30" onkeyup="showResult(this.value)" class="form-control" style="width:50%;"/></form></center></p>
              
              
              
           


     
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
<p style="text-align:center;"><i class="fa fa-shopping-cart fa_custom fa-3x koy"></i><span class='badge badge-warning' id='lblCartCount'> 

		<?php	
				
				$sql = "SELECT count(*) As 'total'
						FROM enter where  orderid='$orid'
						 ";
		 $sql2 = mysqli_query($con,$sql);
		 $dad = mysqli_fetch_assoc($sql2);
		 
 
           $kany=$dad['total'];
           
            echo $kany;
           ?>
           </span></p>	
           
             <p><form enctype="multipart/form-data" method="post"><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th></th><th></th></thead>
	<tbody id="livesearch">

 
<?php 
 include "connect_to_mysqli.php";
   $sa =$_POST['kayd'];
	 
	 
	    if((!$sa))
	  {
$sql = "SELECT all* from food ORDER By name ASC LIMIT 1000";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../food/'.$row["file_name"];
						$ide = $row['cart'];
							$no = $row['nom'];
						
				$data=date("Y-m-d");			
				
				if ($no > 1)
				{
				$show = 'Quantity<input class="form-control" type="number"  max="'.$row['nom'].'" min="1" name="va" value="1" /><br>
	<input type="text" value="'.$orid.'" class="form-control" name="idea" hidden />
	<input type="date" name="dear" min="'.$data.'" value="'.$data.'" class="form-control" required hidden/>
    <button type="submit" name="submin" class="btn-buya" >Add To Cart</button>';    
				}
				else
				{
				    
			$show='<p style="color: #FFC700;">Out Of Stock.</p>';
				}

echo'<form action="" method="post">
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['id'].'" name="food" hidden />&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['name'].'</span><br>&#8358;'.$row['price'].'.00</td>
	<td class="check" style="font-size:16px;">'.$show.'
	</td></tr></form>';
	}}
	
	else
	{
	$sql = "SELECT all* from food WHERE name LIKE '%".$sa."%' ORDER By name DESC LIMIT 1000";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../food/'.$row["file_name"];
						$ide = $row['cart'];
						$no = $row['nom'];
						
				$data=date("Y-m-d");			
				
				if ($no > 1)
				{
				$show = 'Quantity<input class="form-control" type="number"  max="'.$row['nom'].'" min="1" name="va" value="1" /><br>
	<input type="text" value="'.$orid.'" class="form-control" name="idea" hidden />
	<input type="date" name="dear" min="'.$data.'" value="'.$data.'" class="form-control" required hidden/>
    <button type="submit" name="submin" class="btn-buya" >Add To Cart</button>';    
				}
				else
				{
				    
			$show='<p style="color: #FFC700;">Out Of Stock.</p>';
				}

echo'<form action="" method="post">
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['id'].'" name="food" hidden />&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['name'].'</span><br>&#8358;'.$row['price'].'.00</td>
	<td class="check" style="font-size:16px;">'.$show.'
	</td></tr></form>';
	}}
	
	?>
						
						
						
						
						
							
						
						
						
						
						
						
						
						
	
	
	

	</tbody>
	</table>
 

		  
		  
	
      

      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

 <?php include "footer.php"; ?>