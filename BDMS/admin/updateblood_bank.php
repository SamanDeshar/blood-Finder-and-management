<?php
include 'session.php';
include 'conn.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form data
        $id = $_POST['id']; // Make sure to pass the id of the blood bank to be updated
        $name = $_POST['bloodbank_name'];
        $address = $_POST['Address'];
        $contact = $_POST['contact'];
        $blood_groups = $_POST['available_blood_groups'];
        $capacity = $_POST['capacity'];
        $image_url = '';

        // Check if an image was uploaded
        if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
            $image_name = basename($_FILES['image_url']['name']);
            $target_dir = 'uploads/';
            $target_file = $target_dir . $image_name;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image_url']['tmp_name'], $target_file)) {
                $image_url = $target_file;
            } else {
                echo "Error uploading image.";
                exit();
            }
        }

        // Prepare the SQL update statement
        $query = "UPDATE blood_bank SET bloodbank_name = ?, Address = ?, contact = ?, available_blood_groups = ?, capacity = ?, image_url = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $name, $address, $contact, $blood_groups, $capacity, $image_url, $id);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            header("Location: manage_blood_bank.php"); // Redirect to the management page
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    }
} else {
    echo "Please log in to access this page.";
}
?>
