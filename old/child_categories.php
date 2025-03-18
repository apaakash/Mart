<?php
include 'config.php';

if (!isset($_GET['parent_id'])) {
    die("Invalid parent category.");
}

$parent_id = $_GET['parent_id'];
$parent = $conn->query("SELECT * FROM parent_categories WHERE id = $parent_id")->fetch_assoc();
$childs = $conn->query("SELECT * FROM child_categories WHERE parent_id = $parent_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Child Categories</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Child Categories of <?= $parent['name'] ?></h1>
    <div class="category-container">
        <?php while ($child = $childs->fetch_assoc()) : ?>
            <div class="category-box">
                <a href="products.php?child_id=<?= $child['id'] ?>">
                    <img src="Child-item/<?= $child['image'] ?>" alt="<?= $child['name'] ?>">
                    <p><?= $child['name'] ?></p>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
    <a href="p5.php">Back to Parent Categories</a>
</body>
</html>
