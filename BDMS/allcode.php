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
