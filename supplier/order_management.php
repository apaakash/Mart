<?php
include "init.php";
include "config.php";

// Redirect to login page if supplier is not logged in
if (!isset($_SESSION['supplier_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch order status counts from the database
$query = "SELECT 
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_count,
            SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) AS processing_count,
            SUM(CASE WHEN status = 'Shipped' THEN 1 ELSE 0 END) AS shipped_count,
            SUM(CASE WHEN status = 'Delivered' THEN 1 ELSE 0 END) AS delivered_count,
            SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelled_count
          FROM orders";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$pending = $row['pending_count'] ?? 0;
$processing = $row['processing_count'] ?? 0;
$shipped = $row['shipped_count'] ?? 0;
$delivered = $row['delivered_count'] ?? 0;
$cancelled = $row['cancelled_count'] ?? 0;

// Fetch recent orders
$orderQuery = "SELECT id, user_id, name, product_name, quantity, total_price, status FROM orders ORDER BY order_date DESC LIMIT 5";
$orderResult = mysqli_query($conn, $orderQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sidebar Menu</title>
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'>
    <link rel="stylesheet" href="style.css">
    <style>
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

        #order_header {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #order_header h1 {
            font-size: 24px;
            color: #333;
        }

        .recent-orders {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .recent-orders h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f1f1f1;
        }

        .action-btn {
            background-color: #2a9d8f;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-btn:hover {
            background-color: #21867a;
        }

        .statistics {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 220px;
            text-align: center;
        }

        .stat-card h3 {
            font-size: 18px;
            color: #333;
        }

        .stat-card p {
            font-size: 24px;
            font-weight: bold;
            color: #2a9d8f;
        }
    </style>
    </style>
</head>

<body>
    <?php renderSidebar(); ?>
    <section class="home">
        <div class="dashboard-container">
            <div class="main-content">
                <header id="order_header">
                    <h1>Order Management</h1>
                </header>
                <div class="statistics">
                    <div class="stat-card">
                        <h3>Pending</h3>
                        <p><?php echo $pending; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Processing</h3>
                        <p><?php echo $processing; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Shipped</h3>
                        <p><?php echo $shipped; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Cancelled</h3>
                        <p><?php echo $cancelled; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Delivered</h3>
                        <p><?php echo $delivered; ?></p>
                    </div>
                </div>
                <div class="recent-orders">
                    <h2>Recent Orders</h2>
                    <table>
                        <tr>
                            <th>Order ID</th>
                            <th>User ID</th>
                            <th>Customer Name</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        <?php while ($order = mysqli_fetch_assoc($orderResult)) : ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo $order['user_id']; ?></td>
                                <td><?php echo htmlspecialchars($order['name']); ?></td>
                                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                <td><?php echo $order['quantity']; ?></td>
                                <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td>
                                    <a href="update-orders.php?id=<?php echo $order['id']; ?>" class="action-btn" style="background-color: #f4a261;">Update</a>
                                    <a href="delete-orders.php?id=<?php echo $order['id']; ?>" class="action-btn" style="background-color: #e63946;" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <script src="script.js"></script>
</body>

</html>