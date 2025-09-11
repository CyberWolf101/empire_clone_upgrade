<?php
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
    echo "<script>alert('Error setting up menu_links table: " . addslashes(mysqli_error($con)) . "');</script>";
}

// Check if menu_links is empty and populate with default links
$checkEmptyQuery = "SELECT COUNT(*) as count FROM menu_links";
$result = mysqli_query($con, $checkEmptyQuery);
if ($result && mysqli_fetch_assoc($result)['count'] == 0) {
    $insertLinksQuery = "
        INSERT INTO menu_links (name, url, isEnabled, order_no) VALUES
        ('Delta Kitchen', 'deltakitchen.php', 1, 1),
        ('Orishirishi', 'food_page.php', 1, 2),
        ('CHB Luxury Academy', 'academy/index.php', 1, 3),
        ('Repair Center', 'repaircenter.php', 1, 4),
        ('E-Giftcard', '#', 1, 5),
        ('Become a Member', 'members/', 1, 6),
        ('Rental Services', 'rental/', 1, 7),
        ('Ram Suya Academy', '#', 1, 8),
        ('CHB Logistics', 'https://chblogistics.com', 1, 9),
        ('CHB Nailshop', 'https://chbluxuries.com', 1, 10),
        ('Oshofree', 'https://oshofree.ng', 1, 11);
        
        INSERT INTO menu_links (name, url, isEnabled, parent_id, order_no) 
        SELECT 'Buy a E-Giftcard', 'giftcard.php', 1, id, 1 FROM menu_links WHERE name = 'E-Giftcard';
        
        INSERT INTO menu_links (name, url, isEnabled, parent_id, order_no) 
        SELECT 'View/Download E-Giftcard History', 'giftcard_history.php', 1, id, 2 FROM menu_links WHERE name = 'E-Giftcard';
    ";
    if (!mysqli_multi_query($con, $insertLinksQuery)) {
        error_log("Failed to populate menu_links table: " . mysqli_error($con));
        echo "<script>alert('Error populating menu_links table: " . addslashes(mysqli_error($con)) . "');</script>";
    }
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
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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

  <!-- jQuery (single include) -->
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

            #list {
              display: none;
            }

            #list li {
              margin-bottom: 0;
              line-height: 0.2 !important;
              font-family: 'Poppins';
            }

            #secondlist {
              display: none;
            }

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
          </style>

          <script>
            function myFunctionlist() {
              var myLists = document.getElementById('list');
              var displaySettings = myLists.style.display;
              if (displaySettings == 'block') {
                myLists.style.display = 'none';
              } else {
                myLists.style.display = 'block';
              }
            }

            function myFunctionlists() {
              var myLists = document.getElementById('secondlist');
              var displaySettings = myLists.style.display;
              if (displaySettings == 'block') {
                myLists.style.display = 'none';
              } else {
                myLists.style.display = 'block';
              }
            }
          </script>

          <!-- Salon and Spa Dropdown -->
          <li><a href="#" onclick="myFunctionlist()" class="menu_links">Salon and Spa</a></li><br>
          <div id="list">
            <?php
            $sql = "SELECT * FROM category ORDER BY order_no ASC";
            $sql2 = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_array($sql2)) {
              if ($row['isEnabled']) {
                echo '<li><a href="saloon/subcategory.php?category=' . $row['id'] . '" style="background:none; outline:none; border:none; color: #FFC700; font-size:12px; text-transform:capitalize; font-weight:500;">' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</a></li><br>';
              }
            }
            ?>
            <li><a href="voucher/" style="background:none; outline:none; border:none; color: #FFC700; font-size:12px; text-transform:capitalize; font-weight:500;">E-GIFT SPA PACKAGE</a></li><br>
          </div>

          <!-- Dynamic Menu Links -->
          <?php
          $sql = "SELECT * FROM menu_links WHERE parent_id IS NULL ORDER BY order_no ASC";
          $result = mysqli_query($con, $sql);
          if ($result) {
            while ($row = mysqli_fetch_array($result)) {
              if ($row['isEnabled']) {
                $link_id = $row['id'];
                $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                $url = htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8');
                if ($url == '#') {
                  // Dropdown for E-Giftcard
                  if ($name == 'E-Giftcard') {
                    echo '<li><a href="#" onclick="myFunctionlists()" class="menu_links">' . $name . '</a></li><br>';
                    echo '<div id="secondlist">';
                    $sub_sql = "SELECT * FROM menu_links WHERE parent_id = $link_id AND isEnabled = 1 ORDER BY order_no ASC";
                    $sub_result = mysqli_query($con, $sub_sql);
                    while ($sub_row = mysqli_fetch_array($sub_result)) {
                      echo '<li><a href="' . htmlspecialchars($sub_row['url'], ENT_QUOTES, 'UTF-8') . '" style="background:none; outline:none; border:none; color: #FFC700; font-size:12px; text-transform:capitalize; font-weight:600;">' . htmlspecialchars($sub_row['name'], ENT_QUOTES, 'UTF-8') . '</a></li><br>';
                    }
                    echo '</div>';
                  } else {
                    // Placeholder links (e.g., Ram Suya Academy)
                    echo '<li><a href="#" class="menu_links">' . $name . '</a></li><br>';
                  }
                } else {
                  // Regular links
                  echo '<li><a href="' . $url . '" class="menu_links">' . $name . '</a></li><br>';
                }
              }
            }
          } else {
            echo "<li>Error fetching menu links: " . htmlspecialchars(mysqli_error($con)) . "</li>";
          }
          ?>
        </ul>
      </div>
    </div>
  </header>