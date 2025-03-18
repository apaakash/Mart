<?php
session_start(); // Start the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login or index page
    header('Location: index.php');
    exit(); // Stop further execution of the script
}
include 'config.php';
include './Nav/Manu.php';

if (!isset($_GET['parent_id']) || !isset($_GET['child_id'])) {
    die("Invalid category selection.");
}

$parent_id = $_GET['parent_id'];
$child_id = $_GET['child_id'];

$parent = $conn->query("SELECT * FROM parent_categories WHERE id = $parent_id")->fetch_assoc();
$childs = $conn->query("SELECT * FROM child_categories WHERE parent_id = $parent_id");
$items = $conn->query("SELECT * FROM items WHERE category_id = $child_id");

$user_id = $_SESSION['user_id']; // Get the user ID from the session

// Fetch user's address from the users table
$user_query = mysqli_query($conn, "SELECT address FROM users WHERE id = '$user_id'");
$user_data = mysqli_fetch_assoc($user_query);
$user_address = $user_data['address'];

if (isset($_POST['submit'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_weight = $_POST['product_weight']; // Assuming you have weight in your products table
    $product_offer = $_POST['product_offer'];  // Assuming you have offer in your products table
    $product_old_price = $_POST['product_old_price']; //Assuming you have old price in your product table
    $product_quantity = 1;
    $total_price = $product_price * $product_quantity;
    $user_address = $_POST['user_address']; // Get the user address from the form

    $check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE u_id = '$user_id' AND name = '$product_name'");

    if (mysqli_num_rows($check_cart) > 0) {
        echo "<script>alert('Product is already in your cart.');</script>";
    } else {
        $insert_cart = mysqli_query($conn, "INSERT INTO cart (u_id, image, offer, name, weight, price, old_price, total_price, quantity, address) 
            VALUES ('$user_id', '$product_image', '$product_offer', '$product_name', '$product_weight', '$product_price', '$product_old_price', '$total_price', '$product_quantity', '$user_address')");

        if ($insert_cart) {
            echo "<script>alert('Product added to cart.');</script>";
        } else {
            echo "<script>alert('Failed to add product to cart!');</script>";
        }
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rolling Paper Shop</title>
    <link rel="stylesheet" href="./old/style.css">
    <style></style>
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
        <main class="content">
            <header>
                <h1>Buy Rolling Paper Online</h1>
                <div class="sort-dropdown">
                    <label for="sort">Sort By:</label>
                    <select id="sort">
                        <option value="relevance">Relevance</option>
                        <option value="price-low">Price (Low to high)</option>
                        <option value="price-high">Price (High to low)</option>
                        <option value="discount">Discount (High to low)</option>
                        <option value="name">Name (A to Z)</option>
                    </select>
                </div>
            </header>
            <div class="product-container" id="productContainer1">
                <!-- Product List -->
                <?php if ($items->num_rows > 0) : ?>
                    <?php while ($item = $items->fetch_assoc()) : ?>
                        <div class="product-card">
                            <?php if (!empty($item['offer'])) : ?>
                                <div class="offer-label"><?= $item['offer'] ?></div>
                            <?php endif; ?>
                            <img src="./p-item/<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                            <h3><?= $item['name'] ?></h3>
                            <p class="weight">
                                <?= !empty($item['weight']) ? str_replace(',', ' | ', $item['weight']) : 'N/A' ?>
                            </p>
                            <div class="price-container">
                                <p class="price">₹<?= $item['price'] ?></p>
                                <?php if (!empty($item['old_price'])) : ?>
                                    <p class="old-price">₹<?= $item['old_price'] ?></p>
                                <?php endif; ?>
                            </div>
                            <form action="" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo $item['name']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $item['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo $item['image']; ?>">
                                <input type="hidden" name="product_weight" value="<?php echo $item['weight']; ?>">
                                <input type="hidden" name="product_offer" value="<?php echo $item['offer']; ?>">
                                <input type="hidden" name="product_old_price" value="<?php echo $item['old_price']; ?>">
                                <input type="hidden" name="user_address" value="<?php echo $user_address; ?>">
                                <input type="submit" name="submit" value="Add to Cart" class="add-btn">
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </div>

        </main>
    </div>
    <script src="./old/script.js"></script>
    <?php include "./Nav/footer.php"; ?>
</body>

</html>