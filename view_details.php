<?php
session_start();
$active = 'need';
include('conn.php');
include('head.php'); // your header file

if (!isset($_SESSION['form_data'])) {
  echo "Session expired or invalid access.";
  exit();
}

$data = $_SESSION['form_data'];

$name = htmlspecialchars($data['name']);
$email = htmlspecialchars($data['email']);
$blood_group = $data['blood'];
$address = htmlspecialchars($data['address']);
$reason = htmlspecialchars($data['reason']);
$latitude = $data['latitude'];
$longitude = $data['longitude'];

// Search for nearest donors
$max_distance = 50;

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
$stmt->bind_param("dddsi", $latitude, $longitude, $latitude, $blood_group, $max_distance);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Request Summary</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .card p {
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
  <!-- Header is included by head.php -->

  <div class="container mt-5">
    <!-- Request Summary in Two Columns -->
    <div class="card mb-4 p-4">
      <h4 class="mb-4 text-danger font-weight-bold">Request Summary</h4>
      <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
          <p><strong>Name:</strong> <?= $name ?></p>
          <p><strong>Email:</strong> <?= $email ?></p>
          <p><strong>Blood Group:</strong> <?= $blood_group ?></p>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <p><strong>Address:</strong> <?= $address ?></p>
          <p><strong>Reason:</strong> <?= $reason ?></p>
          <p><strong>Hospital Status:</strong>
            <?= isset($data['hospital_status']) ? htmlspecialchars($data['hospital_status']) : 'N/A' ?>
          </p>
          <p><strong>Donor consent Status:</strong>
            <?= $result->num_rows > 0
              ? '<span class="text-success">Pending</span>'
              : '<span class="text-danger">No Donor Found</span>' ?>
          </p>
        </div>
      </div>
    </div>

    <hr>

    <h3>Nearest Donors</h3>
    <div class="row">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="col-md-4">
            <div class="card mb-4">
              <img class="card-img-top" src="image/blood_drop_logo.jpg" alt="Donor Image" style="height: 200px;">
              <div class="card-body">
                <h5 class="card-title"><?= $row['donor_name'] ?></h5>
                <p class="card-text">
                  <strong>Blood Group:</strong> <?= $row['blood_group'] ?><br>
                  <strong>Phone:</strong> <?= $row['donor_number'] ?><br>
                  <strong>Gender:</strong> <?= $row['donor_gender'] ?><br>
                  <strong>Age:</strong> <?= $row['donor_age'] ?><br>
                  <strong>Address:</strong> <?= $row['donor_address'] ?><br>
                  <strong>Distance:</strong> <?= round($row['distance'], 2) ?> km
                </p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No donors found within 50 km radius.</p>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>