<?php
include "sidebar.php";
include "../config.php";

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='admin-login.php';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch item details
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();
}

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $old_price = $_POST['old_price'];
    $offer = $_POST['offer'];
    $category_id = $_POST['category_id'];
    $image = $_FILES['image']['name'];

    // Convert offer percentage to numeric value (e.g., "10% OFF" → 10)
    $discountValue = (int) filter_var($offer, FILTER_SANITIZE_NUMBER_INT);

    // Calculate new price
    $price = $old_price - ($old_price * ($discountValue / 100));

    // Handle weight input
    $weightsString = isset($_POST['weight']) ? $_POST['weight'] : "";
    if (!empty($_POST['custom_weight'])) {
        $weightsString = $_POST['custom_weight']; // Override if custom weight is provided
    }

    if (!empty($image)) {
        $target = "../P-item/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        $image = $item['image']; // Keep old image if no new image is uploaded
    }

    // Update query
    $stmt = $conn->prepare("UPDATE items SET name=?, description=?, price=?, old_price=?, offer=?, weight=?, category_id=?, image=? WHERE id=?");
    $stmt->bind_param("ssddsssii", $name, $description, $price, $old_price, $offer, $weightsString, $category_id, $image, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Item updated successfully!'); window.location.href='manage-product.php';</script>";
    }
    $stmt->close();
}

// Fetch Child Categories
$categoryResult = $conn->query("SELECT * FROM child_categories");
$categories = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: green;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 22px;
        }

        .form-group {
            display: grid;
            grid-template-columns: 1fr 2fr;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #218838;
        }

        .image-preview {
            text-align: center;
            margin: 15px 0;
        }

        .image-preview img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            .form-group {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Update Item</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?= $item['name'] ?>" required>
            </div>

            <div class="form-group">
                <label>Description:</label>
                <textarea name="description"><?= $item['description'] ?></textarea>
            </div>

            <div class="form-group">
                <label>Old Price:</label>
                <input type="number" name="old_price" value="<?= $item['old_price'] ?>" step="0.01" required>
            </div>

            <div class="form-group">
                <label>Offer:</label>
                <input type="text" name="offer" value="<?= $item['offer'] ?>" required>
            </div>

            <div class="form-group">
                <label>Final Price:</label>
                <input type="number" name="price" value="<?= $item['price'] ?>" step="0.01" readonly>
            </div>

            <div class="form-group">
                <label>Weight:</label>
                <input type="text" name="weight" value="<?= $item['weight'] ?>" required>
            </div>

            <div class="form-group">
                <label>Category:</label>
                <select name="category_id" required>
                    <option value="">-- Select Child Category --</option>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $item['category_id']) ? "selected" : "" ?>>
                            <?= $cat['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Image:</label>
                <input type="file" name="image">
            </div>

            <div class="image-preview">
                <img src="../P-item/<?= $item['image'] ?>" width="50">
            </div>

            <button type="submit">Update Item</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let oldPriceInput = document.querySelector("input[name='old_price']");
            let offerInput = document.querySelector("input[name='offer']");
            let finalPriceInput = document.querySelector("input[name='price']");

            function calculateFinalPrice() {
                let oldPrice = parseFloat(oldPriceInput.value) || 0;
                let offerText = offerInput.value;

                // Extract numeric value from offer (e.g., "10% OFF" → 10)
                let discount = parseFloat(offerText.replace(/\D/g, "")) || 0;

                // Calculate new price
                let newPrice = oldPrice - (oldPrice * (discount / 100));

                // Update Final Price field
                finalPriceInput.value = newPrice.toFixed(2);
            }

            // Trigger price calculation on input changes
            oldPriceInput.addEventListener("input", calculateFinalPrice);
            offerInput.addEventListener("input", calculateFinalPrice);
        });
    </script>

</body>

</html>