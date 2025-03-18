<?php
session_start();
include("email.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $password = $_POST['password']; // Get password from form

    include "config.php";

    // Check if email or mobile number already exists
    $query = "SELECT * FROM users WHERE email='$email' OR mobile='$mobile'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "Email or Mobile Number already exists!";
    } else {
        // Insert user details into users table
        $insert_query = "INSERT INTO users (firstname, lastname, email, mobile, address, password) 
                            VALUES ('$firstname', '$lastname', '$email', '$mobile', '$address', '$password')";

        if (mysqli_query($conn, $insert_query)) {
            $user_id = mysqli_insert_id($conn);

            // Generate a 6-digit OTP
            $otp = rand(100000, 999999);

            // Store OTP in otp_verification table
            $otp_query = "INSERT INTO otp_verification (user_id, otp) VALUES ('$user_id', '$otp')";
            mysqli_query($conn, $otp_query);

            // Send OTP to the user's email
            send_otp($email, "OTP for Registration", $otp);

            $_SESSION['user_id'] = $user_id;
            echo "OTP sent to your email. Please verify!";
            header("location:verify_otp.php");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #a8e063 0%, #56ab2f 100%);
            font-family: "Poppins", sans-serif;
        }

        .container {
            background: #ffffff;
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            animation: slideIn 1s ease-in-out;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"],
        textarea {
            width: 93%;
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 12px;
            transition: all 0.3s ease;
            outline: none;
            font-size: 14px;
            background: #f9f9f9;
        }

        input:focus,
        textarea:focus {
            border-color: #56ab2f;
            box-shadow: 0 0 10px rgba(86, 171, 47, 0.3);
            transform: scale(1.03);
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
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background: #3a8e1a;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Register</h2>

        <form method="POST" action="">
            <input type="text" name="firstname" placeholder="Enter your first name" required>
            <input type="text" name="lastname" placeholder="Enter your last name" required>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="text" name="mobile" placeholder="Enter your mobile number" required>
            <textarea name="address" placeholder="Enter your address" required></textarea>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Register</button>
            <br><br>
            <a href="login.php">Login</a>
        </form>


    </div>
</body>

</html>