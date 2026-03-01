<?php include "header.php"; ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Add New Staff</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Staff</li>
  </ol>
</div>

<!-- Row -->
<div class="row">

  <div align="center" class="col-lg-12">
    <?php include "add_staff.php"; ?>
    <div class="arizona">
      <form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:left;">
        <label for="" class="small">Name:</label>
        <input type="text" class="form-control" name="name" placeholder="*Name" required /><br />
        <label for="" class="small">Email:</label>
        <input type="text" class="form-control" name="email" placeholder="*Email" required /><br />
        <label for="" class="small">Password:</label>
        <input type="text" class="form-control" name="password" placeholder="*Password" required /><br />


        <p>
          <label for="" class="small">Role:</label>
          <select class="form-control" name="role" id="roleSelect" required>
            <option selected="selected" value="">- Select Role -</option>
            <option value="manager">Manager</option>
            <option value="subadmin">Sub admin</option>
            <option value="cashier">Cashier</option>
            <option value="storekeeper">Store keeper</option>
            <option value="accountant">Accountant</option>
          </select>
        </p>


        <p id="departmentWrapper">
          <label for="" class="small">Department:</label>
          <select class="select2-multiple form-control" name="sections[]" multiple="multiple" id="select2Multiple"
            style="width:100%;">
            <option value="">- Select Department -</option>
            <option value="saloon">saloon</option>
            <option value="orishirishi">Orishirishi</option>
            <option value="kitchen">Delta Kitchen</option>
            <option value="repair">Repair Center</option>
            <option value="academy">Academy</option>
            <option value="members">Membership</option>
            <option value="giftcard">Giftcard</option>
            <option value="vouchers">Vouchers</option>
            <option value="staff">Staff</option>
            <option value="rental">Rental</option>
            <option value="reviews">Reviews</option>
          </select>
        </p>




        <input type='submit' name='register' value='Register Staff' class='btn btn-primary'>
      </form>
    </div>
  </div>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.getElementById("roleSelect");
    const departmentWrapper = document.getElementById("departmentWrapper");
    const departmentSelect = document.getElementById("select2Multiple");

    function toggleDepartments() {
      if (roleSelect.value === "subadmin") {
        // Hide the entire department dropdown
        departmentWrapper.style.display = "none";
        // Clear selected values
        departmentSelect.value = "";
        if (typeof $ !== "undefined" && $(departmentSelect).hasClass("select2-hidden-accessible")) {
          $(departmentSelect).val(null).trigger("change");
        }
      } else {
        // Show it again
        departmentWrapper.style.display = "block";
      }
    }

    // Run once on load
    toggleDepartments();

    // Run whenever role changes
    roleSelect.addEventListener("change", toggleDepartments);
  });
</script>

</div>



<?php include "footer.php"; ?>