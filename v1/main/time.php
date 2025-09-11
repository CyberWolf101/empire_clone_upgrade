<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
$gift=$_SESSION['gift'];
$ran=$_SESSION['iron'];
$staff=$_SESSION['staff'];
$dear=$_SESSION['dear'];
$timer=$_SESSION['duration'];
$see=$_SESSION['seen'];
$gene=$_SESSION['gene'];  
    
    
    
    
}



$dea=$dear;





include"header.php";?>
<style>
.ter{
background-color:#fff;
padding:0 10px;
}
.check{
padding:2%;
font-size:12px;
width:25%;
}
.check span{

font-size:13px;
font-weight:700;

}
.img{
max-width:30%;
max-height:30%;
border-radius:50%;
}
.submitn{
  
  background: #FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 8px;
  font-size: 12px;
  font-weight: 500;
  outline:none;
  border:none;
 
}

.submitn:hover{
  background: #000000;
  color: #fff;
  outline:none;
  border:none;
}

.btn-buya {
  display: inline-block;
  padding:6px;
  border:none;
  color: #fff;
  font-size: 12px;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
  font-weight: 800;
  transition: 0.3s;
  background:#FEBF01;
  float:right;
  
}

</style>
 


<!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto; ">
        <div class="section-title" style="color:#FFFFFF;">
        </div>
<div class="row">
      <div class="col-lg-12 col-md-12">
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
 <p><form action="" method="post"><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th>TIME</th><th></th></thead>
	
<tbody>
 
<tr class="ter mx-3" >
	<td class="check">Available time</td>
	<td class="check" colspan="2"><i>click to choose time</i><span>
	<select name="team" class="form-control" required >
<?php
include "connect_to_mysqli.php";

date_default_timezone_set("Africa/Lagos");
// Fixed Values
$rent=date("Y-m-d"); // current date
$datar = date("H",strtotime('18:00:00')); //6pm
$dater = date("H",strtotime('08:00:00'));  //8am
$newtima=date('Y-m-d H:i:s');
$dato =  date("H", strtotime($newtima)); //current time

//Theory 1 
if(($dear == $rent ) && ($dato < $datar )) 
{

$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!=''  ORDER by s DESC LIMIT 1";
		$sql2 = mysqli_query($con,$sql);
	    if (mysqli_affected_rows($con) == 0)
	     {
        	if ($dater > $dato)
			{ $data = date("H:i",strtotime('08:00:00'));}
 	else
 	{	$newtime=date('Y-m-d H:i:s'); $data =  date("H:i", strtotime($newtime)); }
		    echo '<option value="'.$data.'">'.$data.'</option>';
			for($i=1; $i<=11; $i++)
{
   
    $repeat = strtotime("+30 minutes",strtotime($data));
    $data= date('H:i',$repeat);
   	echo '<option value="'.$data.'">'.$data.'</option>';
   	
}}






//Just one order
else
{
$sql = "SELECT all* from cart where date='$dear' && staff    ='$staff' && timef!=''   ORDER by s ASC";
$sql2 = mysqli_query($con,$sql);
if (mysqli_affected_rows($con) == 1)
{
while($row = mysqli_fetch_array($sql2))
{ 
$first=strtotime($row['timef']);
$end =strtotime($row['timet']);
}
$newtime=date('Y-m-d H:i:s');
$start=strtotime('08:00:00');
$stert=strtotime($newtime);
$minute=($first - $start) / 60 ;
if($minute > $timer )// free time before
{ 
$daw = date("H:i", $end);
$dawn = date("H:i", $first);
    		if ($dater > $dato)
			{$data = date("H:i",strtotime('08:00:00'));  }//8am 
			else
			{ $data =  date("H:i", strtotime($newtime));}
		     echo '<option value="'.$data.'">'.$data.'</option>'; 
for($i=1; $i<=11; $i++)
{  
    $repeat = strtotime("+30 minutes",strtotime($data));
    $data= date('H:i',$repeat);
     if ($dawn <= $data && $data <= $daw ) {echo "";}
     {echo '<option value="'.$data.'">'.$data.'</option>';}
}}
else // free time after
{
    if($end < $stert)
    {
    $data =  date("H:i", strtotime($newtime));
 	echo '<option value="'.$data.'">'.$data.'</option>';
			for($i=1; $i<=11; $i++)
     {   
    $repeat = strtotime("+30 minutes",strtotime($data));
    $data= date('H:i',$repeat);
   	echo '<option value="'.$data.'">'.$data.'</option>';
}}
else
{
	$data =  date("H:i", $end);
 	echo '<option value="'.$data.'">'.$data.'</option>';
			for($i=1; $i<=11; $i++)
{   
    $repeat = strtotime("+30 minutes",strtotime($data));
    $data= date('H:i',$repeat);
   	echo '<option value="'.$data.'">'.$data.'</option>';
}}}}





 
 
 
 
 
// Two or more order 
else
{ 
$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!='' ORDER by s ASC";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{  
$tmf= strtotime($row['timef']);
$star= strtotime($row['timet']);
}
$newtime=date('Y-m-d H:i:s');
$stare=strtotime($newtime);
				  
$dew = date("H:i", $tmf);
$dewn = date("H:i", $star);
$minutes=($tmf - $stare) / 60 ; 
if($minute > $timer )// free time before
{ 
if ($dater > $dato)
			{$data = date("H:i",strtotime('08:00:00'));  }//8am 
			else
			{ $data =  date("H:i", strtotime($newtime));}
		     echo '<option value="'.$data.'">'.$data.'</option>'; 
for($i=1; $i<=11; $i++)
{  
    $repeat = strtotime("+30 minutes",strtotime($data));
    $data= date('H:i',$repeat);
   if ($dewn <= $data && $data <= $dew ) {echo "";}
   else
  {echo '<option value="'.$data.'">'.$data.'</option>';}
}} 
else
{ 
//select end time of every order
$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!='' ORDER by s ASC";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2)){
$terf= strtotime($row['timet']);
if ($terf !="")
{
 //select start time of every order
$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!=''   ORDER by s ASC";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2)){
$ene = strtotime($row['timef']);
				  
    

$minutes = ($ene - $terf) / 60 ;   //Check when there is a free space
if($minutes > $timer )
{ 
$data =  date("H:i", $terf);
echo '<option value="'.$data.'">'.$data.'</option>'; 
			  	for($i=1; $i<=11; $i++)
{    
$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!='' ORDER by s DESC";
				$sql2 = mysqli_query($con,$sql);
				while($row = mysqli_fetch_array($sql2)){
				$ferst= strtotime($row['timef']);
				 $eder=strtotime($row['timef']);}
$diw = date("H:i", $eder);
$diwn =  date("H:i", $ferst);		 
		 
    
$repeat = strtotime("+30 minutes",strtotime($data));
$data= date('H:i',$repeat);
if ($diwn <= $data && $data <= $diw ) {echo "";}
else{ echo '<option value="'.$data.'">'.$data.'</option>';}}}

else if	($data == 0)			 
{
$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!=''  ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2)){
				 
		    	$data =  date("H:i", strtotime($row['timet']));
}
				echo '<option value="'.$data.'">'.$data.'</option>';
				for($i=1; $i<=11; $i++)
{  
    $repeat = strtotime("+30 minutes",strtotime($data));
    $data= date('H:i',$repeat);
   	echo '<option value="'.$data.'">'.$data.'</option>';}    
}				 
				 

}}}
}}}}

// Theory 1 ends







//Theory 2 
if($dear != $rent )
{

$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!=''  ORDER by s DESC LIMIT 1";
		$sql2 = mysqli_query($con,$sql);
	    if (mysqli_affected_rows($con) == 0)
			  {
				$data = date("H:i",strtotime('08:00:00'));
				echo '<option value="'.$data.'">'.$data.'</option>';
		     	for($i=1; $i<=11; $i++)
{
    $repeat = strtotime("+30 minutes",strtotime($data));
    $data= date('H:i',$repeat);
   	echo '<option value="'.$data.'">'.$data.'</option>';
}}
   


			





//Just one order
else
{
$sql = "SELECT all* from cart where date='$dear' && staff    ='$staff' && timef!=''   ORDER by s ASC";
$sql2 = mysqli_query($con,$sql);
if (mysqli_affected_rows($con) == 1)
{
while($row = mysqli_fetch_array($sql2))
{ 
$first=strtotime($row['timef']);
$end =strtotime($row['timet']);
}

$start=strtotime('08:00:00');
$minute=($first - $start) / 60 ;
if($minute > $timer )// free time before
{ 
$daw = date("H:i", $end);
$dawn = date("H:i", $first);

$data = date("H:i",strtotime('08:00:00'));   //8am 

echo '<option value="'.$data.'">'.$data.'</option>'; 
for($i=1; $i<=11; $i++)
{  
    $repeat = strtotime("+30 minutes",strtotime($data));
    $data= date('H:i',$repeat);
     if ($dawn <= $data && $data <= $daw ) {echo "";}
     {echo '<option value="'.$data.'">'.$data.'</option>';}
}}
else // free time after
{
   $data =  date("H:i", $end);
 	echo '<option value="'.$data.'">'.$data.'</option>';
			for($i=1; $i<=11; $i++)
     {   
    $repeat = strtotime("+30 minutes",strtotime($data));
    $data= date('H:i',$repeat);
   	echo '<option value="'.$data.'">'.$data.'</option>';
}}}


// Two or more order 
else
{ 
$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!='' ORDER by s ASC";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{  
$tmf= strtotime($row['timef']);
$star= strtotime($row['timet']);
}
$stare=strtotime('08:00:00');
				  
$dew = date("H:i", $tmf);
$dewn = date("H:i", $star);
$minutes=($tmf - $stare) / 60 ; 
if($minute > $timer )// free time before
{ 
$data = date("H:i",strtotime('08:00:00'));  //8am 

echo '<option value="'.$data.'">'.$data.'</option>'; 
for($i=1; $i<=11; $i++)
{  
    $repeat = strtotime("+30 minutes",strtotime($data));
    $data= date('H:i',$repeat);
   if ($dewn <= $data && $data <= $dew ) {echo "";}
   else
  {echo '<option value="'.$data.'">'.$data.'</option>';}
}}

else
{ 
//select end time of every order
$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!='' ORDER by s ASC";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2)){
$terf= strtotime($row['timet']);
if ($terf !="")
{
 //select start time of every order
$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!=''   ORDER by s ASC";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2)){
$ene = strtotime($row['timef']);
				  
    

$minutes = ($ene - $terf) / 60 ;   //Check when there is a free space
if($minutes > $timer )
{ 
$data =  date("H:i", $terf);
echo '<option value="'.$data.'">'.$data.'</option>'; 
			  	for($i=1; $i<=11; $i++)
{    
$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!='' ORDER by s DESC";
				$sql2 = mysqli_query($con,$sql);
				while($row = mysqli_fetch_array($sql2)){
				$ferst= strtotime($row['timef']);
				 $eder=strtotime($row['timef']);}
$diw = date("H:i", $eder);
$diwn =  date("H:i", $ferst);		 
		 
    
$repeat = strtotime("+30 minutes",strtotime($data));
$data= date('H:i',$repeat);
if ($diwn <= $data && $data <= $diw ) {echo "";}
else{ echo '<option value="'.$data.'">'.$data.'</option>';}}}

else if	($data == 0)			 
{
$sql = "SELECT all* from cart where date='$dear' && staff='$staff' && timef!=''  ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2)){
				 
		    	$data =  date("H:i", strtotime($row['timet']));
}
				echo '<option value="'.$data.'">'.$data.'</option>';
				for($i=1; $i<=11; $i++)
{  
    $repeat = strtotime("+30 minutes",strtotime($data));
    $data= date('H:i',$repeat);
   	echo '<option value="'.$data.'">'.$data.'</option>';}    
}				 
				 

}}}
}}}}

// Theory 2 ends



















//Theory 4 !!
if(($dear == $rent) &&( $dato >= $datar)) // if same day and time is between 6pm - 7:59am
{
  $baby='<p style="font-size:12px; color:red;">Sorry,booking for today is full,kindly choose the next day to enjoy our quality service,Thank you!</p>';
}






?>
    </select>
	</span></td>
	</tr>

	</tbody>
	</table></p>
	
	
		
			
			  
             <div class="btn-wrap" style="text-align:center;">
                 <?php echo $baby; ?>
                 <a href="baby.php#results"><button type="button" class="submitn">BACK</button></a><br /><br />
                  <button type="submit" name="submit" value="next" class="submitn">TO NEXT STEP  </button><br /><br />

			    <button type="submit" name="submit" value="addc" class="submitn">ADD MORE FROM THIS CATEGORY</button><br /><br />
               <button type="submit" name="submit" value="addm" class="submitn">ADD MORE FROM OTHER CATEGORY</button>
			  
			   </form></div>











<?php
if (isset($_POST['team'])){
// To Update Time

$team=$_POST['team'];
$sum=$_POST['submit'];
if ($team=="")
{
echo"";
}
else{
   

$newtime = strtotime($team) + ($timer * 60);
$tam= date('H:i', $newtime);
$insert = mysqli_query($con,"UPDATE cart SET timef='$team' where id='$ran' && service='$see' && date='$dea'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE cart SET timet='$tam' where id='$ran' && service='$see' && date='$dea'") or die ('Could not connect: ' .mysqli_error($con)); 


 			 

	
if ($sum=="next")
{
session_start();
$_SESSION['ider']=$ran;
$_SESSION['gift']=$gift;
header('Refresh:3; url=type.php');
echo '<p style="color:green;font-size:13px; text-align:center;">Service added Successfully<br><i style="color:black;">you are now being redirected</i></p>';
}

else if ($sum=="addc"){
session_start();
$_SESSION['ider']=$ran;
$_SESSION['more']=$gene;
header('Refresh:3; url=sub.php');
echo '<p style="color:green;font-size:13px; text-align:center;">Service added Successfully<br><i style="color:black;">you are now being redirected</i></p>';
}

else if ($sum=="addm"){
session_start();
$_SESSION['ider']=$ran;
header('Refresh:3; url=more.php');
echo '<p style="color:green;font-size:13px; text-align:center;">Service added Successfully<br><i style="color:black;">you are now being redirected</i></p>';
}

 }  }?>    
            </div>
          </div>



   
      </div>
    </section><!-- End Pricing Section -->
     </div>
   
  </main><!-- End #main -->

 <?php include"footer.php";  ?>