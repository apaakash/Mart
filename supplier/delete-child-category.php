<?php
include "init.php";
include "config.php";

// Redirect to login page if supplier is not logged in
if (!isset($_SESSION['supplier_id'])) {
    header("Location: login.php");
    exit();
}

// Check if `id` is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Check if the child category exists
    $query = "SELECT * FROM child_categories WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Delete the child category
        $deleteQuery = "DELETE FROM child_categories WHERE id = $id";
        if (mysqli_query($conn, $deleteQuery)) {
            echo "<script>alert('Child Category deleted successfully!'); window.location.href='product_management.php';</script>";
        } else {
            echo "<script>alert('Error deleting child category.'); window.location.href='product_management.php';</script>";
        }
    } else {
        echo "<script>alert('Child Category not found.'); window.location.href='product_management.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='product_management.php';</script>";
}

mysqli_close($conn);
