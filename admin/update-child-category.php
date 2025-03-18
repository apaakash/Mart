<?php
include '../config.php';
include 'sidebar.php';

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='admin-login.php';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM child_categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    $stmt->close();
}

// Fetch Parent Categories
$parentResult = $conn->query("SELECT * FROM parent_categories");
$parents = [];
while ($row = $parentResult->fetch_assoc()) {
    $parents[] = $row;
}

// Update category
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $parent_id = $_POST['parent_id'];
    $image = $_FILES['image']['name'];

    if (!empty($image)) {
        $target = "../Child-item/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        $image = $category['image']; // Keep old image if no new one is uploaded
    }

    $stmt = $conn->prepare("UPDATE child_categories SET name=?, parent_id=?, image=? WHERE id=?");
    $stmt->bind_param("sisi", $name, $parent_id, $image, $id);
    if ($stmt->execute()) {
        echo "<script>alert('Category updated successfully!'); window.location.href='manage-child-category.php';</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Child Category</title>
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

        .container {
            width: 90%;
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #0056b3;
        }

        .preview-image {
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Update Child Category</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="name" value="<?= $category['name'] ?>" required>

            <select name="parent_id" required>
                <option value="">-- Select Parent Category --</option>
                <?php foreach ($parents as $parent) : ?>
                    <option value="<?= $parent['id'] ?>" <?= ($parent['id'] == $category['parent_id']) ? "selected" : "" ?>>
                        <?= $parent['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="file" name="image">
            <?php if (!empty($category['image'])) : ?>
                <br>
                <img src="../Child-item/<?= $category['image'] ?>" width="100" class="preview-image">
                <br>
            <?php endif; ?>

            <button type="submit">Update Category</button>
        </form>
    </div>
</body>

</html>
