<?php include "header.php" ?>

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Members</h2>
        </div>
       <p>
   <?php 
		     include "connect_to_mysqli.php";
			 if (isset($_GET['submin']))
 {
		    $use =  $_GET['ordid'];
	  	 
		  			  $insert = mysqli_query($con,"UPDATE member SET app= 'No' where s='$use'") or die ('Could not connect: ' .mysqli_error($con)); 

				echo"<p style='color:blue;'>Membership Successfully Ended!</staff>";	 
				}
					 ?>        
   <?php 
		     include "connect_to_mysqli.php";
			 if (isset($_GET['submit']))
 { $use =  $_GET['ordid'];
$sql = "SELECT * from member where s='$use'";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {
   	$cat = $row['type'];
				 	
		if($cat=="Monthly Membership")
{
    $day=31;
}
else if($cat=="Quarterly Membership")
{
    $day=31*3;
}
else if($cat=="Yearly Membership")
{
    $day=31*12;
}
				  
				  }		  
				  
	  	 
	
date_default_timezone_set('Africa/Lagos'); 
$dear=date("Y-m-d"); 	
	
$data= date('Y-m-d', strtotime("$dear +$day days"));	
	
	$insert = mysqli_query($con,"UPDATE member SET app= 'Yes' where s='$use'") or die ('Could not connect: ' .mysqli_error($con));
	$insert = mysqli_query($con,"UPDATE member SET start= '$dear' where s='$use'") or die ('Could not connect: ' .mysqli_error($con));
	$insert = mysqli_query($con,"UPDATE member SET ende= '$data' where s='$use'") or die ('Could not connect: ' .mysqli_error($con));
	echo"<p style='color:blue;'>Membership Package Successfully Updated!</staff>";	 
				}
					 ?>               
           
           
           
       </p>
       
       
       
       
       
       
       
       
       
        <p><center><form action="" method="post"><input type="text" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p>
	   <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					<th data-column-id='employee_name'  width='200px'>Name</th>
				    <th data-column-id='employee_name'  width='200px'>Email</th>
				    <th data-column-id='employee_name'  width='200px'>Mobile</th>
				    <th data-column-id='employee_name'  width='200px'>User(s)</th>
					<th data-column-id='employee_name'   width='200px'>MemberType</th>
					<th data-column-id='employee_name'  width='200px'>End Date</th>
					<th data-column-id='employee_salary'  width='200px'></th>
				    <th data-column-id='employee_salary'  width='200px'></th>
				    <th data-column-id='employee_salary'  width='200px'></th>
				</tr>
			</thead>
		  <?php
		  
	 	include "connect_to_mysqli.php";
	  $sa =  $_POST['kayd'];
	 
	 
	    if((!$sa))
	  {
		
	 $sql = "SELECT * from member where app != ''  ";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {
    
    $en=$row['ende'];


date_default_timezone_set('Africa/Lagos'); 
$data=date("Y-m-d"); 

$now = strtotime($en);
$your_date = strtotime($data); 
$datediff = $now - $your_date;
$day=$numberDays= round($datediff / (60 * 60 * 24));

if($day > 15)
{
    $cop="green";
}

else if($day < 15 && $day> 6)
{
    $cop="yellow";
}

else if($day < 6)
{
    $cop="red";
}














echo "<tr bgcolor='#fff'><td width='200px' >" . $row['first'] . " " . $row['last'] . "</td><td width='200px' >" . $row['email'] . "</td>
     <td width='200px'>" . $row['phone'] ."</td><td width='200px'>" . $row['nom'] ."</td><td width='200px'>" . $row['type'] ."</td>
    <td width='200px' style='color:".$cop.";'>" . $row['ende'] . "</td>
	<td width='200px'><a href='#delete" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>End</button></a>
			
		
	<div class='modal fade' id='delete" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>End Package?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to end this member package(" . $row['email'] . ")</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['s'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin' value='yes'>Yes</button></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
          </div>
      </div>
    </div>
  </div>
 </div> 
</div>

             
            </div>
</td>


	<td width='200px'><a href='#update" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>Update</button></a>
			
		
	<div class='modal fade' id='update" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Continue Package Plan?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to continue this member package(" . $row['email'] . ")</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['s'] . "' required hidden />  
        <input class='submitn' type='submit' name='submit' value='Yes'/></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
          </div>
      </div>
    </div>
  </div>
 </div> 
</div>

             
            </div>
</td>
<td width='200px'><form action='mora.php' method='post'><input type='hidden' value='" . $row['s'] . "'  name='idea'/>
<button type='submit' class='submitn'>View</button></form></td>
</tr></tr>";
				
}}
else
{
		   
					$sql = "SELECT all* from member WHERE first LIKE '%".$sa."%' || last LIKE '%".$sa."%' && app!='' ";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {
    
    
     $en=$row['ende'];


date_default_timezone_set('Africa/Lagos'); 
$data=date("Y-m-d"); 

$now = strtotime($en);
$your_date = strtotime($data); 
$datediff = $now - $your_date;
$day=$numberDays= round($datediff / (60 * 60 * 24));

if($day > 15)
{
    $cop="green";
}

else if($day < 15 && $day> 6)
{
    $cop="yellow";
}

else if($day < 6)
{
    $cop="red";
}








    
    
    
    
    
    
    
    
    
    
    
    
echo "<tr bgcolor='#fff'><td width='200px' >" . $row['first'] . " " . $row['last'] . "</td><td width='200px' >" . $row['email'] . "</td>
     <td width='200px'>" . $row['phone'] ."</td><td width='200px'>" . $row['nom'] ."</td><td width='200px'>" . $row['type'] ."</td>
    <td width='200px' style='color:".$cop.";'>" . $row['ende'] . "</td>
	<td width='200px'><a href='#delete" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>End</button></a>
			
		
	<div class='modal fade' id='delete" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>End Package?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to end this member package(" . $row['email'] . ")</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['s'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin' value='yes'>Yes</button></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
          </div>
      </div>
    </div>
  </div>
 </div> 
</div>

             
            </div>
</td>


	<td width='200px'><a href='#update" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>Update</button></a>
			
		
	<div class='modal fade' id='update" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Continue Package Plan?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to continue this member package(" . $row['email'] . ")</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['s'] . "' required hidden />  
        <input class='submitn' type='submit' name='submit' value='Yes'/></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
          </div>
      </div>
    </div>
  </div>
 </div> 
</div>

             
            </div>
</td>
<td width='200px'><form action='mora.php' method='post'><input type='hidden' value='" . $row['s'] . "'  name='idea'/>
<button type='submit' class='submitn'>View</button></form></td>
</tr></tr>";

				
}
}



				   
					
	
	  
	 
		 
	
	  ?> 
	  </table></div>
	  </p>
      
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>