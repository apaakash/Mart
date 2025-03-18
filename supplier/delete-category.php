<?php
include "init.php";
include "config.php";

// Redirect to login page if supplier is not logged in
if (!isset($_SESSION['supplier_id'])) {
    header("Location: login.php");
    exit();
}

// Check if `id` is set in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='product_management.php';</script>";
    exit();
}

$id = intval($_GET['id']);

// Check if the category exists
$query = "SELECT * FROM parent_categories WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Category not found.'); window.location.href='product_management.php';</script>";
    exit();
}

// Check if the category has child categories
$childQuery = "SELECT id FROM child_categories WHERE parent_id = $id";
$childResult = mysqli_query($conn, $childQuery);

if (mysqli_num_rows($childResult) > 0) {
    echo "<script>alert('Cannot delete category. It has child categories.'); window.location.href='product_management.php';</script>";
    exit();
}

// Delete the parent category
$deleteQuery = "DELETE FROM parent_categories WHERE id = $id";
if (mysqli_query($conn, $deleteQuery)) {
    echo "<script>alert('Category deleted successfully!'); window.location.href='product_management.php';</script>";
} else {
    echo "<script>alert('Error deleting category.'); window.location.href='product_management.php';</script>";
}
?>
