<?php
include "config.php";

// Check if order ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Order ID is missing.");
}

$order_id = $_GET['id'];

// Delete order
$query = "DELETE FROM orders WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $order_id);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Order deleted successfully!'); window.location='manage-order.php';</script>";
} else {
    echo "Error deleting order.";
}
?>
