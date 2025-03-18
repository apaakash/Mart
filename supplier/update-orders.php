<?php
include "config.php";
include "init.php";
// Check if order ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Order ID is missing.");
}

$order_id = $_GET['id'];

// Fetch order details
$query = "SELECT * FROM orders WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

// If order not found
if (!$order) {
    die("Order not found.");
}

// Update order if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_status = $_POST['status'];

    $updateQuery = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $order_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Order updated successfully!'); window.location='order_management.php';</script>";
    } else {
        echo "Error updating order.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Order</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #2c3e50, #56ab2f);
            animation: fadeIn 0.8s ease-in-out;
        }

        /* Container */
        .reg-container {
            background: #ffffff;
            padding: 20px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: slideUp 0.5s ease-in-out;
        }

        /* Heading */
        h2 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 5px;
        }

        /* Underline Animation */
        h2::after {
            content: "";
            width: 50%;
            height: 3px;
            background: #56ab2f;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 5px;
            animation: growLine 0.6s ease-in-out;
        }

        /* Form */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Inputs */
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background: #f9f9f9;
            transition: 0.3s ease;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.6s ease-in-out forwards;
        }

        /* Read-only fields */
        input[disabled] {
            background: #e0e0e0;
            color: #555;
            cursor: not-allowed;
        }

        /* Input Focus */
        input:focus,
        select:focus {
            border-color: #56ab2f;
            outline: none;
            background: #fff;
            box-shadow: 0 0 10px rgba(86, 171, 47, 0.3);
            transform: scale(1.03);
        }

        /* Select Dropdown */
        select {
            appearance: none;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path fill="%2356ab2f" d="M7 10l5 5 5-5z"/></svg>') no-repeat right 10px center;
            background-size: 16px;
            padding-right: 30px;
        }

        /* Buttons */
        button {
            width: 100%;
            padding: 12px;
            background: #56ab2f;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            opacity: 0;
            animation: fadeInUp 0.6s ease-in-out 0.2s forwards;
        }

        /* Button Hover */
        button:hover {
            background: #3a8e1a;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        /* Cancel Link */
        a {
            display: inline-block;
            text-decoration: none;
            color: #d63031;
            font-weight: bold;
            margin-top: 10px;
            transition: color 0.3s ease-in-out;
        }

        a:hover {
            color: #c0392b;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .reg-container {
                padding: 25px 20px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes growLine {
            from {
                width: 0;
            }

            to {
                width: 50%;
            }
        }
    </style>
</head>

<body>
    <?php renderSidebar(); ?>
    <div class="reg-container">
        <h2>Update Order #<?php echo $order['id']; ?></h2>
        <form method="post">
            <input type="text" value="<?php echo htmlspecialchars($order['name']); ?>" name="firstname" disabled><br>
            <input type="text" name="shop" value="<?php echo htmlspecialchars($order['name']); ?>" disabled><br>
            <input type="number" name="email" value="<?php echo $order['quantity']; ?>" disabled><br>
            <input type="text" name="mobile" value="$<?php echo number_format($order['total_price'], 2); ?>" disabled><br>
            <select name="status">
                <option value="Pending" <?php if ($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Processing" <?php if ($order['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                <option value="Shipped" <?php if ($order['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                <option value="Delivered" <?php if ($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                <option value="Cancelled" <?php if ($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
            </select><br>
            <button type="submit">Update Order</button>
            <a href="order_management.php">Cancel</a>
        </form>
    </div>
</body>

</html>