<?php

if (isset($_POST['pay'])) {

    $payments = $_POST['payment'] ?? [];
    $totalEntered = 0;

    foreach ($payments as $method => $data) {
        if (!empty($data['enabled']) && is_numeric($data['amount'])) {
            $totalEntered += (float) $data['amount'];
        }
    }

    if ($totalEntered != $total_all) {
        echo "<script>alert('Error: Payment amounts must equal Grand Total (₦$total_all). You entered ₦$totalEntered');</script>";
    } else {
        $customertype = $_POST['customertype'];
        $customername = !empty($_POST['customername']) ? $_POST['customername'] : "nil";
        $customerphone = !empty($_POST['customerphone']) ? $_POST['customerphone'] : "nil";
        $customermail = !empty($_POST['customermail']) ? $_POST['customermail'] : "nil";
        $customer_id = !empty($_POST['customer']) ? $_POST['customer'] : "nil";
        // $method = $_POST['method'];  //**** fomer */
        $method = isset($_POST['method'])
            ? (is_array($_POST['method']) ? implode(", ", $_POST['method']) : $_POST['method'])
            : "nil";

        $datetime = date('Y-m-d H:i:s');

        

     

        if ($customertype == "old") {
            $sql = "SELECT * FROM saloon_orders WHERE name='$customer_id'";
            $sql2 = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_array($sql2)) {
                $customername = $row['name'];
                $customerphone = $row['phone'];
                $customermail = $row['email'];
            }
        }


        $insert = mysqli_query($con, "UPDATE saloon_orders SET pay_status='paid' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
        $insert = mysqli_query($con, "UPDATE saloon_orders SET status='completed' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
        $insert = mysqli_query($con, "UPDATE refreshments SET status='processed' where orderid='$saloon'") or die('Could not connect: ' . mysqli_error($con));
        $insert = mysqli_query($con, "UPDATE saloon_orders SET name='$customername' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
        $insert = mysqli_query($con, "UPDATE saloon_orders SET email='$customermail' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
        $insert = mysqli_query($con, "UPDATE saloon_orders SET phone='$customerphone' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));

        // ***** FORMER CODE ***
        // if ($method == "pos") {
        //     $insert = mysqli_query($con, "UPDATE saloon_orders SET pos_amount='$total_all' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
        //     $insert = mysqli_query($con, "UPDATE saloon_orders SET method='POS' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
        // } else if ($method == "cash") {
        //     $insert = mysqli_query($con, "UPDATE saloon_orders SET cash_amount='$total_all' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
        //     $insert = mysqli_query($con, "UPDATE saloon_orders SET method='Cash' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
        // } else if ($method == "transfer") {
        //     $insert = mysqli_query($con, "UPDATE saloon_orders SET transfer_amount='$total_all' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
        //     $insert = mysqli_query($con, "UPDATE saloon_orders SET method='Bank Transfer' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
        // }

        // Example: ["pos" => 2000, "cash" => 1000]
        $payments = $_POST['payment'] ?? [];
        $posAmount = 0;
        $cashAmount = 0;
        $transferAmount = 0;

        // Loop over payment methods
        foreach ($payments as $method => $data) {
            if (!empty($data['enabled']) && is_numeric($data['amount'])) {
                switch ($method) {
                    case "pos":
                        $posAmount = (float) $data['amount'];
                        break;
                    case "cash":
                        $cashAmount = (float) $data['amount'];
                        break;
                    case "transfer":
                        $transferAmount = (float) $data['amount'];
                        break;
                }
            }
        }

        // Concatenate method names (POS, Cash, etc.)
        $methodList = [];
        if ($posAmount > 0)
            $methodList[] = "POS";
        if ($cashAmount > 0)
            $methodList[] = "Cash";
        if ($transferAmount > 0)
            $methodList[] = "Bank Transfer";
        $methodString = implode(", ", $methodList);


        // After calculating values
        // echo "<pre>";
        // echo "POS Amount: " . $posAmount . "\n";
        // echo "Cash Amount: " . $cashAmount . "\n";
        // echo "Transfer Amount: " . $transferAmount . "\n";
        // echo "Method String: " . $methodString . "\n";
        // echo "Grand Total Entered: " . $totalEntered . "\n";
        // echo "customertype: " . $customertype . "\n";
        // echo "name: " . $customername . "\n";
        // echo "</pre>";

        // exit;

        // Save into DB
        mysqli_query($con, "UPDATE saloon_orders 
     SET pos_amount='$posAmount', 
        cash_amount='$cashAmount', 
        transfer_amount='$transferAmount',
        method='$methodString',
        pay_status='paid'
     WHERE id='$saloon'") or die(mysqli_error($con));

        //update stocks
        $sqk = "SELECT * from refreshments where orderid='$saloon'";
        $sqlp = mysqli_query($con, $sqk);
        while ($rowe = mysqli_fetch_array($sqlp)) {
            $food = $rowe['itemid'];
            $value = $rowe['quantity'];


            $sql = "SELECT * from food_menu where s='$food'";
            $sql = mysqli_query($con, $sql);
            while ($rowe = mysqli_fetch_array($sql2)) {
                $origianlvalue = $rowe['quantity'];
            }

            $rem_value = $originalvalue - $value;
            $insert = mysqli_query($con, "UPDATE food_menu SET quantity='$rem_value' where s='$food'") or die('Could not connect: ' . mysqli_error($con));
            $submit = mysqli_query($con, "insert into stock_log(id,action,value,date) values ('$food','minus','$value','$datetime')") or die('Could not connect: ' . mysqli_error($con));
        }



        $sql = "SELECT * from refreshments where orderid='$saloon'";
        $sql2 = mysqli_query($con, $sql);

        // Check if there are rows in the result set
        if (mysqli_num_rows($sql2) > 0) {
            $name = [];
            $surname = [];
            $address = [];

            while ($row = mysqli_fetch_array($sql2)) {
                $name[] = $row['item'];
                $surname[] = $row['quantity'];
                $address[] = $row['totalprice'];
            }

            $html = "<p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#fff;' width='500px'>
     <tr style='border-bottom:#FFFFFF solid;'><td>OrderID</td><td style='color:#FFC700;' colspan='4' >$saloon</td></tr>
     <tr><td style='color:#fff; text-align:center;'>Your Items</td><td>Quantity</td><td>Price</td></tr>";

            foreach ($name as $key => $value) {
                $html .= "<tr>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . $name[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . $surname[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $address[$key] . "</td>
        </tr>";
            }

            $html .= "</table></p>";
        } else {
            // No rows found, you can display a message here if needed
            $html = "";
        }




        //send mail if email exists  
        if ($customermail != "") {


            ///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
            $comments = $email_to = $email_subject = $email_from = $msg = "";
            $email_from = $sitemail;
            $email_to = $customermail;
            $email_subject = "Items Purchased Successfully! - $sitename";
            $email_message = "
	 <center><div style='background-color:#000000; color:#fff !important; padding:10px 20px; width:500px;'>
	 <p style='text-align:left;'>
	 <img src='http://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='60px;' style='margin-top:13px;'>
	 <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>Total Cost: &#8358;$total_all <br>$date</font></p>
	
	
	
	
	<p style='color:#fff; font-size:13px;'>Hey there, $customername,You have successfully paid for your items ,attached below is your reciept details</p>
    $html
	
	<p style='color:#fff; font-size:13px;'>Thank you for your patronage and we hope to see you again soon!</p> 

 

 
 
<br><br>
<p style='text-align:center;'><a href='chbluxuryempire.com' style='color:#FFC700;'>$sitename</a></p>
</div></center>";



            // create email headers
            $header = 'From: "' . $sitename . '" <' . $sitemail . '>' . "\r\n";
            $header .= "Cc: $sitemail \r\n";
            $header .= 'Reply-To: ' . $sitemail . '' . "\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";

            if (!@mail($email_to, $email_subject, $email_message, $header)) {
                echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';
            }
            ////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////  
        }


        // Unset the session variable
        unset($_SESSION["order"]);

        // Clear the cookie
        if (isset($_COOKIE['orderID'])) {
            setcookie("orderID", '', time() - 3600, '/');
        }

        // Perform the redirect
        header("location: foodreciept.php?order=$saloon");
        exit(); // Terminate the script

    }
} ?>