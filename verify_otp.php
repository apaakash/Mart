<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Session expired. Please register or login again.'); window.location.href = 'register.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get OTP from form and user session
    $otp_input = trim(implode('', $_POST['otp']));
    $user_id = $_SESSION['user_id'];

    include "config.php"; // Database connection

    // Fetch the stored OTP for the user
    $query = "SELECT otp FROM otp_verification WHERE user_id='$user_id' ORDER BY created_at DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if ($otp_input == $row['otp']) {
            // Update user status to 'active' after successful OTP verification
            $update_query = "UPDATE users SET status='active' WHERE id='$user_id'";
            mysqli_query($conn, $update_query);

            // Redirect to index page after success
            echo "<script>
                    alert('Login Successful!');
                    window.location.href = 'index.php';
                </script>";
            exit();
        } else {
            echo "<script>alert('Invalid OTP! Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No OTP found. Please request a new OTP.');</script>";
    }

    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #a8e063 0%, #56ab2f 100%);
            font-family: "Poppins", sans-serif;
        }

        .container {
            background: #ffffff;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .otp-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .otp-container input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            outline: none;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #56ab2f;
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background: #3a8e1a;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Verify OTP</h2>
        <form method="POST">
            <div class="otp-container">
                <input type="text" name="otp[]" maxlength="1" required oninput="moveToNext(this, 1)">
                <input type="text" name="otp[]" maxlength="1" required oninput="moveToNext(this, 2)">
                <input type="text" name="otp[]" maxlength="1" required oninput="moveToNext(this, 3)">
                <input type="text" name="otp[]" maxlength="1" required oninput="moveToNext(this, 4)">
                <input type="text" name="otp[]" maxlength="1" required oninput="moveToNext(this, 5)">
                <input type="text" name="otp[]" maxlength="1" required oninput="moveToNext(this, 6)">
            </div>
            <button type="submit">Verify</button>
        </form>
    </div>
    <script>
        function moveToNext(input, index) {
            let inputs = document.querySelectorAll(".otp-container input");

            if (input.value.length === 1 && index < inputs.length) {
                inputs[index].focus(); // Move to the next input
            }

            // Move back if user presses backspace
            input.addEventListener("keydown", function(e) {
                if (e.key === "Backspace" && index > 1 && input.value.length === 0) {
                    inputs[index - 2].focus();
                }
            });
        }
    </script>
</body>

</html>
