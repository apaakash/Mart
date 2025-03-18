<?php
include "init.php";
include "config.php";  // Database connection

// Redirect if not logged in
if (!isset($_SESSION['supplier_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch Total Sales
$totalSalesQuery = "SELECT SUM(total_price) AS total_sales FROM orders";
$totalSalesResult = mysqli_query($conn, $totalSalesQuery);
$totalSales = mysqli_fetch_assoc($totalSalesResult)['total_sales'] ?? 0;

// Fetch Complete Orders
$completeOrdersQuery = "SELECT COUNT(*) AS complete_orders FROM orders WHERE status = 'Delivered'";
$completeOrdersResult = mysqli_query($conn, $completeOrdersQuery);
$completeOrders = mysqli_fetch_assoc($completeOrdersResult)['complete_orders'] ?? 0;

// Fetch New Customers in the Last 30 Days
$newCustomersQuery = "SELECT COUNT(DISTINCT user_id) AS new_customers FROM orders WHERE order_date >= NOW() - INTERVAL 30 DAY";
$newCustomersResult = mysqli_query($conn, $newCustomersQuery);
$newCustomers = mysqli_fetch_assoc($newCustomersResult)['new_customers'] ?? 0;

// Fetch Recent Orders (Last 5) including status
$recentOrdersQuery = "SELECT order_date, total_price, user_id, quantity, status FROM orders ORDER BY order_date DESC LIMIT 5";
$recentOrdersResult = mysqli_query($conn, $recentOrdersQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'>
    <link rel="stylesheet" href="style.css">
    <title>Performance Analytics</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color:green;
        }

        .dashboard-container {
            display: flex;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        #analytics_header {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #analytics_header h1 {
            font-size: 24px;
            color: #333;
        }

        .analytics-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .analytics-container h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .kpi-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .kpi-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 30%;
            text-align: center;
        }

        .kpi-card h3 {
            font-size: 18px;
            color: #333;
        }

        .kpi-card p {
            font-size: 24px;
            font-weight: bold;
            color: #2a9d8f;
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
    </style>
</head>

<body>
    <?php renderSidebar(); ?>
    <section class="home">
        <div class="dashboard-container">
            <div class="main-content">
                <header id="analytics_header">
                    <h1>Performance Analytics</h1>
                </header>
                <div class="analytics-container">
                    <h2>Key Performance Indicators</h2>
                    <div class="kpi-cards">
                        <div class="kpi-card">
                            <h3>Total Sales</h3>
                            <p>₹<?php echo number_format($totalSales, 2); ?></p>
                        </div>
                        <div class="kpi-card">
                            <h3>New Customers</h3>
                            <p><?php echo $newCustomers; ?></p>
                        </div>
                        <div class="kpi-card">
                            <h3>Complete Orders</h3>
                            <p><?php echo $completeOrders; ?></p>
                        </div>
                    </div>
                    <h2>Recent Orders</h2>
                    <table>
                        <tr>
                            <th>Date</th>
                            <th>Sales</th>
                            <th>User ID</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                        <?php while ($row = mysqli_fetch_assoc($recentOrdersResult)) { ?>
                            <tr>
                                <td><?php echo $row['order_date']; ?></td>
                                <td>₹<?php echo number_format($row['total_price'], 2); ?></td>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo ucfirst($row['status']); ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <script src="script.js"></script>
</body>

</html>