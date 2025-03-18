<?php
include "../config.php";
include "sidebar.php";

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='admin-login.php';</script>";
    exit();
}

$category_name = "";
$image = "";
$update_id = "";

// Check if an edit request was made
if (isset($_GET['id'])) {
    $update_id = $_GET['id'];
    $edit_result = $conn->query("SELECT * FROM parent_categories WHERE id = $update_id");
    
    if ($edit_result->num_rows > 0) {
        $edit_row = $edit_result->fetch_assoc();
        $category_name = $edit_row['name'];
        $image = $edit_row['image'];
    } else {
        echo "Category not found!";
        exit();
    }
}

// Handle form submission for updating category
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $new_image = $_FILES['image']['name'];
    $target = "../C-items/" . basename($new_image);

    if (!empty($new_image)) {
        // Delete old image if a new image is uploaded
        if (file_exists("../C-items/" . $image)) {
            unlink("../C-items/" . $image);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $conn->query("UPDATE parent_categories SET name='$name', image='$new_image' WHERE id=$update_id");
    } else {
        $conn->query("UPDATE parent_categories SET name='$name' WHERE id=$update_id");
    }

    header("Location: category-list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Parent Category</title>
    <style>
        body{
            background-color: green;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Parent Category</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" value="<?= $category_name ?>" placeholder="Category Name" required>
        <input type="file" name="image">
        <?php if ($image) : ?>
            <img src="../C-items/<?= $image ?>" width="100"><br>
        <?php endif; ?>
        <button type="submit">Update Category</button>
    </form>
</div>

</body>
</html>
