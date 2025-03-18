<?php

session_start();
$UserError1 = false;
$UserError = false;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the reCAPTCHA response token from the form
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $secret_key = '6Legj50qAAAAAOllnvKKOCr39sYhp-a60hxQo2Xm';  // Secret Key for verification

    // Check if the reCAPTCHA response is empty
    if (empty($recaptcha_response)) {
        $UserError1 = true;
    }

    // Send the reCAPTCHA response to Google’s server for validation
    $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $recaptcha_response);
    $response_keys = json_decode($response);  // Decode the JSON response from Google

    // Check if the verification was successful
    if ($response_keys->success) {
        // reCAPTCHA verification successful, proceed with login
        $email = $_POST['email'];
        $password = $_POST['password'];

        include "config.php";  // Database connection

        // Sanitize user input to prevent SQL injection
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);

        // Query to check if user exists in `users`
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Fetch user data
            $row = mysqli_fetch_assoc($result);

            // Verify password
            if ($password == $row['password']) {  // No hashing as per your preference
                $_SESSION['user_id'] = $row['id'];  // Store user ID in session
                header('Location: index.php');  // Redirect to home page after successful login
                exit();
            } else {
                echo "<script>alert('Invalid login credentials!!')</script>";
            }
        } else {
            echo "<script>alert('No user found with that email!!')</script>";
        }

        mysqli_close($conn);
    } else {
        $UserError = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

        .login-container {
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

        input[type="email"],
        input[type="password"] {
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

        input:focus {
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
            .login-container {
                padding: 30px 20px;
            }
        }

        .errorText {
            color: red;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php echo $UserError ? "<h1 class='errorText'>ERROR TRY ONE MORE !</h1>" : ""; ?>
        <?php echo $UserError1 ? "<h1 class='errorText'>Please complete the reCAPTCHA verification.</h1>" : ""; ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>

            <!-- reCAPTCHA Widget -->
            <div class="g-recaptcha" data-sitekey="6Legj50qAAAAALZZmfZBYCpqIPHzmiJp2p8dlQJJ"></div>

            <button type="submit">Login</button>
            <br><br>
            <a href="register.php">Register</a>
        </form>
    </div>
</body>

</html>