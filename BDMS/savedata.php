<?php
include 'conn.php';

if(isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $mobileno = $_POST['mobileno'];
    $emailid = $_POST['emailid'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $blood = $_POST['blood'];
    $address = $_POST['address'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $sql = "INSERT INTO donor_details (donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, donor_address, latitude, longitude) 
            VALUES ('$fullname', '$mobileno', '$emailid', '$age', '$gender', '$blood', '$address', '$latitude', '$longitude')";

    if(mysqli_query($conn, $sql)) {
        echo "Data saved successfully.";
        header('location:home.php');
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
