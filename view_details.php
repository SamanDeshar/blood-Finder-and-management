<?php
session_start();
$active = 'need';
include('conn.php');
include('head.php'); 

if (!isset($_SESSION['form_data'])) {
  echo "Session expired or invalid access.";
  exit();
}

$data = $_SESSION['form_data'];

$name = htmlspecialchars($data['name']);
$email = htmlspecialchars($data['email']);
$requested_group = $data['blood'];
$address = htmlspecialchars($data['address']);
$reason = htmlspecialchars($data['reason']);
$latitude = $data['latitude'];
$longitude = $data['longitude'];


// BLOOD GROUP COMPATIBILITY LOGIC

$compatibility = [
    "A+"  => ["A+", "A-", "O+", "O-"],
    "A-"  => ["A-", "O-"],
    "B+"  => ["B+", "B-", "O+", "O-"],
    "B-"  => ["B-", "O-"],
    "AB+" => ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"],
    "AB-" => ["A-", "B-", "AB-", "O-"],
    "O+"  => ["O+", "O-"],
    "O-"  => ["O-"]
];

$compatibleGroups = $compatibility[$requested_group];

$max_distance = 50;

// Make placeholders for IN (...)
$placeholders = implode(',', array_fill(0, count($compatibleGroups), '?'));

// Final SQL (Dynamic Blood Compatibility + Nearest Distance)
$sql = "
SELECT donor_name, donor_number, donor_gender, donor_age, donor_address, blood_group,
    (6371 * acos(cos(radians(?)) * cos(radians(donor_details.latitude)) 
    * cos(radians(donor_details.longitude) - radians(?)) 
    + sin(radians(?)) * sin(radians(donor_details.latitude)))) AS distance
FROM donor_details
JOIN blood ON donor_details.donor_blood = blood.blood_id
WHERE blood.blood_group IN ($placeholders)
HAVING distance < ?
ORDER BY distance
LIMIT 5
";

$stmt = $conn->prepare($sql);


$types = str_repeat("s", count($compatibleGroups));  
$types = "ddd" . $types . "i"; //

$params = [$types, $latitude, $longitude, $latitude];

// Append compatible groups into parameter list
foreach ($compatibleGroups as $bg) {
    $params[] = $bg;
}


$params[] = $max_distance;
$stmt->bind_param(...$params);

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
  <div class="container mt-5">
    <div class="card mb-4 p-4">
      <h4 class="mb-4 text-danger font-weight-bold">Request Summary</h4>
      <div class="row">
        <div class="col-md-6">
          <p><strong>Name:</strong> <?= $name ?></p>
          <p><strong>Email:</strong> <?= $email ?></p>
          <p><strong>Requested Blood Group:</strong> <?= $requested_group ?></p>
        </div>

        <div class="col-md-6">
          <p><strong>Address:</strong> <?= $address ?></p>
          <p><strong>Reason:</strong> <?= $reason ?></p>

          <p><strong>Compatible Groups Used:</strong> <?= implode(", ", $compatibleGroups) ?></p>

          <p><strong>Donor Consent Status:</strong>
            <?= $result->num_rows > 0
              ? '<span class="text-success">Pending</span>'
              : '<span class="text-danger">No Donor Found</span>' ?>
          </p>
        </div>
      </div>
    </div>

    <hr>

    <h3>Nearest Compatible Donors</h3>
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
        <p>No compatible donors found within 50 km radius.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
