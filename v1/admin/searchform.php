<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css"/>

<!-- jquery cdn include -->
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

</head>
<body>
<?php
 include "connect_to_mysqli.php";
if(isset($_POST['submitservice'])){
$name=$_POST['submitservice'];
$price=0;

$submit = mysqli_query($con,"insert into text(id,price,services) 
values ('$name','$price','')") or die ('Could not connect: ' .mysqli_error($con));	

if($submit){
echo 'submitted';
		}
}

?>
<h2>Our Customers</h2>

<input type="text" id="myInput" placeholder="Search for names or country" title="Type in a name or country">

<div class="result">

</div>

</body>
</html>