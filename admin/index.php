<?php
include 'sidebar.php';
include '../config.php';

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='admin-login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocery Management Admin Dashboard</title>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

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
            background: green;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
            flex: 1;
            transition: margin-left 0.3s ease;
        }

        .main-content h2 {
            color: #fff;
            margin-bottom: 40px;
            margin-left: 40%;
        }

        h3 {
            color: black;
            font-size: 50px;
        }

        .overview {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex: 1 1 calc(25% - 20px);
            max-width: calc(25% - 20px);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.8s forwards;
        }

        .card:nth-child(1) {
            animation-delay: 0.2s;
        }

        .card:nth-child(2) {
            animation-delay: 0.4s;
        }

        .card:nth-child(3) {
            animation-delay: 0.6s;
        }

        .card:nth-child(4) {
            animation-delay: 0.8s;
        }

        .card h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .card p {
            font-size: 24px;
            font-weight: bold;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .overview {
                flex-direction: column;
            }

            .card {
                max-width: 100%;
            }

            .main-content {
                margin-left: 170px;
            }
        }

        /* Pie Chart Styling */
        .chart-container {
            margin-top: 40px;
            text-align: center;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="main-content">
        <h2>Dashboard Overview</h2>
        <div class="overview">

            <!-- Total Products -->
            <div class="card">
                <h3>Total Products</h3>
                <?php
                $sql = "SELECT * FROM items";
                $res = mysqli_query($conn, $sql);
                $count_products = mysqli_num_rows($res);
                ?>
                <h1><?php echo $count_products; ?></h1>
            </div>

            <!-- Total Orders -->
            <div class="card">
                <h3>Total Orders</h3>
                <?php
                $sql2 = "SELECT * FROM orders";
                $res2 = mysqli_query($conn, $sql2);
                $count_orders = mysqli_num_rows($res2);
                ?>
                <h1><?php echo $count_orders; ?></h1>
            </div>

            <!-- Total Users -->
            <div class="card">
                <h3>Total Users</h3>
                <?php
                $sql3 = "SELECT * FROM users";
                $res3 = mysqli_query($conn, $sql3);
                $count_users = mysqli_num_rows($res3);
                ?>
                <h1><?php echo $count_users; ?></h1>
            </div>

            <!-- Total Revenue -->
            <div class="card">
                <h3>Total Revenue</h3>
                <?php
                $sql4 = "SELECT SUM(total_price) AS total_revenue FROM orders";
                $res4 = mysqli_query($conn, $sql4);
                $row4 = mysqli_fetch_assoc($res4);
                $total_revenue = $row4['total_revenue'] ?? 0;
                ?>
                <h1>₹<?php echo number_format($total_revenue, 2); ?></h1>
            </div>
        </div>

        <!-- Pie Chart Section -->
        <div class="chart-container">
            <h3>User Status Distribution</h3>
            <div id="piechart" style="width: 600px; height: 400px; margin: auto;"></div>
        </div>

    </div>

    <?php
    // Fetch Active and Inactive User Counts
    $sql_active = "SELECT COUNT(*) AS active_users FROM users WHERE status = 'active'";
    $res_active = mysqli_query($conn, $sql_active);
    $row_active = mysqli_fetch_assoc($res_active);
    $active_users = $row_active['active_users'] ?? 0;

    $sql_inactive = "SELECT COUNT(*) AS inactive_users FROM users WHERE status = 'inactive'";
    $res_inactive = mysqli_query($conn, $sql_inactive);
    $row_inactive = mysqli_fetch_assoc($res_inactive);
    $inactive_users = $row_inactive['inactive_users'] ?? 0;
    ?>

    <script type="text/javascript">
        // Load Google Charts
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        // Draw the Pie Chart
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Status', 'Number of Users'],
                ['Active Users', <?php echo $active_users; ?>],
                ['Inactive Users', <?php echo $inactive_users; ?>]
            ]);

            var options = {
                title: 'Active vs Inactive Users',
                pieHole: 0.4,
                colors: ['#28a745', '#dc3545'],
                backgroundColor: 'transparent'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>

</body>

</html>