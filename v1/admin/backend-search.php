
<html>
<head></head>
<body>
    <style>
    button:active
        
        {
            border:none;
        color:#FEBF01;
        outline:none;
        }
        button:focus
        
        {
            border:none;
        color:#FEBF01;
        outline:none;
        }
        .dynamic:active
        
        {
            border:none;
        color:#FEBF01;
        outline:none;
        }
          .dynamic:focus
        
        {
            border:none;
        color:#FEBF01;
        outline:none;
        }
         .dynamic:hover
        {border:none;
        color:#FEBF01;
         outline:none;
        }
        .dynamic{
            border:none;
        }
        
        
    </style>

<?php  include "connect_to_mysqli.php";

    $inputValues = $_POST['input'];

    // Prepare a select statement
    $sql = "SELECT all* from baby WHERE name LIKE '%".$inputValues."%'  AND gen != '0260' AND gen != '0330' AND gen != '0270' LIMIT 1000";
    $sql2 = mysqli_query($con,$sql);
    $show="";
 if(mysqli_num_rows($sql2) > 0){
                 $show .='
            <form method="post" action="">
            <table>';
      
                


           
             while($row = mysqli_fetch_array($sql2))
				  { 
			$show .= '
            <tr class="ter mx-3" onclick=\'this.querySelector("button[type=submit]").click()\' >
         
            <td><button style="background-color:transparent; color:black; border:none; padding:1px;" class="dynamic" value="'.$row["id"].'" type="submit" name="submitservice">' . $row["name"] . ',' . $row["price"] . '</button> </td>
            </tr>
            ';}
				    
				    
				      $show .=' </table></form>';
				      
				      
				      
 }
 else{
 $show=' no data matched';
 }
 
 echo $show;
      
	  
      
	
?>
</body>
 </html>
 