<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch category details
    $result = $conn->query("SELECT image FROM parent_categories WHERE id = $id");
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = "../C-items/" . $row['image'];

        // Delete category image if it exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete category from database
        $conn->query("DELETE FROM parent_categories WHERE id = $id");
    }

    header("Location: manage-category.php");
    exit();
} else {
    echo "Invalid Request!";
    exit();
}
?>
