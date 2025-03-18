<?php
include "init.php";
// Redirect to login page if supplier is not logged in
if (!isset($_SESSION['supplier_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'>
    <link rel="stylesheet" href="style.css">
    <title>Notifications</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        .dashboard-container {
            display: flex;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        #notify_header {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #notify_header h1 {
            font-size: 24px;
            color: #333;
        }

        .notifications-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .notifications-container h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .notification-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-item h3 {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        .notification-item p {
            font-size: 14px;
            color: #555;
        }

        .notification-time {
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <?php renderSidebar(); ?>
    <section class="home">
        <div class="dashboard-container">
            <div class="main-content">
                <header id="notify_header">
                    <h1>Notifications</h1>
                </header>
                <div class="notifications-container">
                    <h2>Recent Notifications</h2>
                    <div class="notification-item">
                        <div>
                            <h3>New Order Received</h3>
                            <p>You have received a new order for Product XYZ.</p>
                        </div>
                        <span class="notification-time">2 hours ago</span>
                    </div>
                    <div class="notification-item">
                        <div>
                            <h3>Payment Processed</h3>
                            <p>Payment for Order #1234 has been successfully processed.</p>
                        </div>
                        <span class="notification-time">4 hours ago</span>
                    </div>
                    <div class="notification-item">
                        <div>
                            <h3>New Message</h3>
                            <p>You have a new message from customer support.</p>
                        </div>
                        <span class="notification-time">1 day ago</span>
                    </div>
                    <!-- Add more notifications as needed -->
                </div>
            </div>
        </div>
    </section>
    <script src="script.js"></script>
</body>

</html>