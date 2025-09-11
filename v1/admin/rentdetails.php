<?php include"header.php";
	include	 'connect_to_mysqli.php';
$rent=$_SESSION['rent_id'];

$sql = "SELECT * from rental_center where s='$rent' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
 $ids=$row['s'];   
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
$phone=$row['phone'];

}
	include	 'connect_to_mysqli.php';
$sql5 = "SELECT * from  rentrate ";  

	     $sql6 = mysqli_query($con,$sql5);
		while ($row = mysqli_fetch_array($sql6)) {
		   $pr=$row['price'];
		   
		}
		$a=$pr*$people; 
		$c=$a;
		
		?>
		
		<?php
		 if (isset($_POST['approve']))
 {
		    header("location: approve.php"); 
					 
 }
		 if (isset($_POST['reject']))
 {
		    header("location: reject.php"); 
					 
 }

?>
<?php
	

?>


<main id="main">
<style>


.prepair{
    text-transform:uppercase;
    font-weight:600;
    color:blue;
} 

.prepair span{
    text-transform:none;
    font-weight:500;
    color:black;   
}

img{
    width:50%;
    height:50%;
} 
.skill{
                
                display: none;
                
            }
.info{
    background: #040b14;
    color:#fff;
    padding:10px 15px;
    border-radius:8px;
    
}

.sender{
    font-size:13px;
    color:#fff;
    padding:5px 10px;
}
.message{
    font-size:16px;
}
.date{
   font-size:10px; 
}
.file a{
    color:#fff;
}
.skill{
                
                display: none;
                
            }
</style>
 <script>
 // Function to prompt for form submission
function promptSubmitForm() {
  var inputValue = document.getElementById("myFormInput").value;
  var confirmation = confirm("Are you sure that you want to carry out the following action: " + inputValue + "?");
      
  if (confirmation) {
    // Submit the form
    return true;
  } else {
    // User selected "Cancel," prevent form submission
    return false;
  }
}

</script> 








    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Rental Requests - (<?php echo $name; ?>)</h2>
          <p>Here are all the logs of every rent request. View details here</p>
        </div>
      

					
<div class="row"><div class="col-lg-6">
 
<p class="prepair">Rental Id:  <span><?php echo $id; ?></span></p> 
<p class="prepair">Name:  <span><?php echo $name; ?></span></p>  
<p class="prepair">Email:  <span><?php echo $email; ?></span></p>  
<p class="prepair">Date:    <span><?php echo $datetouse; ?></span></p>  
<p class="prepair">Phone Number:    <span><?php echo $phone; ?></span></p>  
<p class="prepair">First Reason:    <span><?php echo $firstreason; ?></span></p> 
<p class="prepair">Extra Reason:    <span><?php echo $secondreason; ?></span></p>
<p class="prepair">Duration hour:    <span><?php echo $duration; ?></span></p>
<p class="prepair">Status:    <span><?php echo $confirmation; ?></span></p>
<p class="prepair">Number of People:    <span><?php echo $people; ?></span></p>
<p class="prepair">Total Rate:    <span><?php echo "₦".$c; ?></span></p>

<div class="form-holder">
	                    		<select name="skill_dropdown" id="skill_dropdown" class="form-control">
                   <option disabled="disabled" selected="selected">Choose option</option>
                    <option value="Approve" class="option">Approve</option>
                    <option value="Reject" class="option">Reject </option>
<option value="" selected> - Select Action -</option>    

                    
                </select>
							
				<br> 				
							
							
							<div class="Approve skill">
						
<form action='' method='post'>

<input type='submit' name='approve' value='Approve' class='submitn' ></form>
             
</div>
							
						
						</div>
						
					<div class="Reject skill">
					    <form action='' method='post'>
						<input type='submit' name='reject' value='reject' class='submitn' ></form>
  </div>


</div>
</div>
</div>
</section>








  </main><!-- End #main -->
 
  <?php include"footer.php" ?>
  <script type="text/javascript">
            $(document).ready(function () {
                $("#skill_dropdown").change(function () {
                    var inputVal = $(this).val();
                    var eleBox = $("." + inputVal);
                    $(".skill").hide();
                    $(eleBox).show();
                });
            });
        </script>