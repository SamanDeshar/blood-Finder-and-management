<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Blood Bank Details</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php 
$active = 'bank';
include('head.php');
include('conn.php');

// Set the number of records per page
$records_per_page = 6;

// Get the current page or set it to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Retrieve blood bank records with pagination
$query = "SELECT * FROM blood_bank LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $offset, $records_per_page);
$stmt->execute();
$result = $stmt->get_result();

// Count total records for pagination
$total_query = "SELECT COUNT(*) as total FROM blood_bank";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $records_per_page);
?>
<div class="container mt-5">
  <div class="row">
    <?php while ($row = $result->fetch_assoc()) { ?>
      <div class="col-md-4 mb-4">
        <div class="card">
          <!-- Adjust the image path as necessary -->
          <img src="/BDMS/admin/<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top" alt="Blood Bank Image" style="height: 200px; object-fit: cover;">
         <div class="card-body">
       
            <h5 class="card-title"><?php echo htmlspecialchars($row['bloodbank_name']); ?></h5>
            <p class="card-text">
              <strong>Address:</strong> <?php echo htmlspecialchars($row['Address']); ?><br>
              <strong>Contact:</strong> <?php echo htmlspecialchars($row['contact']); ?><br>
              <strong>Available Blood Groups:</strong> <?php echo htmlspecialchars($row['available_blood_groups']); ?><br>
              <strong>Capacity:</strong> <?php echo htmlspecialchars($row['capacity']); ?>
            </p>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

  <!-- Pagination Controls -->
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
          <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $total_pages): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
