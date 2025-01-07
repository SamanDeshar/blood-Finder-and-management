<?php
include 'session.php';
include 'conn.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM blood_bank WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $bloodBank = $result->fetch_assoc();
    } else {
      echo "Blood bank not found.";
      exit();
    }
  } else {
    echo "Invalid request.";
    exit();
  }
} else {
  echo "Please log in to access this page.";
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <style>
    #sidebar {
      position: relative;
      margin-top: -20px;
    }

    #content {
      position: relative;
      margin-left: 210px;
    }

    @media screen and (max-width: 600px) {
      #content {
        position: relative;
        margin-left: auto;
        margin-right: auto;
      }
    }
  </style>
</head>

<body style="color:black">
  <div id="header">
    <?php $active = "abank";
    include 'header.php';
    ?>
  </div>
  <div id="sidebar">
    <?php include 'sidebar.php'; ?>
  </div>

  <div id="content" class="container mt-5">
    <h2>Edit Blood Bank</h2>
    <form method="POST" action="updateblood_bank.php" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $bloodBank['id']; ?>">

      <div class="form-group">
        <label for="bloodbank_name">Blood Bank Name</label>
        <input type="text" class="form-control" name="bloodbank_name" value="<?php echo $bloodBank['bloodbank_name']; ?>" required>
      </div>

      <div class="form-group">
        <label for="image_url">Image URL</label>
        <input type="file" class="form-control" name="image_url">
        <small>Leave blank to keep current image.</small>
      </div>

      <div class="form-group">
        <label for="Address">Address</label>
        <input type="text" class="form-control" name="Address" value="<?php echo $bloodBank['Address']; ?>" required>
      </div>

      <div class="form-group">
        <label for="contact">Contact</label>
        <input type="text" class="form-control" name="contact" value="<?php echo $bloodBank['contact']; ?>" required>
      </div>

      <div class="form-group">
        <label for="available_blood_groups">Available Blood Groups</label>
        <input type="text" class="form-control" name="available_blood_groups" value="<?php echo $bloodBank['available_blood_groups']; ?>" required>
      </div>

      <div class="form-group">
        <label for="capacity">Capacity</label>
        <input type="text" class="form-control" name="capacity" value="<?php echo $bloodBank['capacity']; ?>" required>
      </div>

      <button type="submit" class="btn btn-primary">Update Blood Bank</button>
    </form>
  </div>
</body>

</html>
