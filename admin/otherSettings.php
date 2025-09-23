<?php
// session_start();
// include "header.php";


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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_menu_links'])) {
  $enabled = $_POST['isEnabled'] ?? [];
  $sql = "SELECT id FROM menu_links";
  $result = mysqli_query($con, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $link_id = $row['id'];
    $isEnabled = in_array($link_id, $enabled) ? 1 : 0;
    $update_sql = "UPDATE menu_links SET isEnabled = $isEnabled WHERE id = $link_id";
    if (!mysqli_query($con, $update_sql)) {
      error_log("Failed to update menu link ID $link_id: " . mysqli_error($con));
      echo "<script>alert('Error updating menu link: " . addslashes(mysqli_error($con)) . "');</script>";
    }
  }
  // echo "<script>alert('Menu links updated successfully!'); window.location.href='settings.php';</script>";
     header("Location: settings.php");
}
?>

<style>
  .settings-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #f8f8f8;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .settings-container h5 {
    color: #000;
    font-family: 'Poppins', sans-serif;
    text-align: center;
    margin-bottom: 20px;
  }

  .settings-container table {
    width: 100%;
    border-collapse: collapse;
  }

  .settings-container th,
  .settings-container td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
  }

  .settings-container th {
    background: #FEBF01;
    color: #fff;
    font-family: 'Poppins', sans-serif;
  }

  .settings-container td {
    font-family: 'Poppins', sans-serif;
  }

  .settings-container .toggle-switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 20px;
  }

  .settings-container .toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .settings-container .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ccc;
    transition: 0.4s;
    border-radius: 20px;
  }

  .settings-container .slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 2px;
    bottom: 2px;
    background: #fff;
    transition: 0.4s;
    border-radius: 50%;
  }

  .settings-container input:checked + .slider {
    background: #FEBF01;
  }

  .settings-container input:checked + .slider:before {
    transform: translateX(20px);
  }
</style>

<div class="settings-container">
  <h5>Manage Menu Links</h5>
  <form method="post" action="">
    <table>
      <thead>
        <tr>
          <th>Link Name</th>
          <!-- <th>URL</th> -->
          <th>Enabled</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM menu_links ORDER BY parent_id IS NULL DESC, order_no ASC";
        $result = mysqli_query($con, $sql);
        if ($result) {
          while ($row = mysqli_fetch_array($result)) {
            $indent = $row['parent_id'] ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '';
            echo '<tr>';
            echo '<td>' . $indent . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>
                    <label class="toggle-switch">
                      <input type="checkbox" name="isEnabled[]" value="' . $row['id'] . '"' . ($row['isEnabled'] ? ' checked' : '') . '>
                      <span class="slider"></span>
                    </label>
                  </td>';
            echo '</tr>';
          }
        } else {
          echo '<tr><td colspan="3">Error fetching menu links: ' . htmlspecialchars(mysqli_error($con)) . '</td></tr>';
        }
        ?>
      </tbody>
    </table>
    <button type="submit" name="update_menu_links" class="btn btn-warning mt-3" style="font-family: 'Poppins', sans-serif;">
      <span>Save Changes</span>
    </button>
  </form>
</div>
