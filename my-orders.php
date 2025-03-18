<?php
session_start(); // Start session at the very beginning

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

include 'config.php';
include './Nav/Manu.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo "<p style='text-align:center; color:red;'>Please log in to view your orders.</p>";
    exit;
}

// Fetch user orders
$order_query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
$result = mysqli_query($conn, $order_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            justify-content: center;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .order {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            background: #fafafa;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.5s ease-in forwards;
        }

        .order:nth-child(even) {
            background: #f0f8ff;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .order-info {
            flex-grow: 1;
        }

        .order-info h3 {
            margin: 0;
            color: #333;
        }

        .order-info p {
            margin: 5px 0;
            color: #555;
        }

        .status {
            padding: 5px 12px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }

        .status.Pending {
            background: #ffc107;
            color: #fff;
        }

        .status.Processing {
            background: #17a2b8;
            color: #fff;
        }

        .status.Shipped {
            background: #007bff;
            color: #fff;
        }

        .status.Delivered {
            background: #28a745;
            color: #fff;
        }

        .status.Cancelled {
            background: #dc3545;
            color: #fff;
        }

        .payment-status {
            padding: 5px 12px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }

        .payment-status.Paid {
            background: #28a745;
            color: #fff;
        }

        .payment-status.Failed {
            background: #dc3545;
            color: #fff;
        }

        .order-date {
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="head">My Orders</h2>
    </div>
    <div class="container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($order = mysqli_fetch_assoc($result)): ?>
                <div class="order">
                    <div class="order-info">
                        <h3><?php echo htmlspecialchars($order['product_name']); ?></h3>
                        <p>Quantity: <?php echo htmlspecialchars($order['quantity']); ?></p>
                        <p>Total Price: ₹<?php echo htmlspecialchars($order['total_price']); ?></p>
                        <p class="order-date">Ordered on: <?php echo date('d M Y, H:i A', strtotime($order['order_date'])); ?></p>
                        <p>payment_id: <?php echo htmlspecialchars($order['payment_id']); ?></p>
                        <p>Payment Status: <?php echo htmlspecialchars($order['payment_status']); ?></p> 
                    </div>
                    <span class="status <?php echo htmlspecialchars($order['status']); ?>">
                        <?php echo htmlspecialchars($order['status']); ?>
                    </span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; color:#555;">No orders found.</p>
        <?php endif; ?>

    </div>
    <?php include "./Nav/footer.php"; ?>
</body>

</html>
