<?php
include "config.php";
include "init.php";
$UserError1 = false;
$UserError = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $shop = $_POST['shop'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO suppliers (firstname, shop, email, mobile, address, password) 
            VALUES ('$firstname', '$shop', '$email', '$mobile', '$address', '$hashed_password')";

    if (mysqli_query($conn, $query)) {
        $UserError1 = true;
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
    <title>Supplier Registration</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: white;
            font-family: "Poppins", sans-serif;
        }

        .reg-container {
            background: #ffffff;
            padding: 10px 40px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(35, 27, 27, 0.1);
            width: 100%;
            max-width: 400px;
            margin-left: 200px;
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
        textarea {
            width: 93%;
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 12px;
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

        @media (max-width: 600px) {
            .reg-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
<?php renderSidebar(); ?>
    <div class="reg-container">
        <h2>Register/<a href="login.php">Login</a></h2>
        <?php echo $UserError ? "<h1 class='errorText'>Some Error!</h1>" : ""; ?>
        <?php echo $UserError1 ? "<h1 class='errorText'>Registration successful! Wait for admin approval.</h1>" : ""; ?>
        <form method="POST" action="">
            <input type="text" name="firstname" placeholder="Enter your first name" required>
            <input type="text" name="shop" placeholder="Enter your shop name" required>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="text" name="mobile" placeholder="Enter your mobile number" required>
            <textarea name="address" placeholder="Enter your address" required></textarea>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Register</button>
        </form>
        <br>
        <a href="../index.php" style="text-decoration: none;">GOTO MART</a>
    </div>
</body>
</html>