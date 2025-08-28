<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
  <?php
  $active = 'donate';
  include('head.php');
  ?>

  <div id="page-container" style="margin-top:50px; position: relative; min-height: 84vh;">
    <div class="container">
      <div id="content-wrap" style="padding-bottom:50px;">
        <div class="row">
          <div class="col-lg-6">
            <h1 class="mt-4 mb-3">Donate Blood</h1>
          </div>
        </div>
        <form name="donor" action="donerdata+opt.php" method="post" onsubmit="return validateForm()">
          <div class="row">
            <!-- Full Name -->
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Full Name<span style="color:red">*</span></div>
              <div>
                <input type="text" name="fullname" class="form-control" required>
              </div>
            </div>

            <!-- Mobile Number -->
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Mobile Number<span style="color:red">*</span></div>
              <div>
                <input type="text" name="mobileno" class="form-control" id="mobileno" required>
                <small class="text-danger" id="error-mobile"></small>
              </div>
            </div>

            <!-- Age -->
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Age<span style="color:red">*</span></div>
              <div>
                <input type="text" name="age" class="form-control" id="age" required>
                <small class="text-danger" id="error-age"></small>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Email -->
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Email Id</div>
              <div>
                <input type="email" name="emailid" class="form-control" id="emailid">
                <small class="text-danger" id="error-email"></small>
              </div>
            </div>

            <!-- Gender -->
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Gender<span style="color:red">*</span></div>
              <div>
                <select name="gender" class="form-control" required>
                  <option value="">Select</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
            </div>

            <!-- Blood Group -->
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Blood Group<span style="color:red">*</span></div>
              <div>
                <select name="blood" class="form-control" required>
                  <option value="" selected disabled>Select</option>
                  <?php
                  include 'conn.php';
                  $sql = "SELECT * FROM blood";
                  $result = mysqli_query($conn, $sql) or die("Query unsuccessful.");
                  while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <option value="<?php echo $row['blood_id']; ?>"><?php echo $row['blood_group']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Address -->
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Address<span style="color:red">*</span></div>
              <div>
                <input type="text" name="address" class="form-control" id="address" required>
              </div>
            </div>
          </div>

          <!-- Hidden location fields -->
          <input type="hidden" name="latitude" id="latitude" />
          <input type="hidden" name="longitude" id="longitude" />

          <div class="row">
            <div class="col-lg-4 mb-4">
              <input type="submit" name="submit" class="btn btn-primary" value="verify with OPT" style="cursor:pointer">
            </div>
          </div>
        </form>
        
  <!--       <?php
        $verified = isset($_GET['verified']) && $_GET['verified'] == 1;
        ?>
        <div class="mt-4">
          <a href="view_details.php?verified=1" class="btn btn-success">View your request Details</a>
          <?php if ($verified): ?>
          <?php endif; ?>
        </div>

 -->
        <?php include('footer.php'); ?>

        <!-- JavaScript to get location -->
        <script>
          function getLocation() {
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
              alert("Geolocation is not supported by this browser.");
            }
          }

          function showPosition(position) {
            document.getElementById("latitude").value = position.coords.latitude;
            document.getElementById("longitude").value = position.coords.longitude;
          }

          function showError(error) {
            switch (error.code) {
              case error.PERMISSION_DENIED:
                alert("User denied the request for Geolocation.");
                break;
              case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
              case error.TIMEOUT:
                alert("The request to get user location timed out.");
                break;
              case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
            }
          }

          // Call getLocation function on page load
          window.onload = getLocation;
          function validateForm() {
            let valid = true;

            // Clear previous error messages
            document.getElementById("error-mobile").innerText = "";
            document.getElementById("error-age").innerText = "";
            document.getElementById("error-email").innerText = "";

            // Location check
            if (!document.getElementById("latitude").value || !document.getElementById("longitude").value) {
              alert("Please allow location access to continue.");
              getLocation();
              return false;
            }

            // Mobile number validation
            const mobile = document.getElementById("mobileno").value.trim();
            const mobileRegex = /^[0-9]{10}$/;
            if (!mobileRegex.test(mobile)) {
              document.getElementById("error-mobile").innerText = "Please enter a valid 10-digit mobile number.";
              valid = false;
            }

            // Age validation
            const ageValue = document.getElementById("age").value.trim();
            const age = parseInt(ageValue, 10);
            if (isNaN(age) || age < 18 || age > 50) {
              document.getElementById("error-age").innerText = "Age must be between 18 and 50.";
              valid = false;
            }

            // Email validation
            const email = document.getElementById("emailid").value.trim();
            if (email !== "") {
              const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
              if (!emailRegex.test(email)) {
                document.getElementById("error-email").innerText = "Please enter a valid email address.";
                valid = false;
              }
            }

            return valid;
          }


        </script>


</body>

</html>