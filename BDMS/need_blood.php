<?php
$active = 'need';
$active=='donate';
$active=='contact';
include('conn.php'); // Database connection
include('head.php'); // Header file for common header content

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $blood_group = $_POST['blood'];
  $recipient_lat = $_POST['latitude'];
  $recipient_lng = $_POST['longitude'];
  $max_distance = 50; // Set maximum search radius in km

  // SQL query to find the nearest donors with the same blood group
  $sql = "SELECT donor_name, donor_number, donor_gender, donor_age, donor_address, blood_group,
                 (6371 * acos(cos(radians(?)) * cos(radians(donor_details.latitude)) 
                 * cos(radians(donor_details.longitude) - radians(?)) 
                 + sin(radians(?)) * sin(radians(donor_details.latitude)))) AS distance
          FROM donor_details
          JOIN blood ON donor_details.donor_blood = blood.blood_id
          WHERE blood.blood_group = ?
          HAVING distance < ?
          ORDER BY distance
          LIMIT 5";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("dddsi", $recipient_lat, $recipient_lng, $recipient_lat, $blood_group, $max_distance);
  $stmt->execute();
  $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
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
  <div id="page-container" style="margin-top:50px; position: relative; min-height: 84vh;">
    <div class="container">
      <div id="content-wrap" style="padding-bottom:50px;">
        <h1 class="mt-4 mb-3">Need Blood</h1>
        
        <!-- Form to submit blood request -->
        <form name="needblood" action="" method="post">
          <div class="row">
            <!-- Recipient's Name -->
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Recipient's Name<span style="color:red">*</span></div>
              <div><input type="text" name="name" class="form-control" required></div>
            </div>

            <!-- Blood Group -->
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Blood Group<span style="color:red">*</span></div>
              <div>
                <select name="blood" class="form-control" required>
                  <option value="" selected disabled>Select</option>
                  <?php
                    $sql = "SELECT * FROM blood";
                    $result_blood = mysqli_query($conn, $sql) or die("Query unsuccessful.");
                    while ($row = mysqli_fetch_assoc($result_blood)) {
                  ?>
                    <option value="<?php echo $row['blood_group']; ?>"><?php echo $row['blood_group']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <!-- Address -->
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Address<span style="color:red">*</span></div>
              <div><input type="text" name="address" class="form-control" required></div>
            </div>

            <!-- Reason for Blood Request -->
            <div class="col-lg-4 mb-4">
              <div class="font-italic">Reason, why do you need blood?<span style="color:red">*</span></div>
              <div><textarea class="form-control" name="reason" required></textarea></div>
            </div>
          </div>

          <!-- Hidden fields for latitude and longitude -->
          <input type="hidden" id="latitude" name="latitude">
          <input type="hidden" id="longitude" name="longitude">

          <!-- Submit Button -->
          <div class="row">
            <div class="col-lg-4 mb-4">
              <div><input type="submit" class="btn btn-primary" value="Submit Request" style="cursor:pointer"></div>
            </div>
          </div>
        </form>

       
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

          // Call getLocation function on page load
          window.onload = getLocation;
        </script>

        <!-- Display Nearest Donors -->
        <?php if (isset($result) && $result->num_rows > 0): ?>
          <div class="row mt-5">
            <h3>Nearest Donors</h3>
            <?php while ($row = $result->fetch_assoc()): ?>
              <div class="col-lg-4 col-sm-6 portfolio-item">
                <br>
                <div class="card" style="width: 300px">
                  <img class="card-img-top" src="image/blood_drop_logo.jpg" alt="Card image" style="width:100%;height:300px">
                  <div class="card-body">
                    <h3 class="card-title"><?php echo $row['donor_name']; ?></h3>
                    <p class="card-text">
                      <b>Blood Group : </b> <b><?php echo $row['blood_group']; ?></b><br>
                      <b>Mobile No. : </b> <?php echo $row['donor_number']; ?><br>
                      <b>Gender : </b><?php echo $row['donor_gender']; ?><br>
                      <b>Age : </b> <?php echo $row['donor_age']; ?><br>
                      <b>Address : </b> <?php echo $row['donor_address']; ?><br>
                      <b>Distance : </b> <?php echo round($row['distance'], 2); ?> km<br>
                    </p>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
          <p>No donors found within the specified distance.</p>
        <?php endif; ?>

      </div>
    </div>
  </div>
</body>
<?php
include('conn.php'); // Database connection

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);

 
    // Insert data into `need_blood` table
    $sql = "INSERT INTO need_blood (name, blood_group, reason, address, latitude, longitude ) 
            VALUES ('$name', '$blood_group', '$reason', '$address', '$latitude', '$longitude')";

    if (mysqli_query($conn, $sql)){
      
    } else {
        header("Location: home.php");
    }
}


?>
</html>
