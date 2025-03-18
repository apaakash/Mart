<?php
session_start(); // Start session at the very beginning

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

include 'config.php';
include './Nav/Manu.php';

// Validate parent_id parameter
if (!isset($_GET['parent_id']) || !is_numeric($_GET['parent_id'])) {
    die("Invalid parent category.");
}

$parent_id = (int)$_GET['parent_id']; // Ensure parent_id is an integer
$parent = $conn->query("SELECT * FROM parent_categories WHERE id = $parent_id")->fetch_assoc();
$childs = $conn->query("SELECT * FROM child_categories WHERE parent_id = $parent_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rolling Paper Shop</title>
    <link rel="stylesheet" href="./old/style.css">
</head>
<body>

    <div class="container_aside">
        <aside class="sidebar">
            <?php while ($child = $childs->fetch_assoc()) : ?>
                <div class="sidebar-item">
                    <a id="child_c" href="products.php?parent_id=<?= $parent_id ?>&child_id=<?= $child['id'] ?>">
                        <img src="Child-item/<?= $child['image'] ?>" alt="<?= $child['name'] ?>">
                        <span><?= $child['name'] ?></span>
                    </a>
                </div>
            <?php endwhile; ?>
        </aside>
    </div>

    <script src="./old/script.js"></script>
    <?php include "./Nav/footer.php"; ?>

</body>
</html>
