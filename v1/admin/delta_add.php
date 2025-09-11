<?php 
include "connect_to_mysqli.php";
if(isset($_POST['far'])){

$force=$_POST['far'];
$tim=0;
$mob=$_POST['ph'];
$cat=$_POST['cater'];





$extract_user = mysqli_query($con,"SELECT * FROM baby WHERE name='$force'");
$count = mysqli_num_rows($extract_user);
if ($count > 0) {
echo "This meal already exists on the menu";
}else
{


$sql = "SELECT all* from baby";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
 $id = $row["s"];
 $n =$row["id"]; 
}

$ran = $n + 01;	

$targetDir ="../food/";
$fileName = basename($_FILES["product"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

 if(isset($_POST["submit"]) && !empty($_FILES["product"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
     // Upload file to server
     if(move_uploaded_file($_FILES["product"]["tmp_name"], $targetFilePath)){
     // Insert image file name into database
     $insert = mysqli_query($con,"INSERT into images (file_name, uploaded_on) VALUES ('".$fileName."', NOW())");
     
     //Submit to images table
$submit = mysqli_query($con,"insert into delta_images(id,image) values ('$ran','".$fileName."')") or die ('Could not connect: ' .mysqli_error($con));
 }}}

 foreach($cat as $i){
        $cat=$i;
$submit = mysqli_query($con,"insert into baby(id, gen, name, price, time) values ('$ran','$cat','$force','$mob','$tim')") or die ('Could not connect: ' .mysqli_error($con));

}
echo '<p style="color:blue;">Meal added to menu successfully</p>';
header("refresh:2;url=delta_addmeal.php"); 
}}					
?>