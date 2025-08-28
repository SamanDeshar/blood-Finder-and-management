<?php
session_start();
require 'vendor/autoload.php'; // For PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Store all form fields in session
  $_SESSION['form_data'] = [
    'name' => $_POST['name'],
    'email' => $_POST['email'],
    'blood' => $_POST['blood'],
    'address' => $_POST['address'],
    'reason' => $_POST['reason'],
    'latitude' => $_POST['latitude'],
    'longitude' => $_POST['longitude']
  ];

  // Generate OTP and save it
  $otp = rand(100000, 999999);
  $_SESSION['otp'] = $otp;

  // Send email with OTP
  $mail = new PHPMailer(true);
  try {
    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '1samandeshar1@gmail.com';
    $mail->Password = 'qvbv juak xpau lmji';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email content
    $mail->setFrom('your_gmail@gmail.com', 'HamroBlood');
    $mail->addAddress($_POST['email'], $_POST['name']);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "Your OTP is: $otp";

    $mail->send();
    header("Location: verify_otp.php");
    exit();
  } catch (Exception $e) {
    echo "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}
?>