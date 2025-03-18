<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get image name
    $stmt = $conn->prepare("SELECT image FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();

    // Delete image file
    if ($item && file_exists("../P-item/" . $item['image'])) {
        unlink("../P-item/" . $item['image']);
    }

    // Delete item from database
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully!'); window.location.href='manage-product.php';</script>";
    }
    $stmt->close();
}
?>
