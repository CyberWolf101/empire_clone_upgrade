
//meal handle

    $selectedMeals = [];
    $selectedMealQuantities = [];

    if (isset($_POST['selected_meal']) && is_array($_POST['selected_meal'])) {
        foreach ($_POST['selected_meal'] as $key => $mealID) {
            if (!empty($mealID)) {
                $selectedMeals[] = $mealID;
                $selectedMealQuantities[] = $_POST['mealquantity'][$key];
            }
        }
    }



    foreach ($selectedMeals as $index => $mealID) {
        $mealQuantity = $selectedMealQuantities[$index];
    
    
$sqk = "SELECT * from delta_meals where id='$index'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
{
$mealname = $rowe['name'];
$mealprice = $rowe['price'];
}
  
$mealtotal=$mealQuantity*$mealprice;

$bot = "SELECT * from delta_cart where id='$saloon' AND itemno='$index'";
$bot2 = mysqli_query($con,$bot);
if (mysqli_affected_rows($con) == 0){
$submit = mysqli_query($con,"insert into delta_cart(`id`,`itemno`, `itemname`, `unitprice`, `quantity`, `totalprice`, `status`) values 
('$saloon','$index','$mealname','$mealprice','$mealQuantity','$mealtotal','processing')") or die ('Could not connect: ' .mysqli_error($con));
}

else{
    
 $newquantity="";      
 $totalvalue=""; 
  
$sqk = "SELECT * from delta_cart where id='$saloon' AND itemno='$index";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp)){
$quantity = $rowe['quantity'];
$rowfood = $rowe['s'];
}


$newquantity=$quantity+$mealQuantity;
$totalvalue=$newquantity*$mealprice;
    
$insert = mysqli_query($con,"UPDATE delta_cart SET totalprice= '$totalvalue' where s='$rowfood'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE delta_cart SET unitprice= '$mealprice' where s='$rowfood'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE delta_cart SET quantity= '$newquantity' where s='$rowfood'") or die ('Could not connect: ' .mysqli_error($con));         
    
}
}




//protein handle
if (isset($_POST['protein']) && isset($_POST['proteinquantity'])) {
    $selectedProtein = $_POST['protein'];
    $selectedProteinQuantities = $_POST['proteinquantity'];

    // Now, $selectedProtein contains the checked protein values, and
    // $selectedProteinQuantities contains their corresponding quantities.

    // You can loop through these arrays to process the selected items and their quantities.
    for ($i = 0; $i < count($selectedProtein); $i++) {
        $proteinID = $selectedProtein[$i];
        $proteinValue = $selectedProteinQuantities[$i];

        // Fetch information about the selected protein from your database.
    $sqk = "SELECT * FROM delta_protein WHERE id = '$proteinID'";
    $sqlp = mysqli_query($con, $sqk);

    if ($sqlp) {
        while ($rowe = mysqli_fetch_array($sqlp)) {
            $proteinname = $rowe['name'];
            $proteinprice = $rowe['price'];
        }

        $bot = "SELECT * FROM delta_cart WHERE id='$saloon' AND itemno='$proteinID'";
        $bot2 = mysqli_query($con, $bot);

        if (mysqli_affected_rows($con) == 0) {
            $proteintotal = $proteinValue * $proteinprice;

            $submit = mysqli_query($con, "INSERT INTO delta_cart(`id`, `itemno`, `itemname`, `unitprice`, `quantity`, `totalprice`, `status`) VALUES ('$saloon','$proteinID','$proteinname','$proteinprice','$proteinValue','$proteintotal','processing')") or die ('Could not connect: ' . mysqli_error($con));
        } else {
            $newquantity = "";
            $totalvalue = "";

            $sqk = "SELECT * FROM delta_cart WHERE id='$saloon' AND itemno='$proteinID'";
            $sqlp = mysqli_query($con, $sqk);
            
            while ($rowe = mysqli_fetch_array($sqlp)) {
                $quantity = $rowe['quantity'];
                $rowfood = $rowe['s'];
            }

            $newquantity = $quantity + $proteinValue;
            $totalvalue = $newquantity * $proteinprice;

            $insert = mysqli_query($con, "UPDATE delta_cart SET totalprice= '$totalvalue' WHERE s='$rowfood'") or die ('Could not connect: ' . mysqli_error($con));
            $insert = mysqli_query($con, "UPDATE delta_cart SET unitprice= '$proteinprice' WHERE s='$rowfood'") or die ('Could not connect: ' . mysqli_error($con));
            $insert = mysqli_query($con, "UPDATE delta_cart SET quantity= '$newquantity' WHERE s='$rowfood'") or die ('Could not connect: ' . mysqli_error($con));
        }
    }
}}
