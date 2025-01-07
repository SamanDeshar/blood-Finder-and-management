<?php
// Include session and database connection files
include 'session.php';
include 'conn.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $bloodbank_name = $_POST['bloodbank_name'];
    $contact = $_POST['contact'];
    $Address = $_POST['Address'];
    $available_blood_groups = $_POST['available_blood_groups'];
    $capacity = $_POST['capacity'];

    // Handle image file upload
    $image_url = $_FILES['image_url']['name'];
    $target_dir = "uploads/";  // Use relative path to the uploads directory
    $target_file = $target_dir . basename($image_url);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['image_url']['tmp_name'], $target_file)) {
        // Prepare the SQL statement with placeholders to avoid SQL injection
        $sql = "INSERT INTO blood_bank (bloodbank_name, image_url, contact, Address, available_blood_groups, capacity) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Store only the relative path to the image in the database (without full server path)
        $image_path = "uploads/" . basename($image_url);

        // Bind parameters to the prepared statement
        $stmt->bind_param("sssssi", $bloodbank_name, $image_path, $contact, $Address, $available_blood_groups, $capacity);

        // Execute the prepared statement and check for success
        if ($stmt->execute()) {
            header("Location: blood_edit.php?message=Blood bank added successfully");
            exit();
        } else {
            echo "Error inserting record: " . $stmt->error;
        }

        $stmt->close(); // Close the statement
    } else {
        echo "Sorry, there was an error uploading the image.";
    }
}

$conn->close();
?>
