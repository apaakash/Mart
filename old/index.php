<?php
include 'config.php';

// Fetch Parent Categories
$parents = $conn->query("SELECT * FROM parent_categories");

// Fetch Items
$items = $conn->query("SELECT items.*, items.image AS item_image, child_categories.name AS category_name, child_categories.image AS category_image, parent_categories.image AS parent_image
    FROM items 
    JOIN child_categories ON items.category_id = child_categories.id
    JOIN parent_categories ON child_categories.parent_id = parent_categories.id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Categories</h1>
    <ul>
        <?php while ($parent = $parents->fetch_assoc()) : ?>
            <li>
                <strong>
                    <img src="C-items/<?= $parent['image'] ?>" alt="<?= $parent['name'] ?>" width="50"> 
                    <?= $parent['name'] ?>
                </strong>
                <ul>
                    <?php
                    $childs = $conn->query("SELECT * FROM child_categories WHERE parent_id = " . $parent['id']);
                    while ($child = $childs->fetch_assoc()) :
                    ?>
                        <li>
                            <img src="Child-item/<?= $child['image'] ?>" alt="<?= $child['name'] ?>" width="40">
                            <?= $child['name'] ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </li>
        <?php endwhile; ?>
    </ul>

    <h1>Products</h1>
    <div class="product-grid">
        <?php while ($item = $items->fetch_assoc()) : ?>
            <div class="product">
                <img src="P-item/<?= $item['item_image'] ?>" alt="<?= $item['name'] ?>" width="100">
                <h3><?= $item['name'] ?></h3>
                <p><?= $item['category_name'] ?></p>
                <p><strong>₹<?= $item['price'] ?></strong></p>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
