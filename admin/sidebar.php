<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: #f4f4f4;
        }

        .sidebar {
            width: 200px;
            background: #4CAF50;
            color: #fff;
            padding: 20px;
            position: fixed;
            height: 100%;
            transition: width 0.3s ease;
            left: 0;
            top: 0;
        }

        .pro {
            margin-bottom: 20px;
            font-family: 30px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .sidebar a:hover {
            background: #45a049;
            transform: scale(1.05);
        }

        .main-content {
            margin-left: 220px;
            /* Space for sidebar */
            padding: 20px;
            flex: 1;
            transition: margin-left 0.3s ease;
            width: calc(100% - 220px);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 150px;
            }

            .main-content {
                margin-left: 170px;
                width: calc(100% - 170px);
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a class="pro" href="profile.php" style="text-decoration: none;">Admin Profile</a>

        <?php if (isset($_SESSION['admin_id'])) { ?>
            <a href="index.php">Dashboard</a>
            <a href="manage-category.php">Manage category</a>
            <a href="manage-order.php">Manage Orders</a>
            <a href="manage-user.php">Manage Users</a>
            <a href="manage-inventory.php">Inventory</a>
            <a href="manage-payments.php">Payment Management</a>
            <a href="supplier-manage.php">Supplier Management</a>
            <a href="logout.php">Logout</a>
        <?php } else { ?>
            <a href="admin-login.php">Login</a>
        <?php } ?>
    </div>
</body>

</html>