<?php include "header.php";
$ran=$_SESSION['godid'];
?>
 <?php /* include "detest.php"; */ ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

  $('#myInput').on('keyup',function () {
      let inputValue = this.value;
      let errMsg = "";
      let outputDiv = ".result";
      console.log('inputValue ', inputValue);

      if(inputValue != "") { // input receieved

        $.ajax({
          url:"backend-search.php",
          data: {'input':inputValue},
          dataType: "html",
          type: "POST",
          success: function (response) {
             // console.log('response ', response);
             $(outputDiv).empty().html(response);
          }
        });

      } else { // no input found
        let msg = "Please type your message or keyword.";
        $('.errMsg').text(msg);
      }

  });

});

</script>
<style>
.result{
    
    background-color:#FFFFFF;
    padding:5px 10px;
}
.bord{
    
    border-top:10px solid #F5F5F5;
    width:980%;
}
#main{
    background-color:#F5F5F5;
    padding:5px 10px;
}
  .edit {
      background-color:#FFFFFF;
      padding:15px 5px;
  }
   .s003 form {
  width: 100%;
  max-width: 790px;
  margin-bottom: 0;
  
}

.s003 form .inner-form {
  /*background: #fff; */
  border:1px solid black;
  display: -ms-flexbox;
  display: flex;
  width: 60%;
  -ms-flex-pack: justify;
      justify-content: space-between;
  -ms-flex-align: center;
      align-items: center;
 
  border-radius: 3px;
}

.s003 form .inner-form .input-field {
  height: 30px;
}

.s003 form .inner-form .input-field input {
  height: 100%;
  background: transparent;
  border: 0;
  display: block;
  width: 100%;
  padding: 10px 32px;
  font-size: 16px;
  color: #555;
}

.s003 form .inner-form .input-field input.placeholder {
  color: #888;
  font-size: 16px;
}

.s003 form .inner-form .input-field input:-moz-placeholder {
  color: #888;
  font-size: 16px;
}

.s003 form .inner-form .input-field input::-webkit-input-placeholder {
  color: #888;
  font-size: 16px;
}

.s003 form .inner-form .input-field input:hover, .s003 form .inner-form .input-field input:focus {
  box-shadow: none;
  outline: 0;
  border-color: #fff;
}

.s003 form .inner-form .input-field.first-wrap {
  width: 200px;
  border-right: 1px solid rgba(0, 0, 0, 0.1);
}

.s003 form .inner-form .input-field.first-wrap .choices__inner {
  background: transparent;
  border-radius: 0;
  border: 0;
  height: 100%;
  color: #fff;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-align: center;
      align-items: center;
  padding: 10px 30px;
}

.s003 form .inner-form .input-field.first-wrap .choices__inner .choices__list.choices__list--single {
  display: -ms-flexbox;
  display: flex;
  padding: 0;
  -ms-flex-align: center;
      align-items: center;
  height: 100%;
}

.s003 form .inner-form .input-field.first-wrap .choices__inner .choices__item.choices__item--selectable.choices__placeholder {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-align: center;
      align-items: center;
  height: 100%;
  opacity: 1;
  color: #888;
}

.s003 form .inner-form .input-field.first-wrap .choices__inner .choices__list--single .choices__item {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-align: center;
      align-items: center;
  height: 100%;
  color: #555;
}

.s003 form .inner-form .input-field.first-wrap .choices[data-type*="select-one"]:after {
  right: 30px;
  border-color: #e5e5e5 transparent transparent transparent;
}

.s003 form .inner-form .input-field.first-wrap .choices__list.choices__list--dropdown {
  border: 0;
  background: #fff;
  padding: 20px 30px;
  margin-top: 2px;
  border-radius: 4px;
  
}

.s003 form .inner-form .input-field.first-wrap .choices__list.choices__list--dropdown .choices__item--selectable {
  padding-right: 0;
}

.s003 form .inner-form .input-field.first-wrap .choices__list--dropdown .choices__item--selectable.is-highlighted {
  background: #fff;
  color: #63c76a;
}

.s003 form .inner-form .input-field.first-wrap .choices__list--dropdown .choices__item {
  color: #555;
  min-height: 24px;
}

.s003 form .inner-form .input-field.second-wrap {
  -ms-flex-positive: 1;
      flex-grow: 1;
}

.s003 form .inner-form .input-field.third-wrap {
  width: 74px;
}

.s003 form .inner-form .input-field.third-wrap .btn-search {
  height: 100%;
  width: 100%;
  white-space: nowrap;
  color: #fff;
  border: 0;
  cursor: pointer;
  background: #CCCCCC;
	background: linear-gradient(#1b1b1b, #CCCCCC);
  transition: all .2s ease-out, color .2s ease-out;
}

.s003 form .inner-form .input-field.third-wrap .btn-search svg {
  width: 16px;
}

.s003 form .inner-form .input-field.third-wrap .btn-search:hover {
  background: #FEBF01;
}

.s003 form .inner-form .input-field.third-wrap .btn-search:focus {
  outline: 0;
  box-shadow: none;
}

    
</style>
        <?php
 include "connect_to_mysqli.php";
 $hide="visible";
  $hided="hidden";
if(isset($_POST['submitservice'])){
    
$name=$_POST['submitservice'];
$price=0;



$sql = "SELECT id, gen, name, price, time
FROM baby where id =$name ";
	
	 $sql2 = mysqli_query($con,$sql);
		while ($row = mysqli_fetch_array($sql2)) {
	
$names=$row['id'];
$gen=$row['gen'];
$namesy=$row['name'];
$price=$row['price'];
$duration=$row['time'];
}
$submit = mysqli_query($con,"insert into entry(id,gen,name,price,time,idno,status) 
values ('$names','$gen','$namesy','$price','$duration','$ran','no')") or die ('Could not connect: ' .mysqli_error($con));
}
if($submit){
    
     $hide="hidden";
     $hided="visible";
}
  

?>
<main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
<div class="row">
      <div class="col-lg-4">
        <div class="section-title">
            <?php echo $success ?>
           <h6>  Add Booking</h6>
    </div>
        </div> </div>
        <div class="row edit">
        
        <div class="col-lg-7 sm-7">
            <div class="s003">
      <form>
        <div class="inner-form">
          
          <div class="input-field second-wrap">
           <input type="text" id="myInput" placeholder="Search Services">	
          </div>
          
          <div class="input-field third-wrap">
            <button class="btn-search" type="button">
              <svg class="svg-inline--fa fa-search fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
              </svg>
            </button>
          </div>
        </div>
      </form>
    </div>
     </div>
     <div class="col-lg-5 sm-5">
<div class="btn-wrap" align="center">
<form method="post">
<input type="text" value="<?php echo $ran; ?>" name="id" hidden />
<button type="submit" name="proceed" value="next" class="submitn disable"  style="visibility:<?php echo $hide; ?>;">PROCEED TO PAYMENT</button>
</form>
</div></div>
     <br>
    
		<div class="result">

</div>

<?php 

  
$sql = "SELECT * from staff ORDER By name DESC LIMIT 15";
$result = mysqli_query($con,$sql);
 if(mysqli_num_rows($result) > 0){
      $options= mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    


        ?>
       
        <form method ="POST" name = "myform" action="" onsubmit="submitForm(event)"> 
        
        <div class="col-lg-12">
           
        <div class="table-responsive">
            
           
                      
                   
            <table class="table table-striped table-bordered" style="width:100%; ; margin-top:5px;">
              <thead>
                <tr style="border:0.8px solid black; border-left:none; border-right:none;">
                  
                  <th scope="col" class="border-0 ">
                    <div class="p-2 px-3 text-uppercase align-middle" >Services</div>
                  </th>
                  <th scope="col" class="border-0 ">
                    <div class="py-2 text-uppercase align-middle">Price</div>
                  </th>
                  <th scope="col" class="border-0">
                    <div class="py-2 text-uppercase align-middle">Duration</div>
                  </th>
                  <th scope="col" class="border-0 ">
                    <div class="py-2 text-uppercase align-middle">No. of people</div>
                  </th>
                  <th scope="col" class="border-0 ">
                    <div class="py-2 text-uppercase align-middle">Choose Date</div>
                  </th>
                  <th scope="col" class="border-0 ">
                    <div class="py-2 text-uppercase align-middle">Choose Time</div>
                  </th>
                  <th scope="col" class="border-0 ">
                    <div class="py-2 text-uppercase align-middle">Choose Staff</div>
                  </th>
                  
                  <!----
                  <th scope="col" class="border-0 ">
                    <div class="py-2 text-uppercase">Total</div>
                  </th>
                  ---->
                   
                </tr>
              </thead>
         <?php 
         
  /*
$sql = "SELECT * from staff ORDER By name DESC LIMIT 15";
$sql2 = mysqli_query($con,$sql);
$result= mysqli_fetch_array($sql2);
echo $result['name'];
   */
   ?>
   
      <tbody>
             
        
              <?php
           
             $count=1;
             $sql = "SELECT all* from entry where idno='$ran' && status='no'  ORDER BY S DESC";
	
	 $sql2 = mysqli_query($con,$sql);
		while ($row = mysqli_fetch_array($sql2)) {
		   




	

?>




           
                 
                <tr style="background-color:#F5F5F5;">
                   
                  <th  scope="row" class="border-0">
                    <div class="p-2">
                      
                      <div class="ml-3 d-inline-block align-middle">
                        <p class="mb-0"> <a href="#" class="text-dark d-inline-block align-middle"><?php echo $row['name'] ; ?> <input  type="text" name="services[]"  value="<?php echo $row['id']; ?>" style="width:200px;" hidden></a></p>
                      </div>
                    </div>
                  </th>
                   <th  class="border-0">
                    <div class="p-2">
                      
                      <div class="ml-3 d-inline-block align-middle">
                        <p class="mb-0"><i><?php echo $row['price']; ?><input  type="text" name="Cost[]"  value="<?php echo $row['price']; ?>" style="width:200px;" hidden></i></p>
                      </div>
                    </div>
                  </th>
                   <th  class="border-0">
                    <div class="p-2">
                      
                      <div class="ml-3 d-inline-block align-middle">
                        <p class="mb-0"><i><?php echo $row['time']; ?></i></p>
                      </div>
                    </div>
                  </th>
                  <th  class="border-0">
                    <div class="p-2">
                      
                      <div class="ml-3 d-inline-block align-middle">
                        <p class="mb-0"><strong><input type = "text" name = "qty[]" style="width:30px;" onkeyup="calculate(this.value)" required></strong></p>
                      </div>
                    </div> 
                  </th>
                  <th  class="border-0">
                    <div class="p-2">
                      
                      <div class="ml-3 d-inline-block align-middle">
                        <p class="mb-0"><strong><input type="date" class="form-control bookingDate" name="date[]" min="<?php echo date("Y-m-d"); ?>"    required/></strong></p>
                      </div>
                    </div> 
                  </th>
                      <th  class="border-0">
                    <div class="p-2">
                      
                      <div class="ml-3 d-inline-block align-middle">
                        <p class="mb-0"><strong><input type="time" class="form-control bookingTime" name="time[]" style="width:100px;" required /></strong></p>
                      </div>
                    </div> 
                  </th>
                     <th  class="border-0">
                    <div class="p-2">
                      
                      <div class="ml-3 d-inline-block align-middle">
                        <h5 class="mb-0"><strong><select class="form-control bookingStaff" name="staff[]"  onchange="checkStaffAvailability(this)" style="width:200px;">
<option value="">-Select Staff-</option>


   <?php 
  foreach ($options as $option) {
  ?>
    <option><?php echo $option['name']; ?> </option>
    <?php 
    }
   ?>


	
</select></strong></h5>
                      </div>
                    </div> 
                  </th>
                   
                 
                   
                
                  <input  type="text" name="serv[]"  value="<?php echo $row['name']; ?>"  hidden>
                 <!--- <input  type="text" name="total[]"  value="<?php //echo $row['price']* $qty; ?>" hidden>  --->
                 <input  type="text" name="ter[]"  value="<?php echo $row['time']; ?>" hidden>
               
        
                  <!--- <td class="border-0 align-middle"><strong><input type="text"   name="textbox5" style="width:100px;" required> </strong> </td> ----->
                   
                  
                </tr>
                
                
               
             
              
    
              <?php $count++; }  ?>
     </tbody>
            </table>
             </div>
         
          </div>
         
    <br><br>
         <div class="bord"> </div>
         <br> 
  		<div class="col-lg-6">
<div class="btn-wrap" align="center">
<input type="text" value="<?php echo $ran; ?>" name="id" hidden/>
<button type="submit" name="submit" value="next" class="submitn disable"style="visibility:<?php echo $hided; ?>;">PROCEED TO PAYMENT</button>
</form></div></div>


 </div>

         
          
          
          
          
           
          
          </div>
       
        </div>
        </section>
        </main>

        
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"> </script>  


<script>
   function checkStaffAvailability(selectElement) {
  var selectedValue = $(selectElement).val();
  var bookingTime = $(selectElement).closest('tr').find('input[name="time[]"]').val();
  var bookingDate = $(selectElement).closest('tr').find('input[name="date[]"]').val();

  // Make an Ajax request to check staff availability
  $.ajax({
    url: 'availability.php',
    method: 'POST',
    data: { staffName: selectedValue, bookingTime: bookingTime, bookingDate: bookingDate },
    dataType: 'json',
    success: function(response) {
      if (response.isBooked) {
        alert('Selected staff is already booked for the same time and date. Please choose another staff.');
        // Reset the select element value
        $(selectElement).val('');
      }
    },
    error: function(xhr, status, error) {
      console.error('Error: ' + error);
    }
  });
}


    
</script>
<?php
if(isset($_POST['proceed'])){

$ran=$_POST['id'];

session_start();
$_SESSION['idea'] =$ran;
echo header("location: payback.php");
}
?> 
 <?php include"footer.php";  ?>