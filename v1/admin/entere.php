<?php 
 
// Load the database configuration file 
include "connect_to_mysqli.php"; 
 
// Fetch records from database 

			    $sql = "SELECT date as dater, id as name, Count(id) As most, 
        SUM(priced) as amount FROM enter WHERE status='Paid' && price!='0' GROUP BY ID,DATE ORDER BY date ASC ";
		  $sql2 = mysqli_query($con,$sql);
		

     $delimiter = ","; 
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
     
    // Set column headers 
    $fields = array('DATE', 'AMOUNT','ITEM','QUANTITY'); 
    fputcsv($f, $fields, $delimiter); 
     
    // Output each row of the data, format line as csv and write to file pointer 
  while ($row = mysqli_fetch_array($sql2)) { 
        $status = ($row['app'] == 1)?'Active':'Inactive'; 
        $lineData = array($row['dater'], $row['amount']); 
        fputcsv($f, $lineData, $delimiter); 
    } 
     
    // Move back to beginning of file 
    fseek($f, 0); 
     
    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="ChbLuxuryitems";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f); 
 
exit; 
 
?>