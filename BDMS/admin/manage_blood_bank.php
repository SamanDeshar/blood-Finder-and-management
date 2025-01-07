<?php
include 'session.php';
include 'conn.php';
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
  <?php
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  ?>
    <div id="header">
      <?php $active = "abank"; // To keep the active state on sidebar
      include 'header.php';
      ?>
    </div>
    <div id="sidebar">
      <?php include 'sidebar.php'; ?>
    </div>

    <div id="content" class="container mt-5">
      <h2>Manage Existing Blood Banks</h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Available Blood Groups</th>
            <th>Capacity</th>
            <th>Image</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Fetch all blood banks for display
          $query = "SELECT * FROM blood_bank";
          $result = mysqli_query($conn, $query);
          while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['bloodbank_name']; ?></td>
              <td><?php echo $row['contact']; ?></td>
              <td><?php echo $row['Address']; ?></td>
              <td><?php echo $row['available_blood_groups']; ?></td>
              <td><?php echo $row['capacity']; ?></td>
              <td><img src="<?php echo $row['image_url']; ?>" width="50" height="50" alt="Image"></td>
              <td>
                <a href="edit_blood_bank.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_blood_bank.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php
  } else {
    echo "Please log in to access this page.";
  }
  ?>
</body>

</html>
