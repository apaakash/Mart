<?php
include "config.php";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    $payment_status = empty($payment_id) ? 'Failed' : 'Paid';
    $order_status = empty($payment_id) ? 'Cancelled' : 'Pending';

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
        $order_id = $stmt->insert_id; // Get the last inserted order ID

        // If payment is successful, clear the cart
        if ($payment_status === 'Paid') {
            $deleteCart = $conn->prepare("DELETE FROM cart WHERE u_id = ?");
            $deleteCart->bind_param("i", $user_id);
            $deleteCart->execute();
        }

        // Send email to user with order details
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Use Gmail's SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'demowork10001@gmail.com'; // Replace with your email
            $mail->Password = 'ahzkmvqzvvmhklok'; // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('demowork10001@gmail.com', 'E-commerce Website');
            $mail->addAddress($email, $name); // Add user's email address here

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Order Confirmation - Payment Successful';
            $mail->Body = "
                <h2>Thank you for your purchase, $name!</h2>
                <p>Your payment has been successfully processed. Here are your order details:</p>
                <ul>
                    <li><strong>Order ID:</strong> $order_id</li>
                    <li><strong>Payment ID:</strong> $payment_id</li>
                    <li><strong>Amount:</strong> ₹" . number_format($amount, 2) . "</li>
                    <li><strong>Address:</strong> $address</li>
                    <li><strong>Email:</strong> $email</li>
                    <li><strong>Product:</strong> $productName</li>
                    <li><strong>Quantity:</strong> $quantity</li>
                </ul>
                <p>Thank you for shopping with us!</p>";

            $mail->send();
            error_log("Success: Email sent to user");
        } catch (Exception $e) {
            error_log("Error: Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
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
