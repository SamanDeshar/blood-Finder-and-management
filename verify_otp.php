<?php
session_start();
include('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $entered_otp = implode('', $_POST['otp']);


    if ($entered_otp == $_SESSION['otp']) {
        // Extract form data from session
        $data = $_SESSION['form_data'];
        $name = mysqli_real_escape_string($conn, $data['name']);
        $email = mysqli_real_escape_string($conn, $data['email']);   
        $blood_group = mysqli_real_escape_string($conn, $data['blood']);
        $address = mysqli_real_escape_string($conn, $data['address']);
        $reason = mysqli_real_escape_string($conn, $data['reason']);
        $latitude = mysqli_real_escape_string($conn, $data['latitude']);
        $longitude = mysqli_real_escape_string($conn, $data['longitude']);

        $sql = "INSERT INTO need_blood (name, email ,blood_group, reason, address, latitude, longitude) 
                VALUES ('$name','$email', '$blood_group', '$reason', '$address', '$latitude', '$longitude')";

        if (mysqli_query($conn, $sql)) {
            header("Location: need_blood.php?verified=1");
        exit();
           
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>Enter the 6-digit OTP</h2>
        <form method="post" action="">
            <div class="otp-input">
                <!-- Create 6 separate input fields for better UX -->
                <input type="text" name="otp[]" maxlength="1" pattern="\d*" required>
                <input type="text" name="otp[]" maxlength="1" pattern="\d*" required>
                <input type="text" name="otp[]" maxlength="1" pattern="\d*" required>
                <input type="text" name="otp[]" maxlength="1" pattern="\d*" required>
                <input type="text" name="otp[]" maxlength="1" pattern="\d*" required>
                <input type="text" name="otp[]" maxlength="1" pattern="\d*" required>
            </div>
            <input type="submit" class="verify-btn" value="Verify OTP">
        </form>
    </div>

    <script>
        // Automatically focus next input on typing
        const inputs = document.querySelectorAll('.otp-input input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
        });
    </script>
</body>
</html>
