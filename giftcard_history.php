<?php include "header.php";  ?>





<style>
.btn-buya {
  display: inline-block;
  padding:6px !important;
  border:none;
  color: #fff;
  font-size: 12px !important;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
  font-weight: 700;
  transition: 0.3s;
  background:#FEBF01;
  width:200px;
  
}


.btn-buya:hover {
  display: inline-block;
  padding:6px !important;
  border:none;
  color: #fff;
  font-size: 12px !important;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
  font-weight: 800;
  transition: 0.3s;
  background:#FEBF01;
  width:200px;
  
}
</style>





<div style="margin-top:100px; color:#FFFFFF;">
<div class="justify-content-center" align="center">
<form action="downloadhistory.php" method="POST">
<p><b>DOWNLOAD/VIEW E-GIFTCARD HISTORY</b></p>
<p>Get all transaction history on your e-giftcard here</p>
<div class="col-lg-4">
<p><input type="text" class="form-control" id="giftcardInput" name="giftcard" placeholder="Enter Giftcard Serial Number here"  required /></p>
</div>
<div class="col-lg-12"><p> <button type="submit" name="submitdetails" value="1" class="btn-buya">DOWNLOAD</button></form></p>
<form action="" method="get">
<input type="text" class="form-control" id="displayGiftcard" name="giftcards" placeholder="Enter Giftcard Serial Number here"  required hidden />
<p><button type="submit" name="getdetails" value="1" class="btn-buya">VIEW HISTORY</button></p>
<p><a href="https://chbluxuryempire.com" class="btn-buya">BACK TO HOME</a></p></div>
</form>


<script>
    // Get references to the input fields
    const giftcardInput = document.getElementById('giftcardInput');
    const displayGiftcard = document.getElementById('displayGiftcard');

    // Add an input event listener to the giftcardInput field
    giftcardInput.addEventListener('input', function () {
        // Update the value of the displayGiftcard field with the typed text
        displayGiftcard.value = giftcardInput.value;
    });
</script>

<style>
/* CSS for the table */
table {
    border-collapse: collapse;
    width: 100%;
    font-size:14px;
}

/* Table header styles */
table th {
    background-color: #f2f2f2;
    color: #333;
    font-weight: bold;
    padding: 10px;
    text-align: left;
    border: 1px solid #ccc;
}

/* Table row styles */
table tr {
    background-color: #fff;
    color: #333;
}

/* Table row hover effect */
table tr:hover {
    background-color: #f5f5f5;
}

/* Table cell styles */
table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ccc;
}

/* Optional: Add alternating row colors */
table tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Optional: Add a border to the table */
table {
    border: 1px solid #ccc;
}

/* Optional: Style the first column differently (e.g., for ID) */
table td:first-child {
    font-weight: bold;
}

</style>
<p style="color:#fff;">
<?php 
if(isset($_GET["getdetails"])) {
    $giftcard = $_GET['giftcards'];


        $sql = "SELECT * FROM giftcard_history WHERE giftcardno ='$giftcard' AND status='processed'";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            echo'<table border="1" cellspacing="3">
        <tr>
            <th>Amount Deducted</th>
            <th>Amount Left</th>
            <th>Transaction ID</th>
            <th>Date</th>
            <th>Description</th>
        </tr>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>₦" . $row["amount_deducted"] . "</td>";
                echo "<td>₦" . $row["amount_left"] . "</td>";
                echo "<td>" . $row["orderid"] . "</td>";
                echo "<td>" . $row["date"] . "</td>";
                 echo "<td>" . $row["description"] . "</td>";
                echo "</tr>";
            }
            echo" </table>";
        } else {
             echo "<script>alert('No Transactions Found!'); window.location.href = 'giftcard_history.php';</script>";
        }


}
?>
</p>
</div>




















<?php include "footer.php"; ?>