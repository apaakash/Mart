<?php
include '../config.php';
include 'sidebar.php';

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='admin-login.php';</script>";
    exit();
}
// Add Child Category
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $parent_id = $_POST['parent_id'];
    $image = $_FILES['image']['name'];
    $target = "../Child-item/" . basename($image);

    // Upload image
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO child_categories (name, parent_id, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $parent_id, $image);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch Parent Categories
$parentResult = $conn->query("SELECT * FROM parent_categories");
$parents = [];
while ($row = $parentResult->fetch_assoc()) {
    $parents[] = $row;
}

// Fetch Child Categories
$childResult = $conn->query("SELECT child_categories.*, parent_categories.name AS parent_name 
    FROM child_categories 
    JOIN parent_categories ON child_categories.parent_id = parent_categories.id");
$childs = [];
while ($row = $childResult->fetch_assoc()) {
    $childs[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Child Categories</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: green;
            padding-top: 20px;
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
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        input,
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            background: #4caf50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #45a049;
        }

        .category-list {
            list-style: none;
            padding: 0;
        }

        .category-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .actions a {
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 5px;
            margin-left: 120px;
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
    </style>
</head>

<body>
<div class="button-container">
        <a href="manage-parent-category.php">Add Parent Category</a>
        <a href="manage-Category.php">Manage Category</a>
        <a href="manage-product.php">Add Product</a>
    </div>
    <div class="container">
        <h2>Add Child Category</h2>
        <form class="form-container" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Child Category Name" required>
            <select name="parent_id" required>
                <option value="">-- Select Parent Category --</option>
                <?php foreach ($parents as $parent) : ?>
                    <option value="<?= $parent['id'] ?>"><?= $parent['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="file" name="image" required>
            <button type="submit">Add Child Category</button>
        </form>

        <h2>Child Category List</h2>
        <ul class="category-list">
            <?php foreach ($childs as $child) : ?>
                <li>
                    <img src="../Child-item/<?= $child['image'] ?>" alt="Category Image">
                    <?= $child['name'] ?> (Parent: <?= $child['parent_name'] ?>)
                    <div class="actions">
                        <a href="update-child-category.php?id=<?= $child['id'] ?>" class="edit-btn"><img src="./img/update.png" alt=""></a>
                        <a href="delete-child-category.php?id=<?= $child['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure?')"><img src="./img/delete.png" alt=""></a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</body>

</html>