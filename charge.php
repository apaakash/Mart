<?php
include "config.php";
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_id = $_POST['razorpay_payment_id'] ?? '';
    $amount = $_POST['amount'] / 100; // Convert paise to rupees
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $productName = $_POST['productName'] ?? '';
    $quantity = $_POST['quantity'] ?? 0;

    if (!$name || !$address || !$email || !$productName || $quantity == 0) {
        echo "Invalid order details.";
        exit();
    }

    // Check if payment_id exists
    $payment_status = empty($payment_id) ? 'Failed' : 'Paid'; // Corrected
    $order_status = empty($payment_id) ? 'Cancelled' : 'Pending'; // Corrected

    // Debugging logs
    error_log("Payment ID: " . $payment_id);
    error_log("Payment Status: " . $payment_status);

    // Insert order into database
    $stmt = $conn->prepare("INSERT INTO orders (user_id, name, address, email, product_name, quantity, total_price, payment_id, payment_status, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("issssidsis", $user_id, $name, $address, $email, $productName, $quantity, $amount, $payment_id, $payment_status, $order_status);

    if ($stmt->execute()) {
        // If payment is successful, clear the cart
        if ($payment_status === 'Paid') {
            $deleteCart = $conn->prepare("DELETE FROM cart WHERE u_id = ?");
            $deleteCart->bind_param("i", $user_id);
            $deleteCart->execute();
        }

        echo "success";
    } else {
        error_log("Order Insertion Error: " . $stmt->error);
        echo "Order failed.";
    }

    $stmt->close();
    $conn->close();
}
?>
