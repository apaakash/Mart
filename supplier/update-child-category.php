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

// Fetch the existing child category details
$query = "SELECT * FROM child_categories WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Child Category not found.'); window.location.href='product_management.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($result);

// Fetch all parent categories for selection
$parentQuery = "SELECT * FROM parent_categories";
$parentResult = mysqli_query($conn, $parentQuery);

// Handle form submission for updating the category
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $parent_id = intval($_POST['parent_id']);

    if (!empty($name) && $parent_id > 0) {
        $updateQuery = "UPDATE child_categories SET name='$name', parent_id='$parent_id' WHERE id=$id";

        if (mysqli_query($conn, $updateQuery)) {
            echo "<script>alert('Child Category updated successfully!'); window.location.href='product_management.php';</script>";
        } else {
            echo "<script>alert('Error updating child category.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'>

    <title>Update Child Category</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .update-btn {
            width: 100%;
            padding: 10px;
            background-color: #2a9d8f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
        }

        .update-btn:hover {
            background-color: #21867a;
        }
    </style>
</head>

<body>
    <?php renderSidebar(); ?>
    <div class="container">
        <h2>Update Child Category</h2>
        <form method="POST">
            <label for="name">Category Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>

            <label for="parent_id">Parent Category:</label>
            <select name="parent_id" id="parent_id" required>
                <option value="">Select Parent Category</option>
                <?php while ($parentRow = mysqli_fetch_assoc($parentResult)) { ?>
                    <option value="<?php echo $parentRow['id']; ?>" <?php echo ($row['parent_id'] == $parentRow['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($parentRow['name']); ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit" class="update-btn">Update Category</button>
        </form>
    </div>

</body>

</html>