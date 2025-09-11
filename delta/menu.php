<?php include "header.php";


$orid= $_SESSION['hord'];

if(empty($_SESSION['hord'])){
   echo header("location: index.php");
}


  
$sql = "SELECT all* from sub where id='$orid' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
				  {
				 		$nam=$row["name"];
					    $gene=$row["gen"];
					     $addmeal=$row["addmeal"];
						}


//Booking Type Code was here


 ?>
 <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="addcart.js"></script>
<script type="text/javascript" src="menu.js"></script>

 <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Added to Cart!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:#FFFFFF;"></button>
      </div>
      <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
        <p style="font-weight:600; font-size:13px;">Your cart has been successfully updated!<br>Press ok to continue or Proceed to payment directly</p>
		<p><button class="submitn"  data-bs-dismiss="modal">Ok</button></p>
           <p style="color:black;" style="margin-bottom:30px;"> <form action="check_out.php" method="post" >
         <input type="text" name="idea" value="<?php echo $ran;?>" hidden /><input type="submit" name="submit" value="Proceed to Payment" class="submitn"> </form></p>
          
      </div>
    </div>
  </div>
</div></div>












<style>
    #main{
        display:block;
    }
    #swallow{
        display:none;
    }
    #sides{
        display:none;
    }
</style>


<script>
    function scrollToNextInput(input) {
  var inputs = $(input).closest("form").find("input");
  var index = inputs.index(input);

  if (index < inputs.length - 1) {
    var nextInput = inputs[index + 1];
    while (nextInput.type === "checkbox" && nextInput.name === input.name && index < inputs.length - 1) {
      nextInput = inputs[++index];
    }
    $("html, body").animate({ scrollTop: $(nextInput).offset().top }, "slow");
    $(nextInput).focus();
  } else {
    var submitButton = $("button[type='submit']");
    if (submitButton.length > 0) {
      $("html, body").animate({ scrollTop: $(submitButton).offset().top }, "slow");
    }
  }
}

$(document).ready(function() {
  $("input[type='text'], input[type='checkbox']").on("input", function() {
    var inputName = $(this).attr("name");
    if (inputName === "food[]" || inputName === "quant[]") {
      scrollToNextInput(this);
    }
  });
});
$(document).ready(function() {
  $("input[type='text'], input[type='radio']").on("input", function() {
    var inputName = $(this).attr("name");
    if (inputName === "add" || inputName === "quanties") {
      scrollToNextInput(this);
    }
  });
});

</script>


          <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
          <div class="container" style="width:100%; margin:auto; ">
          <div class="section-title" style="color:#FFFFFF;">
          <h2>PACKAGES - <?php echo $nam; ?></h2>
          </div>

            <div class="row">
             <div class="col-lg-3 col-md-4">
		     <p style="color:#FEBF01;">Choose a package</p>
             <div class="box" data-aos="zoom-in" data-aos-delay="100">
			 </div></div>
			
               <div class="col-lg-9 col-md-8">
              <div class="box" data-aos="zoom-in" data-aos-delay="100">
                  

<div class="btn-wrap" style="margin-bottom:30px;">
<span style="float:left;"><a class="btn-buya" href="deltakitchen.php">OTHER SECTIONS</a></span> 


<form id="check_out.php" method="post" >
<input type="text" name="idea" value="<?php echo $ran;?>" hidden /><input type="submit" name="submit" value="Proceed to Payment" class="btn-buya"/></form>

<input type="text" id="idea" value="<?php echo $ran;?>" style="display:none;" />
</div>

<!--<div style="text-align:center;">
<button  onclick="toggleAll()" id="allButton" type="button" class="btn-buya">Main Dishes</button>
-<button  onclick="toggleSwallow()" id="swallowButton" type="button" class="btn-buya">Swallow</button>
<button  onclick="toggleSides()"   id="sidesButton" type="button" class="btn-buya">Sides</button></div>	--->

             <div id="main"><form method='post' class='user-form'>
             <table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
        	 <tbody>
<H3>Choose a Protein</H3>
  
<?php 
  
$sql = "SELECT all* from baby JOIN 
       delta_images ON baby.id=delta_images.id
       where gen='$orid' ORDER By name DESC LIMIT 100";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
 {
				 		$imageURL='../food/'.$row["image"];
						$ide = $row['cart'];
						$no = $row['nom'];
						
				        $data=date("Y-m-d");			
		

echo'<tr class="ter mx-3" onclick=\'this.querySelector("input[type=checkbox]").click(); check();\' >
	<td class="check"><input type="checkbox" style="pointer-events:none;"  class="rad" value="'.$row['id'].'" name="food[]"  />
	<input type="hidden" style="pointer-events:none;"  class="rad" value="'.$row['name'].'" name="namesy[]"  />
		<input type="hidden" style="pointer-events:none;"  class="rad" value="'.$row['time'].'" name="time[]"  />
			<input type="hidden" style="pointer-events:none;"  class="rad" value="'.$row['price'].'" name="price[]"  />
    &nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
    <td class="check"><span>'.$row['name'].'</span><br></td>
    <td class="check" style="font-size:16px">&#8358;'.$row['price'].'.00</td>
     <td class="check" style="font-size:16px;">
                      Quantity <input class="form-control quantity" type="number" value="1"  min="1" name="quant[]" id="quantity" placeholder="0" /><br>
                  </td>'
    ;
    echo "</tr>";



	}
	
	?>
	
	
		</tbody>
     	</table></div>
				
						
 <div >
<h3>ADDITIONAL MEALS</h3>   
<table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
        	 <tbody>
<?php 

             $sqls = "SELECT * FROM baby 
             JOIN delta_images ON baby.id = delta_images.id 
             WHERE baby.gen = '$addmeal'";
    $sql2s = mysqli_query($con, $sqls);

    while ($rows = mysqli_fetch_array($sql2s)) {
        $imageURL='../food/'.$rows["image"];
        $id = $rows['id'];
        $name = $rows['name'];
        $price = $rows['price'];

        echo '<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click(); check();\' >
                  <td class="check">
                      <input type="radio" style="pointer-events:none;" value="'.$id.'" name="add" id="add"/>
                      &nbsp;&nbsp;&nbsp;
                      
                      <label for="add_'.$id.'"><img src="'.$imageURL .'" class="img"/></label>
                  </td>
                  <td class="check"><span>'.$name.'</span><br>&#8358;'.$price.'.00</td>
                  <td class="check" style="font-size:16px;">
                      Quantity <input class="form-control" type="number" min="1" name="quanties" value="1"  id="quantity" placeholder="0" /><br>
                  </td>
              </tr>';
    }


	?>
	
	
		</tbody>
     	</table></div>

   
   
   
   
   
               <div class="btn-wrap" align="center" style="margin-bottom:80px;">
			   <input type="text" style="display:none;" value="<?php echo $ran; ?>" name="idea" />
			   <button type="submit" name="submit" value="submit" class="submitn btn-buya">ADD TO CART</button><br />
			   </form></div>
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
      </div>
    </section><!-- End Pricing Section -->

<form action="check_out.php" method="post" >
<input type="text" name="idea" value="<?php echo $ran;?>" hidden /><button type="submit" class="zero">
<a href="#" class="float">
<i class="bi bi-cart my-float"></i><span class="badge badge-warning" id="lblCartCount"></span>
</a></button></form>
<style>
.float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:40px;
	right:40px;
	background-color:#FEBF01;
	color:#FFF;
	border-radius:50px;
	text-align:center;
	box-shadow: 2px 2px 3px #999;
	display: flex;
  justify-content: center;
  align-items: center;
}

.my-float{
	font-size:24px;
	
}

.my-float:hover{
	color:white !important;
}

.float:hover{
	color:white !important;
}
</style>

    </main><!-- End #main -->
   
 <?php include"footer.php";  ?>