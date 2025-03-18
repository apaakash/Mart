<?php
include "init.php";
include "config.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $old_price = $_POST['old_price'];
    $offer = $_POST['offer'];
    $category_id = $_POST['category_id'];
    $image = $_FILES['image']['name'];
    $target = "../P-item/" . basename($image);

    // Convert offer percentage to numeric value (e.g., "10% OFF" → 10)
    $discountValue = (int) filter_var($offer, FILTER_SANITIZE_NUMBER_INT);

    // Calculate new price
    $price = $old_price - ($old_price * ($discountValue / 100));

    // Handle weight input
    $weightsString = isset($_POST['weight']) ? $_POST['weight'] : "";
    if (!empty($_POST['custom_weight'])) {
        $weightsString = $_POST['custom_weight']; // Override if custom weight is provided
    }

    // Upload image and insert data
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO items (name, description, price, old_price, offer, weight, category_id, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddssss", $name, $description, $price, $old_price, $offer, $weightsString, $category_id, $image);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch Child Categories
$categoryResult = $conn->query("SELECT * FROM child_categories");
$categories = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch Items
$itemResult = $conn->query("SELECT items.*, child_categories.name AS category_name FROM items JOIN child_categories ON items.category_id = child_categories.id");
$items = [];
while ($row = $itemResult->fetch_assoc()) {
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Product Management</title>
    <style>
        .main-content {
            margin-left: 220px;
            padding: 20px;
            flex: 1;
            width: calc(100% - 220px);
            background: #f4f4f4;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1200px;
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

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        input,
        select,
        button,
        textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            background: #4caf50;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #4caf50;
            color: white;
        }

        .actions button {
            background: #ff6347;
            margin-left: 5px;
        }

        .actions button:hover {
            background: #e5533d;
        }

        .button-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center;
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

        @media (max-width: 768px) {
            form {
                flex-direction: column;
            }
        }
    </style>
    <script>
        function calculatePrice() {
            let oldPrice = parseFloat(document.getElementById("old_price").value) || 0;
            let offerText = document.getElementById("offer").value;
            let discount = parseFloat(offerText.replace(/[^0-9]/g, '')) || 0;
            let newPrice = oldPrice - (oldPrice * (discount / 100));

            if (!isNaN(newPrice)) {
                document.getElementById("price").value = newPrice.toFixed(2);
            }
        }
    </script>
</head>

<body>
<?php renderSidebar(); ?>
    <div class="main-content">
        <div class="button-container">
            <a href="product_management.php">Manage Category</a>
            <a href="manage-parent-category.php">Add Parent Category</a>
            <a href="manage-child-Category.php">Add Child Category</a>
        </div>
        <div class="container">
            <h2>Product Management</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Item Name" required>
                <textarea name="description" placeholder="Description"></textarea>
                <input type="number" id="old_price" name="old_price" placeholder="Old Price" step="0.01" required oninput="calculatePrice()">
                <input type="text" id="offer" name="offer" placeholder="Offer (e.g., 10% OFF)"  oninput="calculatePrice()">
                <input type="number" id="price" name="price" placeholder="New Price" step="0.01">

                <label>Weight:</label>
                <select name="weight" id="weightDropdown" required>
                    <option value="">-- Select Weight --</option>
                    <option value="100g">100g</option>
                    <option value="1 piece">1 piece</option>
                    <option value="100ml">100ml</option>
                    <option value="100g + 1 piece">100g + 1 piece</option>
                    <option value="custom">Custom</option>
                </select>

                <input type="text" name="custom_weight" id="customWeightInput" placeholder="Enter Custom Weight" style="display: none;">

                <select name="category_id" required>
                    <option value="">-- Select Child Category --</option>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="file" name="image" required>
                <button type="submit">Add Item</button>
            </form>

            <h2>Item List</h2>
            <table>
                <tr>
                    <th>Image</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Old Price</th>
                    <th>New Price</th>
                    <th>Weight</th>
                    <th>Offer</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td><img src="../P-item/<?= $item['image'] ?>" width="50" height="50"></td>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['category_name'] ?></td>
                        <td>₹<?= $item['old_price'] ?></td>
                        <td>₹<?= $item['price'] ?></td>
                        <td><?= $item['weight'] ?></td>
                        <td><?= $item['offer'] ?></td>
                        <td class="actions">
                            <a href="update-item.php?id=<?= $item['id'] ?>"><img src="./img/update.png" alt=""></a>
                            <a href="delete-item.php?id=<?= $item['id'] ?>" onclick="return confirm('Are you sure?');"><img src="./img/delete.png" alt=""></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <script>
        document.getElementById("weightDropdown").addEventListener("change", function() {
            var customInput = document.getElementById("customWeightInput");
            if (this.value === "custom") {
                customInput.style.display = "block";
                customInput.setAttribute("required", "required");
            } else {
                customInput.style.display = "none";
                customInput.removeAttribute("required");
            }
        });
    </script>
</body>

</html>