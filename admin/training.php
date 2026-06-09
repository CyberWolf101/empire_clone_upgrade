<?php include "header.php";

/// Delete
if (isset($_GET['categoryid'])) {
  $service_delete = $_GET['categoryid'];
  $del = mysqli_query($con, "DELETE from training where s='$service_delete'") or die('Could not connect: ' . mysqli_error($con));
  echo "<script>alert('Training Deleted Successfully!'); window.location.href = 'delta_subcategory.php';</script>";
  exit(); // Make sure to exit the script after the alert
}

//Update Store
if (isset($_POST['update_store'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $discount = $_POST['discount'];
  $des = mysqli_real_escape_string($con, $_POST['details']);
  $fileName = basename($_FILES["file"]["name"]);


  // File upload path
  $targetDir = "../chbacademy/";
  $fileName = basename($_FILES["file"]["name"]);
  $targetFilePath = $targetDir . $fileName;
  $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

  if (isset($_POST["update_store"]) && !empty($_FILES["file"]["name"])) {
    // Allow certain file formats
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf');
    if (in_array($fileType, $allowTypes)) {
      // Upload file to server
      if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
        // Insert image file name into database
        $insert = mysqli_query($con, "INSERT into images (file_name,input,uploaded_on) VALUES ('" . $fileName . "','staff', NOW())");
        $insert = mysqli_query($con, "UPDATE training SET picture='" . $fileName . "' where s='$id'") or die('Could not connect: ' . mysqli_error($con));
        if ($insert) {
          $statusMsg = "The file " . $fileName . " has been uploaded successfully.";
        } else {
          $statusMsg = "File upload failed, please try again.";
        }
      } else {
        $statusMsg = "Sorry, there was an error uploading your file.";
      }
    } else {
      $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
    }
  } else {
    $statusMsg = 'Please select a file to upload.';
  }



  $insert = mysqli_query($con, "UPDATE training  SET name='$name', discount_added = '$discount' where s='$id'") or die('Could not connect: ' . mysqli_error($con));
  $insert = mysqli_query($con, "UPDATE training  SET description='$des' where s='$id'") or die('Could not connect: ' . mysqli_error($con));
  echo "<script>alert('Details Updated Successfully!'); window.location.href = 'training.php';</script>";
}

// Add training items



?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Academy Trainings</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Academy</li>
  </ol>
</div>

<!-- Row -->
<div class="row">

  <div align="center" class="col-lg-12">
    <script type="text/javascript">
      function showAri() {
        if (document.getElementById('formAri').style.display == 'none') {
          // clock is visible. hide it
          document.getElementById('formAri').style.display = 'block';
        } else {
          // clock is hidden. show it
          document.getElementById('formAri').style.display = 'none';
        }
      }
    </script>
    <?php include "addtraining.php"; ?>
    <p><button onClick="showAri()" class="btn btn-warning w-100">Add New Training</button></p>
    <div class="arizona" id="formAri" style="display:none;">
      <form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:center;">
        <input type="text" class="form-control" name="name" placeholder="*Name" required /><br />
        <textarea name="details" class="form-control" placeholder="Enter description here"></textarea><br>
        <input type="file" class="form-control" name="file" required /><br />
        <input type='submit' name='register' value='Register Details' class='btn btn-primary w-100'>
      </form>
    </div>
  </div>


  <!-- Datatables -->
  <div class="col-lg-12" style="margin-top:2%;">
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">All Trainings</h6>
      </div>
      <div class="table-responsive p-3">
        <table class="table align-items-center table-flush text-primary" id="dataTable">
          <thead class="thead-light">
            <tr>
              <th>Name</th>
              <!-- <th>Training Items / Price</th> -->
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Name</th>
              <!-- <th>Training Items / Price</th> -->
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>
          <tbody>
            <?php
            $sql = "SELECT * from training ORDER BY s ASC";
            $sql2 = mysqli_query($con, $sql);
            $trainings = [];
            $trainingItems = [];

            while ($row = mysqli_fetch_array($sql2)) {
              $trainings[] = $row;

              foreach ($trainings as $training) {
                $id = $training["id"];
                $sql = "SELECT * FROM training_items WHERE training_id = '$id'";
                $result = mysqli_fetch_array(mysqli_query($con, $sql));
                $trainingItems[] = $result;
            ?>
                <tr>
                  <td><?= $training['name'] ?></td>
                  <!-- <td>
                    <?php
                    // foreach ($trainingItems as $item) {
                    //   echo $item["name"] . ": &#8358;" . $item["price"];
                    // }
                    ?>
                  </td> -->
                  <td> <button type='button' data-toggle='modal' data-target='#modal<?= $training['s'] ?>' class='btn btn-sm btn-primary'>Edit</button></td>
                  <td> <button type='button' data-toggle='modal' data-target='#addTrainingItemModal<?= $training['s'] ?>' class='btn btn-sm btn-primary'>Add Training Item</button></td>
                  <td>
                    <form action='' method='get' onsubmit='return confirm("Are you sure you want to delete this \"<?= $training["name"] ?>\"?")'>
                      <input type='text' name='categoryid' value='<?= $training['s'] ?>' required hidden>
                      <input type='submit' name='delete' value='Delete Training' class='btn btn-sm btn-danger'>
                    </form>
                  </td>
                </tr>
              <?php
              }






              echo '<div class="modal fade" id="modal' . $row['s'] . '" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
                  <form id="form" name="form" action="" method="post" enctype="multipart/form-data"> 
				<div class="modal-header">
				<h6 style="color:black;">Edit Details</h6>
				</div>
                <div class="modal-body">
                      <div class="row mb-3">
                      <div class="col-md-12">
                          
                          
                   <p><input type="text" name="name" class="form-control" value="' . $row['name'] . '" placeholder="Name" required></p>
                   <p><textarea name="details" class="form-control" placeholder="Enter description here">' . $row['description'] . '</textarea></p>
                   <p><label>Discount(%)</label><input type="number" class="form-control" name="discount" value="' . $row["discount_added"] . '" /></p>
                   <p><label>Add New File</label><input type="file" class="form-control" name="file" /></p>
                   <p><input type="hidden" name="id" class="form-control" value="' . $row['s'] . '" placeholder="Advert Text" required></p>
                    </div>
					  <div class="modal-footer">
					  <input id="submit" name="update_store" class="btn btn-sm btn-primary shadow-sm w-100" type="submit" value="Update Details">
                    </div>
                    </form>
                  </div>
                </div></div>
               </div><!-- End Modal Dialog Scrollable-->
               


























';

              ?>
              <form id="add-training-items-form" action="javascript:void();" method="post">
                <div class="modal fade addTrainingItemModal" id="addTrainingItemModal<?= $row['s'] ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 style="color:black;">Add Training Items</h6>
                      </div>
                      <div class="modal-body">
                        <div id="add-training-item-message" class="w-100"></div>
                        <div class="row mb-3">
                          <div class="col-md-12">
                            <p><input type="text" name="name" class="form-control" placeholder="Item Name" required></p>
                            <p><input type="number" name="price" class="form-control" placeholder="Item Price" required></p>
                            <input type="hidden" id="training_id_input" class="form-control" name="training_id" value="<?= $row["id"] ?>" />
                          </div>
                          <div class="d-flex">
                            <button id="add" name="add_training_item" class="btn btn-sm btn-primary shadow-sm w-100" type="button">Add</button>
                            <input id="save_changes" name="save_changes" class="btn btn-sm btn-success shadow-sm w-100" type="button" value="Save changes">
                          </div>

                          <div class="w-100 mb-3 d-grid">
                            <!-- <p>New (unsaved) items</p> -->
                            <div id="new_changes_page"></div>
                            <div>
                              <hr>
                              <p>Old Items</p>
                              <div id="old-items"></div>
                              <script>
                                function loadOldItems() {
                                  fetch('all_training_items.php', {
                                      method: "POST",
                                      body: JSON.stringify({
                                        training_id: document.querySelector("#training_id_input").value
                                      }),
                                    }).then(res => res.json())
                                    .then(data => {
                                      const page = document.querySelector("#old-items");
                                      page.innerHTML = "";
                                      if (data.length < 1) {
                                        page.innerHTML = "No old items";
                                      } else {
                                        data.forEach((item, index) => {
                                          page.innerHTML += `
                                            <div class="card shadow-sm bg-light m-3 p-1 d-flex justify-content-between">
                                              <div class="d-grid">
                                                <p class="p-1 font-weight-bold">${item.name}</p>
                                                <p class="px-1">${item.price}</p>
                                              </div>
                                              <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm bg-danger btn-close" onclick=''><i class="bi bi-trash"></i></button>
                                              </div>
                                            </div>
                                        `;
                                        })

                                      }
                                    })
                                }
                                setInterval(loadOldItems(), 100);
                              </script>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">

                      </div>

                      <script>
                        const formToSubmit = document.querySelector("#add-training-items-form");
                        let newItems = [];
                        const html = document.getElementById("new_changes_page");

                        function deleteI(index) {
                          newItems.splice(index, 1);
                          console.log(newItems);
                          html.innerHTML = "";
                          newItems.forEach((item, index) => {
                            html.innerHTML += `
                            <div class="card shadow-sm bg-light m-3 p-1 d-flex justify-content-between">
                              <div class="d-grid">
                                <p class="p-1 font-weight-bold">${item.name}</p>
                                <p class="px-1">${item.price}</p>
                              </div>
                              <div class="d-flex justify-content-end">
                              <button class="btn btn-sm bg-danger btn-close" onclick='deleteI(${index})'><i class="bi bi-trash"></i></button>
                              </div>
                            </div>`;
                          })
                        }
                        document.querySelector("button#add").addEventListener("click", function() {
                          // e.preventDefault();
                          const form = new FormData(formToSubmit);
                          console.log(form);
                          let name = form.get("name");
                          let price = form.get("price");
                          if (!(name == "" || price == "") && !(newItems.find((x) => name == x.name))) {
                            newItems.push({
                              name,
                              price
                            })
                          }
                          html.innerHTML = "";
                          if (newItems.length < 1) {
                            // html.innerHTML = "No new items";
                          } else {
                            newItems.forEach((item, index) => {
                              html.innerHTML += `
                                <div class="card shadow-sm bg-light m-3 p-1 d-flex justify-content-between">
                                  <div class="d-grid">
                                    <p class="p-1 font-weight-bold">${item.name}</p>
                                    <p class="px-1">${item.price}</p>
                                  </div>
                                  <div class="d-flex justify-content-end">
                                  <button class="btn btn-sm bg-danger btn-close" onclick='deleteI(${index})'><i class="bi bi-trash"></i></button>
                                  </div>
                                </div>
                              `;
                            })
                          }

                        })
                        document.querySelector("input#save_changes").addEventListener("click", function() {
                          fetch("add_training_items.php", {
                              method: "POST",
                              headers: {
                                "Content-type": "application/json"
                              },
                              body: JSON.stringify({
                                training_id: document.querySelector("#training_id_input").value,
                                data: JSON.stringify(newItems)
                              })
                            })
                            .then(res => res.json())
                            .then(data => {
                              if (data) {
                                if (data.status == true) {
                                  document.querySelector("#add-training-item-message").innerHTML = `
                                  <div class="alert alert-success alert-dismissable">
                                    <p class="alert-text">${data.message}</p>
                                  </div>
                                  `;
                                  loadOldItems();
                                  const cartModal = new bootstrap.Modal(document.querySelector('addTrainingItemModal'), {});
                                  cartModal.hide();
                                } else {
                                  document.querySelector("#add-training-item-message").innerHTML = `
                                  <div class="alert alert-danger alert-dismissable">
                                    <p class="alert-text">${data.message}</p>
                                  </div>
                                  `;
                                }
                              }
                            });
                          newItems = [];
                          html.innerHTML = "";
                        })
                      </script>
                    </div>
                  </div>
                </div>
              </form>
              <?php

              // $i++;
              ?>
              <!-- ADD TRAINING ITEMS MODAL -->

            <?php
            }
            ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>




  <?php include "footer.php"; ?>