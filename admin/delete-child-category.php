<?php
include '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete category
    $stmt = $conn->prepare("DELETE FROM child_categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Category deleted successfully!'); window.location.href='manage-child-category.php';</script>";
    }
    $stmt->close();
}
?>
