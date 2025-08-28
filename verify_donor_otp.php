<?php
session_start();
include 'conn.php';

$message = "";
$success = false;

// Redirect if no OTP or form data in session
if (!isset($_SESSION['otp']) || !isset($_SESSION['form_data'])) {
  header('Location: donor_form_page.php'); // change to your actual form page filename
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $entered_otp = implode('', $_POST['otp']);

  if (!preg_match('/^\d{6}$/', $entered_otp)) {
    $message = "Invalid OTP format.";
  } else if ($entered_otp == $_SESSION['otp']) {
    $data = $_SESSION['form_data'];

    $stmt = $conn->prepare("INSERT INTO donor_details (donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, donor_address, latitude, longitude)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
      "sssisssdd",
      $data['fullname'],
      $data['mobileno'],
      $data['emailid'],
      $data['age'],
      $data['gender'],
      $data['blood'],
      $data['address'],
      $data['latitude'],
      $data['longitude']
    );

    if ($stmt->execute()) {
      unset($_SESSION['form_data'], $_SESSION['otp']);
      $success = true;
    } else {
      $message = "Error inserting data: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
  } else {
    $message = "Invalid OTP. Please try again.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>OTP Verification</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .otp-container {
      background: #fff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      text-align: center;
      width: 350px;
    }

    h2 {
      color: #333;
      font-size: 22px;
      margin-bottom: 20px;
    }

    .otp-input {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-bottom: 20px;
    }

    .otp-input input {
      width: 40px;
      height: 50px;
      font-size: 24px;
      text-align: center;
      border: 1px solid #ccc;
      border-radius: 6px;
      transition: border 0.3s ease;
    }

    .otp-input input:focus {
      outline: none;
      border-color: #007bff;
    }

    .verify-btn {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .verify-btn:hover {
      background-color: #0056b3;
    }

    .message {
      font-weight: bold;
      margin-bottom: 20px;
    }

    .message.error {
      color: red;
    }

    .message.success {
      color: green;
    }
  </style>
</head>

<body>
  <div class="otp-container">
    <h2>Enter the 6-digit OTP</h2>

    <?php if ($success): ?>
      <div class="message success">
        OTP verified successfully! Redirecting to home...
      </div>
      <script>
        setTimeout(() => {
          window.location.href = "home.php?donor_verified=1";
        }, 2000); // redirect after 2 seconds
      </script>
    <?php else: ?>
      <?php if ($message): ?>
        <div class="message error"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
      <form method="post" action="">
        <div class="otp-input">
          <input type="text" name="otp[]" maxlength="1" pattern="\d*" required autofocus>
          <input type="text" name="otp[]" maxlength="1" pattern="\d*" required>
          <input type="text" name="otp[]" maxlength="1" pattern="\d*" required>
          <input type="text" name="otp[]" maxlength="1" pattern="\d*" required>
          <input type="text" name="otp[]" maxlength="1" pattern="\d*" required>
          <input type="text" name="otp[]" maxlength="1" pattern="\d*" required>
        </div>
        <input type="submit" class="verify-btn" value="Verify OTP">
      </form>
    <?php endif; ?>
  </div>

  <script>
    // Auto focus next input on typing, backspace support
    const inputs = document.querySelectorAll('.otp-input input');
    inputs.forEach((input, index) => {
      input.addEventListener('input', () => {
        if (input.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
      });
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
          inputs[index - 1].focus();
        }
      });
    });
  </script>
</body>

</html>