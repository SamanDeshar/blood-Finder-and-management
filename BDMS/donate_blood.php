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

      <form name="donor" action="savedata.php" method="post" onsubmit="return validateForm()">
        <div class="row">
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Full Name<span style="color:red">*</span></div>
            <div><input type="text" name="fullname" class="form-control" required></div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Mobile Number<span style="color:red">*</span></div>
            <div><input type="text" name="mobileno" class="form-control" required></div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Email Id</div>
            <div><input type="email" name="emailid" class="form-control"></div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Age<span style="color:red">*</span></div>
            <div><input type="text" name="age" class="form-control" required></div>
          </div>
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
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Blood Group<span style="color:red">*</span></div>
            <div>
              <select name="blood" class="form-control" required>
                <option value="" selected disabled>Select</option>
                <?php
                include 'conn.php';
                $sql = "select * from blood";
                $result = mysqli_query($conn, $sql) or die("Query unsuccessful.");
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <option value="<?php echo $row['blood_id'] ?>"><?php echo $row['blood_group'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 mb-4">
            <div class="font-italic">Address<span style="color:red">*</span></div>
            <div><input type="text" id="address" name="address" class="form-control" required></div>
          </div>
        </div>

        <!-- Hidden inputs for latitude and longitude -->
        <input type="hidden" name="latitude" id="latitude" />
        <input type="hidden" name="longitude" id="longitude" />

        <div class="row">
          <div class="col-lg-4 mb-4">
            <div><input type="submit" name="submit" class="btn btn-primary" value="Submit" style="cursor:pointer"></div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

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
    switch(error.code) {
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

  function validateForm() {
    // Check if latitude and longitude are empty
    if (!document.getElementById("latitude").value || !document.getElementById("longitude").value) {
      alert("Please allow location access to continue.");
      getLocation(); // Prompt the user to allow location
      return false; // Prevent form submission until location is obtained
    }
    return true;
  }

  // Call getLocation function on page load
  window.onload = getLocation;
</script>

</body>
</html>
