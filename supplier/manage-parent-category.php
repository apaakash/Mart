<?php
include 'config.php';
include "init.php";

// Handle category addition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $image = $_FILES['image']['name'];
    $target = "../C-items/" . basename($image);

    // Upload image
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO parent_categories (name, image) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $image);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Parent Category Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background: #f4f4f4;
            padding-top: 20px;
        }

        .button-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .button-container a {
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .button-container a:hover {
            background: #0056b3;
        }

        .container {
            width: 90%;
            max-width: 800px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-left: 80px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .form-container input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .form-container button {
            background: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .form-container button:hover {
            background: #45a049;
        }

        @media (max-width: 600px) {
            .form-container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
<?php renderSidebar(); ?>
    <div class="button-container">
        <a href="product_management.php">Manage Category</a>
        <a href="manage-child-Category.php">Add Child Category</a>
        <a href="manage-product.php">Add Product</a>
    </div>

    <div class="container">
        <h2>Parent Category Management</h2>

        <form class="form-container" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Parent Category Name" required>
            <input type="file" name="image" required>
            <button type="submit">Add Parent Category</button>
        </form>
    </div>
</body>

</html>
