 $(document).ready(function(){

fetch_cart();






  // handle form submission
  $('.user-form').on('submit', function(e) {
    e.preventDefault();
    
  
    var formData = $(this).serialize();
    
    $.ajax({
      url: 'addcart.php',
      type: 'POST',
      data: formData,
      success: function(response) {
    $('#lblCartCount').html(response);
    $(".btnclick").removeAttr("disabled");
    $('#quantity').val('');
    $('#myModal').modal('show');
$('input[type=radio]').prop('checked', false);

    fetch_cart();
},
      error: function(xhr, status, error) {
        console.error('Error submitting form for row ' + rowId + ': ' + error);
         $(".btn-buya").removeAttr("disabled");
      }
    });
  });




  function fetch_cart()
  {
   var idea = $('#idea').val();
   $.ajax({
   url:"fetchcart.php",
   method:"POST",
   data:{
       	idea: idea 
   },
   success:function(data)
   {
   $('#lblCartCount').html(data);
   }
   });
   }






});


 