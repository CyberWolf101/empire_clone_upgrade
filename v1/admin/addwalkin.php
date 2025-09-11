
<?php 
 include 'connect_to_mysqli.php';
if(isset($_post['submin'])){
$first=$_POST['names'];
$first=$_POST['price'];

 $submit = mysqli_query($con,"insert into texting(id,names,prices,money) values ('','$first','$per','')") or die ('Could not connect: ' .mysqli_error($con));
    
  
}
?>

<?php

 include 'connect_to_mysqli.php';
//Including Database configuration file.


//Search box value assigning to $Name variable.
   $Name = $_POST['search'];
//Search query.
   $Query = "SELECT all* from baby WHERE name LIKE '%$Name%' LIMIT 1000";
//Query execution
   $ExecQuery = MySQLi_query($con, $Query);
//Creating unordered list to display result.
   echo '
<ul style="list-style-type:none;">
   ';
   //Fetching result from database.
   while($row = mysqli_fetch_array($ExecQuery )) {
       ?>
   <!-- Creating unordered list items.
        Calling javascript function named as "fill" found in "script.js" file.
        By passing fetched result as parameter. -->
    
       <form method="post" action="" >
           <table>
             <tr class="ter mx-3" onclick=\'this.querySelector("button[type=submit]").click()\' >
                 <td><input type="text" value="'.<?php echo $row['name'] ?>.'" class="form-control" name="names" hidden /></td>  
                    <td><input type="text" value="'.<?php echo $row['price'] ?>.'" class="form-control" name="price" hidden /></td> 
                     <td> <button class="submitn" type="submit" name="submin"><?php echo $row['name'] ?>,<?php echo $row['price'] ?></button><?php echo $he; ?> </td>
               </tr>
               
               
           </table>
      </form>';
   <!-- Below php code is just for closing parenthesis. Don't be confused. -->
   <?php
} 
?>
</ul>

