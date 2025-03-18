<?php
include "sidebar.php";
include "../config.php";

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='admin-login.php';</script>";
    exit();
}
// Fetch Items
$itemResult = $conn->query("SELECT items.*, child_categories.name AS category_name FROM items JOIN child_categories ON items.category_id = child_categories.id");
$items = [];
while ($row = $itemResult->fetch_assoc()) {
    $items[] = $row;
}
?>
<style>
    .main-content {
        flex: 1;
        width: 100%;
        background: green;
        min-height: 100vh;
    }

    .container {
        width: 90%;
        max-width: 1200px;
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
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background: #4caf50;
        color: white;
    }

    @media (max-width: 768px) {
        form {
            flex-direction: column;
        }
    }
</style>
<div class="main-content">
    <div class="container">
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