<?php
include "init.php";
include 'config.php';
// Redirect to login page if supplier is not logged in
if (!isset($_SESSION['supplier_id'])) {
  header("Location: login.php");
  exit();
}

// Fetch Active vs Inactive User Data
$query = "SELECT status, COUNT(*) as count FROM users GROUP BY status";
$result = mysqli_query($conn, $query);
$usersData = ["active" => 0, "inactive" => 0];

while ($row = mysqli_fetch_assoc($result)) {
  if ($row['status'] == 'active') {
    $usersData['active'] = $row['count'];
  } else {
    $usersData['inactive'] = $row['count'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css'>
  <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: green;
      display: flex;
    }

    .dashboard-container {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      gap: 20px;
      padding: 20px;
    }

    .main-content {
      flex: 1;
      padding: 10px;
    }

    .overview {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }

    .card {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .grid-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .chart-container {
      width: 100%;
      max-width: 600px;
      margin: auto;
    }

    .wide-card {
      grid-column: span 2;
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

    .home {
      position: absolute;
      top: 0;
      left: 250px;
      height: 100vh;
      width: calc(100% - 250px);
      background-color: green;
      transition: all 0.5s ease;
    }

    .main {
      padding: 20px;
      background-color: green;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin: 20px;
    }
  </style>
</head>

<body>
  <?php renderSidebar(); ?>
  <section class="home">
    <div class="dashboard-container">
      <div class="main-content">
        <header id="order_header">
          <h1>Dashboard</h1>
        </header>

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
      </div>
    </div>
  </section>
  <script src="script.js"></script>
</body>

</html>
