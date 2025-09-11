<?php include"header.php";  include "rental_center.php"; 
$rentid=$_SESSION['rent_id'];


$sql = "SELECT * from rental_center where rental_id='$rentid' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
    
$id=$row['rental_id'];
$name=$row['name'];
$email=$row['email'];
$dateregistered=$row['dateregistered'];
$datetouse=$row['datetouse'];
$firstreason=$row['firstreason'];
$secondreason=$row['secondreason'];
$duration=$row['duration'];
$people=$row['people'];
$confirmation=$row['confirmation'];



}
?>
<main>
         
 <style>
.btn-buya {
  display: inline-block;
  padding:5px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#FFC700;
  margin-bottom:20px;
  width:300px;
  
}
.btn-buya:hover {
  display: inline-block;
  padding:10px;
  border:none;
  color: #fff;
  text-align:center;
  font-size: 14px;
  text-transform:uppercase;
  font-family: 'Poppins', Open sans;
  font-weight: 800;
  background:#000000;
  margin-bottom:20px;
  width:300px;
  
}

.status{
 color:#FFC700;
 font-size:48px;
 text-transform:uppercase;
 
}

.prepair{
    text-transform:uppercase;
    font-weight:600;
    color:#FFC700;
} 

.prepair span{
    text-transform:none;
    font-weight:500;
    color:white;   
}

img{
    width:50%;
    height:50%;
} 

.info{
    background:#FFC700;
    color:#fff;
    padding:10px 15px;
    border-radius:3px;
    height:800px;
}

.sender{
    font-size:13px;
    color:#fff;
    padding:5px 10px;
}
.message{
    font-size:16px;
}
.progress-text{
   font-size:13px;
}

.date{
    font-size:10px;
}
.file a{
    color:#fff;
}


</style>
		
		
		
		  
		  <div style="margin-top:100px; color:#FFFFFF; padding:50px;">
		  <div class="justify-content-center" align="center"><p><span class="status">Status- <?php echo $confirmation; ?></span>
		  <br>View details and send message to support here</p></div>
		 <div class="row justify-content-center">
		     
		     
         <div class="col-lg-6 info">
        <p><div class="progress" style="text-align: center !important;">
        <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
        </div><span class="progress-text"><?php echo $status; ?></span>
         </div><span class="progress-text"><?php echo $name; ?></span>
         </p>
	    </div>
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   </div></div>
		  

 		  
		  
		
  
		  
		  
		  
		  
		  
		  </div>
		   
       <?php include "footer.php"; ?>