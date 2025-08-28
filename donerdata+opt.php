<?php
session_start();
include 'conn.php';
require 'vendor/autoload.php'; // PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $fullname = trim($_POST['fullname']);
    $mobileno = trim($_POST['mobileno']);
    $emailid = trim($_POST['emailid']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $blood = trim($_POST['blood']);
    $address = trim($_POST['address']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);

    $errors = [];

    // Validation
    if (empty($fullname))
        $errors[] = "Full name is required.";
    if (!preg_match('/^[0-9]{10}$/', $mobileno))
        $errors[] = "Mobile number must be 10 digits.";
    if (!is_numeric($age) || $age < 18 || $age > 50)
        $errors[] = "Age must be between 18 and 50.";
    if (!in_array($gender, ['Male', 'Female']))
        $errors[] = "Select a valid gender.";
    if (!is_numeric($blood))
        $errors[] = "Select a valid blood group.";
    if (empty($address))
        $errors[] = "Address is required.";
    if (!is_numeric($latitude) || !is_numeric($longitude))
        $errors[] = "Location coordinates are invalid.";

    // Email check (optional field)
    if (!empty($emailid)) {
        if (!filter_var($emailid, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        } else {
            $stmt = $conn->prepare("SELECT donor_id FROM donor_details WHERE donor_mail = ?");
            $stmt->bind_param("s", $emailid);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors[] = "This email is already registered.";
            }
            $stmt->close();
        }
    }

    if (!empty($errors)) {
        foreach ($errors as $e) {
            echo "<p style='color:red;'>$e</p>";
        }
        exit;
    }

    // Generate OTP and save to session
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;

    // Store form data
    $_SESSION['form_data'] = [
        'fullname' => $fullname,
        'mobileno' => $mobileno,
        'emailid' => $emailid,
        'age' => $age,
        'gender' => $gender,
        'blood' => $blood,
        'address' => $address,
        'latitude' => $latitude,
        'longitude' => $longitude
    ];

    // Send OTP email if email is provided
    if (!empty($emailid)) {
        $mail = new PHPMailer(true);
        try {
            // SMTP setup — adjust your credentials
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = '1samandeshar1@gmail.com';
            $mail->Password = 'qvbv juak xpau lmji'; //
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your_email@gmail.com', 'HamroBlood');
            $mail->addAddress($emailid, $fullname);
            $mail->Subject = 'Your OTP Code for Donor Registration';
            $mail->Body = "Hi $fullname,\n\nYour OTP code is: $otp\n\nPlease enter this code on the verification page to complete your registration.";

            $mail->send();
        } catch (Exception $e) {
            echo "<p style='color:red;'>Mailer Error: {$mail->ErrorInfo}</p>";
            exit;
        }
    } else {
        // No email — consider sending OTP via SMS or show OTP on screen (not recommended for production)
        echo "<p style='color:green;'>Your OTP is: <strong>$otp</strong></p>";
    }

    // Redirect to OTP verification page
    header("Location: verify_donor_otp.php");
    exit();
}
