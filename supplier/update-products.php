<?php
include "init.php";

include "config.php";

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product ID is missing.");
}

$product_id = intval($_GET['id']); // Convert to integer for security

// Fetch product details
$query = "SELECT name, category_id, price FROM items WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

// If product not found, exit
if (!$product) {
    die("Product not found.");
}

// Fetch categories
$categoryQuery = "SELECT id, name FROM child_categories";
$categories = mysqli_query($conn, $categoryQuery);

// Update product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);

    $updateQuery = "UPDATE items SET name = ?, category_id = ?, price = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "sidi", $name, $category_id, $price, $product_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Product updated successfully!'); window.location='product_management.php';</script>";
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Product</title>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'>

    <link rel="stylesheet" href="style.css">

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: white;
            font-family: "Poppins", sans-serif;
        }

        .form-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #2c3e50;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        button {
            width: 48%;
            padding: 10px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            border: none;
        }

        .update-btn {
            background: #2a9d8f;
            color: white;
        }

        .update-btn:hover {
            background: #21867a;
        }

        .cancel-btn {
            background: #888;
            color: white;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            width: 48%;
        }

        .cancel-btn:hover {
            background: #666;
        }
    </style>
</head>

<body>
    <?php renderSidebar(); ?>
    <div class="form-container">
        <h2>Update Product</h2>
        <form method="post">
            <label for="name">Product Name</label>
            <?php
            include 'config.php';

            // Check if ID is provided
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                echo "<script>alert('Invalid request!'); window.history.back();</script>";
                exit();
            }

            $product_id = intval($_GET['id']);

            // Fetch existing product details
            $query = "SELECT * FROM items WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $product_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $product = mysqli_fetch_assoc($result);

            if (!$product) {
                echo "<script>alert('Product not found!'); window.history.back();</script>";
                exit();
            }

            // Update product in database
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST['name'];
                $price = $_POST['price'];

                $updateQuery = "UPDATE items SET name = ?, price = ? WHERE id = ?";
                $updateStmt = mysqli_prepare($conn, $updateQuery);
                mysqli_stmt_bind_param($updateStmt, "sdi", $name, $price, $product_id);

                if (mysqli_stmt_execute($updateStmt)) {
                    echo "<script>
                alert('Product updated successfully!');
                window.location.href = 'product_management.php';
            </script>";
                } else {
                    echo "<script>alert('Error updating product.'); window.history.back();</script>";
                }
            }
            ?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Update Product</title>
            </head>

            <body>
                <h2>Update Product</h2>
                <form method="POST">
                    <label>Product Name:</label>
                    <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>

                    <label>Price:</label>
                    <input type="number" name="price" value="<?php echo $product['price']; ?>" step="0.01" required><br>

                    <button type="submit">Update Product</button>
                </form>


                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <?php while ($row = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $product['category_id']) ? "selected" : ""; ?>>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </option>
                    <?php } ?>
                </select>

                <label for="price">Price ($)</label>
                <input type="text" id="price" name="price" value="<?php echo number_format($product['price'], 2); ?>" required>

                <div class="button-group">
                    <button type="submit" class="update-btn">Update</button>
                    <a href="product_management.php" class="cancel-btn">Cancel</a>
                </div>
        </form>
    </div>
</body>

</html>