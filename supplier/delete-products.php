<?php
include 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid request!'); window.history.back();</script>";
    exit();
}

$product_id = intval($_GET['id']);

$query = "DELETE FROM items WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
                alert('Product deleted successfully!');
                window.location.href = 'product_management.php'; 
            </script>";
    } else {
        echo "<script>alert('Error deleting product.'); window.history.back();</script>";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('Error preparing query.'); window.history.back();</script>";
}

mysqli_close($conn);
?>
