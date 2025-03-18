<?php
include "config.php";
include "init.php";
$UserError1 = false;
$UserError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM suppliers WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        if ($row['status'] === 'approved') {
            $_SESSION['supplier_id'] = $row['id'];
            $_SESSION['supplier_name'] = $row['name'];
            header("Location: index.php");
            exit();
        } else {
            $UserError1 = true;
        }
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
    <title>Supplier Login</title>
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

        .login-container {
            background: #ffffff;
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(82, 55, 55, 0.1);
            animation: slideIn 1s ease-in-out;
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
    <?php renderSidebar(); ?>
    <div class="login-container">
        <h2>Supplier Login/<a href="register.php">Register</a></h2>

        <?php echo $UserError ? "<h1 class='errorText'>Invalid email or password.</h1>" : ""; ?>
        <?php echo $UserError1 ? "<h1 class='errorText'>Your request is still pending or has been denied.</h1>" : ""; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>
        <br>
        <a href="../index.php" style="text-decoration: none;">GOTO MART</a>
    </div>
</body>

</html>