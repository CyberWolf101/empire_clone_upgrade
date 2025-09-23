<?php
// Ensure no output before this point
if (!isset($con)) {
  error_log("Database connection not established in headerContents.php");
  header("Location: index.php?error=" . urlencode("Database connection failed"));
  exit;
}

// Create menu_links table if it doesn't exist
$createMenuLinksQuery = "
    CREATE TABLE IF NOT EXISTS menu_links (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        url VARCHAR(255) NOT NULL,
        isEnabled TINYINT(1) DEFAULT 1,
        parent_id INT DEFAULT NULL,
        order_no INT DEFAULT 0,
        FOREIGN KEY (parent_id) REFERENCES menu_links(id) ON DELETE SET NULL
    ) ENGINE=InnoDB";
if (!mysqli_query($con, $createMenuLinksQuery)) {
  error_log("Failed to create menu_links table: " . mysqli_error($con));
  header("Location: index.php?error=" . urlencode("Error setting up menu_links table: " . mysqli_error($con)));
  exit;
}

// Check if menu_links is empty and populate with default links
$checkEmptyQuery = "SELECT COUNT(*) as count FROM menu_links";
$result = mysqli_query($con, $checkEmptyQuery);
if ($result) {
  if (mysqli_fetch_assoc($result)['count'] == 0) {
    $insertLinksQuery = "
            INSERT INTO menu_links (name, url, isEnabled, order_no) VALUES
            ('Delta Kitchen', 'deltakitchen.php', 1, 1),
            ('Orishirishi', 'food_page.php', 1, 2),
            ('CHB Luxury Academy', 'academy/index.php', 1, 3),
            ('Repair Center', 'repaircenter.php', 1, 4),
            ('E-Giftcard', '#', 1, 5),
            ('Become a Member', 'members/', 1, 6),
            ('Rental for Beauty and Skill Training', 'rental/index.php', 1, 7),
            ('Ram Suya Academy', '#', 1, 8),
            ('CHB Logistics', 'https://chblogistics.com', 1, 9),
            ('CHB Nailshop', 'https://chbluxuries.com', 1, 10),
            ('Oshofree', 'https://oshofree.ng', 1, 11),
            ('E-Gift Voucher Spa Packages', 'voucher/index.php', 1, 12),
            ('View Pre-Orders', 'viewpreorders.php', 1, 13);
            
            INSERT INTO menu_links (name, url, isEnabled, parent_id, order_no) 
            SELECT 'Buy a E-Giftcard', 'giftcard.php', 1, id, 1 FROM menu_links WHERE name = 'E-Giftcard';
            
            INSERT INTO menu_links (name, url, isEnabled, parent_id, order_no) 
            SELECT 'View/Download E-Giftcard History', 'giftcard_history.php', 1, id, 2 FROM menu_links WHERE name = 'E-Giftcard';
        ";
    if (!mysqli_multi_query($con, $insertLinksQuery)) {
      error_log("Failed to populate menu_links table: " . mysqli_error($con));
      header("Location: index.php?error=" . urlencode("Error populating menu_links table: " . mysqli_error($con)));
      exit;
    }
    // Clear result buffer for multi-query
    while (mysqli_next_result($con)) {
      ;
    }
  }
  mysqli_free_result($result);
} else {
  error_log("Error checking menu_links table: " . mysqli_error($con));
  header("Location: index.php?error=" . urlencode("Error checking menu_links table: " . mysqli_error($con)));
  exit;
}

// Membership expiration logic
$today = date("Y-m-d");
$sql = "SELECT * FROM members WHERE paystatus='paid' AND status='valid' AND end_date <= ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $today);
if (mysqli_stmt_execute($stmt)) {
  $sql2 = mysqli_stmt_get_result($stmt);
  while ($row = mysqli_fetch_array($sql2)) {
    $memberid = $row['cardno'];
    $type = $row["type"];
    $name = $row["name"];
    $email = $row["email"];
    $phone = $row["phone"];
    $startdate = $row["start_date"];
    $enddate = $row["end_date"];

    // Update member status
    $update_sql = "UPDATE members SET status='invalid' WHERE cardno = ?";
    $update_stmt = mysqli_prepare($con, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "s", $memberid);
    if (!mysqli_stmt_execute($update_stmt)) {
      error_log("Failed to update member $memberid: " . mysqli_error($con));
    }
    mysqli_stmt_close($update_stmt);

    // Send email notification
    $email_to = $email;
    $email_subject = "Membership Package Expired! - CHBLUXURYEMPIRE";
    $email_message = "
            <div style='background-color:#000000; color:#fff !important; padding:10px 20px;'>
                <p style='text-align:left;'>
                    <img src='http://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='60px;' style='margin-top:13px;'>
                    <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>Date: $today</font>
                </p>
                <h5>MEMBERSHIP DETAILS</h5>
                <p style='color:white;'>$name<br>$email<br>$phone<br>$type<br>Membership Expires: $enddate</p>
                <p style='color:white;'>Hello Dear Customer, your membership package ID $memberid which started on $startdate with a subscription of $type has expired today $today</p>
                <p style='color:#fff; font-size:13px;'>Thank you for your patronage and we hope to see you again soon!</p> 
                <br><br>
                <p style='text-align:center;'><a href='http://chbluxuryempire.com' style='color:#FFC700;'>CHBLUXURYEMPIRE</a></p>
            </div>";

    $header = 'From: "CHBLUXURYEMPIRE" <noreply@chbluxuryempire.com>' . "\r\n";
    $header .= "Reply-To: noreply@chbluxuryempire.com\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html; charset=UTF-8\r\n";

    if (!mail($email_to, $email_subject, $email_message, $header)) {
      error_log("Failed to send email to $email");
    }
  }
  mysqli_free_result($sql2);
  mysqli_stmt_close($stmt);
} else {
  error_log("Failed to fetch members: " . mysqli_error($con));
}

// Saloon cookie check
if (isset($_COOKIE['foodID'])) {
  $saloon = mysqli_real_escape_string($con, $_COOKIE['foodID']);
  $sql = "SELECT * FROM saloon_orders WHERE id = ?";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "s", $saloon);
  if (mysqli_stmt_execute($stmt)) {
    $sql2 = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($sql2);
    $type = isset($row["bookingtype"]) && $row["bookingtype"] !== '' ? (int) $row["bookingtype"] : 0;
    $kit = $row["saloonkit"] ?? null;
    $username = mysqli_real_escape_string($con, $_SESSION['username'] ?? '');
    $c_phone = mysqli_real_escape_string($con, $_SESSION['phone'] ?? '');
    $c_email = mysqli_real_escape_string($con, $_SESSION['email'] ?? '');
    $status = $row["status"] ?? null;
    mysqli_free_result($sql2);

    // Refreshments total
    $sam = "SELECT SUM(totalprice) as total FROM refreshments WHERE orderid = ?";
    $sam_stmt = mysqli_prepare($con, $sam);
    mysqli_stmt_bind_param($sam_stmt, "s", $saloon);
    if (mysqli_stmt_execute($sam_stmt)) {
      $sam2 = mysqli_stmt_get_result($sam_stmt);
      $row = mysqli_fetch_array($sam2);
      $total_all = isset($row['total']) ? (int) $row['total'] : 0;
      mysqli_free_result($sam2);

      // Update saloon order total
      $update_sql = "UPDATE saloon_orders SET total_amount = ? WHERE id = ?";
      $update_stmt = mysqli_prepare($con, $update_sql);
      mysqli_stmt_bind_param($update_stmt, "is", $total_all, $saloon);
      if (!mysqli_stmt_execute($update_stmt)) {
        error_log("Failed to update saloon order $saloon: " . mysqli_error($con));
      }
      mysqli_stmt_close($update_stmt);
    } else {
      error_log("Failed to fetch refreshments total: " . mysqli_error($con));
    }
    mysqli_stmt_close($sam_stmt);
  } else {
    error_log("Failed to fetch saloon order $saloon: " . mysqli_error($con));
  }
  mysqli_stmt_close($stmt);
}

// Site settings
$sql = "SELECT * FROM site_settings";
$result = mysqli_query($con, $sql);
if ($result) {
  $row = mysqli_fetch_array($result);
  $apikey = $row["apikey"];
  $sitemail = $row["sitemail"];
  $sitename = $row["sitename"];
  $siteimg = $row["site_img"];
  $kitprice = $row["pedicurekit"];
  $rentprice = $row["rental"];
  $late_fee = $row["latefee"];
  $walkinIncrease = $row["walk_in_fee"];
  mysqli_free_result($result);
} else {
  error_log("Failed to fetch site settings: " . mysqli_error($con));
}
?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>CHBLUXURYEMPIRE</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/luxury/logo_luxury.png" rel="apple-touch-icon">
  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/index.css">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
  <header class="header">
    <div class="d-flex justify-content-center" style="height:80vh;">
      <div class="menu" style="position: absolute; top: 10%; left: 50%; cursor: pointer;">
        <i class='bx bx-x-circle' style="color: white;"></i>
      </div>
      <div style="position: absolute; top: 20%; height: 80vh;" class="soft">
        <ul style="list-style:none; font-weight:600;">
          <style>
            .menu_links {
              color: #FFC700;
              text-transform: uppercase !important;
            }

            .menu_links:hover {
              color: #fff;
              text-transform: uppercase !important;
            }

            #list,
            #secondlist {
              display: none;
            }

            #list li,
            #secondlist li {
              margin-bottom: 0;
              line-height: 0.2 !important;
              font-family: 'Poppins';
              text-transform: capitalize !important;
            }

            @import url("https://fonts.googleapis.com/css?family=Amatic+SC");

            .btn-anim {
              border: none;
              display: block;
              text-align: center;
              cursor: pointer;
              text-transform: uppercase;
              outline: none;
              overflow: hidden;
              position: relative;
              color: #fff;
              font-weight: 400;
              font-size: 15px;
              background-color: #ffc700;
              padding: 17px 60px;
              border-radius: 5px;
              margin: 0 auto;
              box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            .btn-anim span {
              position: relative;
              z-index: 1;
            }

            .btn-anim:after {
              content: "";
              position: absolute;
              left: 0;
              top: 0;
              height: 490%;
              width: 140%;
              background: #000;
              -webkit-transition: all 0.5s ease-in-out 100ms;
              transition: all 0.5s ease-in-out 100ms;
              -webkit-transform: translateX(-110%) translateY(-25%) rotate(45deg);
              transform: translateX(-110%) translateY(-25%) rotate(45deg);
            }

            .btn-anim:before {
              content: "";
              position: absolute;
              left: 0;
              top: 0;
              height: 490%;
              width: 140%;
              background: #fff;
              -webkit-transition: all 0.5s ease-in-out;
              transition: all 0.5s ease-in-out;
              -webkit-transform: translateX(-110%) translateY(-25%) rotate(45deg);
              transform: translateX(-110%) translateY(-25%) rotate(45deg);
            }

            .btn-anim:hover:before,
            .btn-anim:hover:after {
              -webkit-transform: translateX(-9%) translateY(-25%) rotate(45deg);
              transform: translateX(-9%) translateY(-25%) rotate(45deg);
              color: #fff;
            }

            .link {
              font-size: 20px;
              margin-top: 30px;
            }

            .link a {
              color: #000;
              font-size: 25px;
            }

            .grid2 {
              display: grid;
              grid-template-columns: 1fr 1fr;
            }


            .ter {
              background-color: #fff;
              padding: 0 10px;
            }

            .check {
              padding: 2%;
              font-size: 12px;
              width: 25%;
            }

            .check span {
              font-size: 13px;
              font-weight: 600;
            }

            .btn-buya {
              display: inline-block;
              padding: 6px !important;
              border: none;
              color: #fff;
              font-size: 10px !important;
              text-transform: uppercase;
              font-family: "Poppins", sans-serif;
              font-weight: 600;
              transition: 0.3s;
              background: #FEBF01;
              margin: 4px;
            }

            .btn-buya:hover {
              font-size: 12px !important;
              font-weight: 800;
              background: #000;
            }

            .form-control {
              height: 40px;
              border-radius: none !important;
            }

            .section-title h2::after {
              content: "";
              position: absolute;
              display: block;
              width: 80px;
              background: none;
              bottom: 0;
              left: calc(2% - 25px);
            }

            .box {
              border-radius: 0px;
            }

            .pricing .box {
              padding: 20px 0 0;
              background: #f8f8f8;
              text-align: center;
              box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.12);
              border-radius: 0px;
              position: relative;
              overflow: hidden;
            }

            .nav-tabs .nav-link.active {
              background-color: #FEBF01;
              color: #fff;
            }

            .nav-tabs .nav-link {
              color: #000;
            }

            /* Search Bar and Suggestions Styles */
            .search-container {
              position: relative;
              margin-bottom: 20px;
              width: 100%;
              max-width: 500px;
              display: flex;
              align-items: center;
              gap: 10px;
            }

            .search-input {
              flex-grow: 1;
              padding: 10px;
              font-size: 14px;
              border: 2px solid #FEBF01;
              border-radius: 5px;
              outline: none;
            }

            .search-suggestions {
              position: absolute;
              top: 100%;
              left: 0;
              right: 0;
              background: #fff;
              border: 1px solid #ddd;
              border-radius: 5px;
              max-height: 200px;
              overflow-y: auto;
              z-index: 1000;
              display: none;
            }

            .search-suggestions div {
              padding: 10px;
              cursor: pointer;
              border-bottom: 1px solid #eee;
            }

            .search-suggestions div:hover {
              background: #f0f0f0;
            }

            .search-suggestions div:last-child {
              border-bottom: none;
            }
          </style>
          <script>
            function myFunctionlist() {
              var myLists = document.getElementById('list');
              var displaySettings = myLists.style.display;
              myLists.style.display = displaySettings == 'block' ? 'none' : 'block';
            }
            function myFunctionlists() {
              var myLists = document.getElementById('secondlist');
              var displaySettings = myLists.style.display;
              myLists.style.display = displaySettings == 'block' ? 'none' : 'block';
            }
          </script>
          <!-- Salon and Spa Dropdown -->
          <li><a href="#" onclick="myFunctionlist()" class="menu_links">Salon and Spa</a></li><br>
          <div id="list">
            <?php
            $sql = "SELECT * FROM category ORDER BY order_no ASC";
            $stmt = mysqli_prepare($con, $sql);
            if (mysqli_stmt_execute($stmt)) {
              $sql2 = mysqli_stmt_get_result($stmt);
              while ($row = mysqli_fetch_array($sql2)) {
                if ($row['isEnabled']) {
                  echo '<li><a href="saloon/subcategory.php?category=' . $row['id'] . '" style="background:none; outline:none; border:none; color: #FFC700; font-size:12px; text-transform:capitalize; font-weight:500;">' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</a></li><br>';
                }
              }
              mysqli_free_result($sql2);
            }
            mysqli_stmt_close($stmt);
            ?>
            <li><a href="voucher/index.php"
                style="background:none; outline:none; border:none; color: #FFC700; font-size:12px; text-transform:capitalize; font-weight:500;">E-GIFT
                SPA PACKAGE</a></li><br>
          </div>
          <!-- Dynamic Menu Links -->
          <?php
          $sql = "SELECT * FROM menu_links WHERE parent_id IS NULL ORDER BY order_no ASC";
          $stmt = mysqli_prepare($con, $sql);
          if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_array($result)) {
              if ($row['isEnabled'] && !($row['url'] == 'viewpreorders.php' && (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']))) {
                $link_id = $row['id'];
                $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                $url = htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8');
                if ($url == '#') {
                  if ($name == 'E-Giftcard') {
                    echo '<li><a href="#" onclick="myFunctionlists()" class="menu_links">' . $name . '</a></li><br>';
                    echo '<div id="secondlist">';
                    $sub_sql = "SELECT * FROM menu_links WHERE parent_id = ? AND isEnabled = 1 ORDER BY order_no ASC";
                    $sub_stmt = mysqli_prepare($con, $sub_sql);
                    mysqli_stmt_bind_param($sub_stmt, "i", $link_id);
                    if (mysqli_stmt_execute($sub_stmt)) {
                      $sub_result = mysqli_stmt_get_result($sub_stmt);
                      while ($sub_row = mysqli_fetch_array($sub_result)) {
                        echo '<li><a href="' . htmlspecialchars($sub_row['url'], ENT_QUOTES, 'UTF-8') . '" style="background:none; outline:none; border:none; color: #FFC700; font-size:12px; text-transform:capitalize; font-weight:600;">' . htmlspecialchars($sub_row['name'], ENT_QUOTES, 'UTF-8') . '</a></li><br>';
                      }
                      mysqli_free_result($sub_result);
                    }
                    mysqli_stmt_close($sub_stmt);
                    echo '</div>';
                  } else {
                    echo '<li><a href="#" class="menu_links">' . $name . '</a></li><br>';
                  }
                } else {
                  echo '<li><a href="' . $url . '" class="menu_links">' . $name . '</a></li><br>';
                }
              }
            }
            mysqli_free_result($result);
          } else {
            error_log("Error fetching menu links: " . mysqli_error($con));
            echo "<li>Error fetching menu links: " . htmlspecialchars(mysqli_error($con), ENT_QUOTES, 'UTF-8') . "</li>";
          }
          mysqli_stmt_close($stmt);
          ?>
        </ul>
      </div>
    </div>
  </header>