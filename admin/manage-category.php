<?php
include "../config.php";
include 'sidebar.php';

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='admin-login.php';</script>";
    exit();
}
// Fetch existing parent categories
$result = $conn->query("SELECT * FROM parent_categories");
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            background: green;
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
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #4caf50;
            color: #fff;
        }

        .action-buttons button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="button-container">
        <a href="manage-parent-category.php">Add Parent Category</a>
        <a href="manage-child-category.php">Add Child Category</a>
        <a href="manage-product.php">Add Product</a>
    </div>

    <div class="container">
        <table>
            <tr>
                <th>Image</th>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($categories as $category) : ?>
                <tr>
                    <td><img src="../C-items/<?= $category['image'] ?>" width="50" height="50"></td>
                    <td><?= $category['name'] ?></td>
                    <td class="action-buttons">
                        <a href="update-category.php?id=<?= $category['id'] ?>" class="edit-btn"><img src="./img/update.png" alt=""></a>
                        <a href="delete-category.php?id=<?= $category['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this category?');"><img src="./img/delete.png" alt=""></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>