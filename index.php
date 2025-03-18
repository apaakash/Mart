<?php
session_start();
include "./Nav/Manu.php";
include "config.php"; // Ensure database connection is included

// Check if the user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_address = "";

// Fetch user address if logged in
if ($user_id) {
    $user_query = mysqli_query($conn, "SELECT address FROM users WHERE id = '$user_id'");
    if ($user_data = mysqli_fetch_assoc($user_query)) {
        $user_address = $user_data['address'];
    }
}

// Fetch parent categories
$categories = $conn->query("SELECT * FROM parent_categories");

// Fetch all products
$items = $conn->query("SELECT * FROM items ORDER BY RAND()");

// Handle Add to Cart (only for logged-in users)
if ($user_id && isset($_POST['submit'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_weight = $_POST['product_weight'];
    $product_offer = $_POST['product_offer'];
    $product_old_price = $_POST['product_old_price'];
    $product_quantity = 1;
    $total_price = $product_price * $product_quantity;

    // Check if the product is already in the cart
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

<!-- Category Section -->
<div class="container">
    <div class="category full-width">
        <a href="product.php"><img src="./img/Group-33704.webp" alt="Paan Corner" class="category-image"></a>
    </div>
</div>

<div class="parent-div">
    <div class="child-div">
        <a href="product.php"><img src="./img/Pet-Care_WEB.avif" alt="Pet-Care"></a>
    </div>
    <div class="child-div">
        <a href="product.php"><img src="./img/pharmacy-WEB.avif" alt="pharmacy"></a>
    </div>
    <div class="child-div">
        <a href="product.php"><img src="./img/babycare-WEB.avif" alt="babycare"></a>
    </div>
</div>

<!-- Category Section -->
<div class="category-section">
    <?php while ($parent = $categories->fetch_assoc()) : ?>
        <div class="category-item">
            <a href="Child_products.php?parent_id=<?= $parent['id'] ?>">
                <img src="./C-items/<?= $parent['image'] ?>" alt="<?= $parent['name'] ?>">
            </a>
        </div>
    <?php endwhile; ?>
</div>

<!-- Products Section -->
<div class="product-container">
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
                <!-- Add to Cart Form -->
                <form action="" method="post">
                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                    <input type="hidden" name="product_name" value="<?= $item['name'] ?>">
                    <input type="hidden" name="product_price" value="<?= $item['price'] ?>">
                    <input type="hidden" name="product_image" value="<?= $item['image'] ?>">
                    <input type="hidden" name="product_weight" value="<?= $item['weight'] ?>">
                    <input type="hidden" name="product_offer" value="<?= $item['offer'] ?>">
                    <input type="hidden" name="product_old_price" value="<?= $item['old_price'] ?>">
                    <input type="hidden" name="user_address" value="<?= htmlspecialchars($user_address) ?>">
                    <input type="submit" name="submit" value="Add to Cart" class="add-btn">
                </form>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <p>No products found.</p>
    <?php endif; ?>
</div>

<?php include "./Nav/footer.php"; ?>
