<?php include 'session.php'; ?>
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
  <?php
  include 'conn.php';
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  ?>
    <div id="header">
      <?php $active = "abank";
      include 'header.php';
      ?>
    </div>
    <div id="sidebar">
      <?php include 'sidebar.php'; ?>
    </div>

    <div id="content" class="container mt-5">
      <h2>Add New Blood Bank</h2>
      <form method="POST" action="adallcode.php" enctype="multipart/form-data">
        <div class="form-group">
          <label for="bloodbank_name">Blood Bank Name</label>
          <input type="text" class="form-control" name="bloodbank_name" required>
        </div>
        <div class="form-group">
          <label for="image_url">Image URL</label>
          <input type="file" class="form-control" name="image_url" required>
        </div>
        <div class="form-group">
          <label for="location"> (Address)</label>
          <input type="text" class="form-control" name="Address" required>
        </div>
        <div class="form-group">
          <label for="contact">Contact</label>
          <input type="text" class="form-control" name="contact" required>
        </div>
        <div class="form-group">
          <label for="available_blood_groups">Available Blood Groups</label>
          <input type="text" class="form-control" name="available_blood_groups" required>
        </div>
        <div class="form-group">
          <label for="capacity">Capacity</label>
          <input type="text" class="form-control" name="capacity" required>
        </div>
       
        <button type="submit" class="btn btn-primary">Add Blood Bank</button>
      </form>

      <hr>

      <!-- Manage Existing Blood Banks Button -->
      <a href="manage_blood_bank.php" class="btn btn-info" style="margin-top: 20px;">Manage Existing Blood Banks</a>
    </div>
  <?php
  } else {
    echo "Please log in to access this page.";
  }
  ?>
</body>

</html>
