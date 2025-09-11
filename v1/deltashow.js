 $(document).ready(function(){
    
    	fetch_delta();
    	
    	


 
 

 function fetch_delta()
  {
  var category = $('#category').val();
   $.ajax({
   url:"fetchdelta.php",
   method:"POST",
   data:{
         category:  category
   },
   success:function(data)
   {
    $('#showdelta').html(data);
   }
   });
   }







});

$("button").click(function() {
    var category = $(this).val();
     $.ajax({
   url:"fetchdelta.php",
   method:"POST",
   data:{
         category:  category
   },
   success:function(data)
   {
    $('#showdelta').html(data);
   }
   });
   alert(category);
});


 