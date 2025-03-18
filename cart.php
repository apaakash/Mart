<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

include 'config.php';
include './Nav/Manu.php';

if (isset($_POST['update_quantity']) && isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];
    $action = $_POST['update_quantity'];

    // Fetch the current quantity and price details
    $query = mysqli_query($conn, "SELECT quantity, price, old_price FROM cart WHERE id = $cart_id");
    $row = mysqli_fetch_assoc($query);
    $current_quantity = (int) $row['quantity'];
    $price_per_unit = (float) $row['price'];
    $old_price_per_unit = (float) $row['old_price'];

    // Update quantity based on action
    if ($action === 'increase') {
        $new_quantity = $current_quantity + 1;
    } elseif ($action === 'decrease') {
        $new_quantity = max(0, $current_quantity - 1);
    }

    // Calculate new total values
    $new_total_price = $price_per_unit * $new_quantity;
    $new_total_old_price = $old_price_per_unit * $new_quantity;

    // If quantity becomes zero, remove item from cart
    if ($new_quantity == 0) {
        mysqli_query($conn, "DELETE FROM cart WHERE id = $cart_id");
    } else {
        // Update quantity, price, old_price, and total_price
        mysqli_query($conn, "UPDATE cart SET 
            quantity = $new_quantity, 
            price = $price_per_unit, 
            old_price = $old_price_per_unit, 
            total_price = $new_total_price 
            WHERE id = $cart_id");
    }

    header("Location: cart.php");
    exit();
}


$select_cart = mysqli_query($conn, "SELECT * FROM cart");
$grand_total = 0;
$total_discount = 0;
$total_items = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./old/style.css">
    <title>Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .content {
            display: flex;
            padding: 20px;
        }

        .left-section {
            flex: 2;
            padding-right: 20px;
        }

        .right-section {
            flex: 1;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .delivery-address {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .price-header {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .price-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .product {
            display: flex;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .product-image img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-right: 20px;
        }

        .product-details {
            flex: 1;
        }

        .product-price {
            margin-bottom: 10px;
        }

        .current-price {
            font-weight: bold;
            margin-right: 10px;
        }

        .original-price {
            text-decoration: line-through;
            color: #888;
            margin-right: 10px;
        }

        .discount {
            color: green;
            margin-right: 10px;
        }

        .offer {
            background-color: #e0f2fe;
            color: #1e88e5;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.8em;
        }

        .continue-btn {
            background-color: blue;
            color: #fff;
            padding: 15px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1em;
            text-decoration: none;
        }

        .continue-btn:hover {
            background-color: darkblue;
        }

        .price-details {
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .total-amount {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-top: 15px;
        }

        .savings {
            color: green;
            text-align: center;
            margin-top: 20px;
        }

        .footer {
            display: flex;
            align-items: center;
            font-size: 0.9em;
            color: #555;
        }

        .footer img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            background-color: #f0f0f0;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 1.2em;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .quantity-btn:hover {
            background-color: #ddd;
        }

        .quantity {
            margin: 0 10px;
            font-size: 1.2em;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="content">
            <div class="left-section">

                <?php
                if (mysqli_num_rows($select_cart) > 0) {
                    while ($cart_item = mysqli_fetch_assoc($select_cart)) {
                        $quantity = (int) $cart_item['quantity'];
                        $price_per_unit = (float) $cart_item['price'];
                        $old_price_per_unit = (float) $cart_item['old_price'];

                        // Calculate total price based on quantity
                        $total_price = $price_per_unit * $quantity;
                        $total_old_price = $old_price_per_unit * $quantity;
                        $total_discount_price = $total_old_price - $total_price;

                        $total_discount += $total_discount_price;
                        $grand_total += $total_price;
                        $total_items += $quantity;
                ?>
                        <!-- <div class="delivery-address">
                            Deliver to:
                            <p><?php //echo $cart_item['address']; ?></p>
                        </div> -->
                        <div class="product">
                            <div class="product-image">
                                <img src="./p-item/<?php echo $cart_item['image']; ?>" alt="<?php echo $cart_item['name']; ?>">
                            </div>
                            <div class="product-details">
                                <div class="product-name"><?php echo $cart_item['name']; ?></div>
                                <div class="product-quantity"><?php echo $cart_item['weight']; ?></div>
                                <div class="product-price">
                                    <span class="current-price">₹<?php echo number_format($total_price, 2); ?>/-</span>
                                    <span class="original-price">₹<?php echo number_format($total_old_price, 2); ?>/-</span>
                                    <span class="discount">₹<?php echo number_format($total_discount_price, 2); ?> Off</span>
                                    <span class="offer"><?php echo $cart_item['offer']; ?></span>
                                </div>
                                <form method="POST">
                                    <div class="quantity-control">
                                        <input type="hidden" name="cart_id" value="<?php echo $cart_item['id']; ?>">

                                        <!-- Decrease Button -->
                                        <button type="submit" name="update_quantity" class="quantity-btn" value="decrease">-</button>
                                        <input type="hidden" name="new_quantity" value="<?php echo max(1, $quantity - 1); ?>">

                                        <!-- Display Current Quantity -->
                                        <span class="quantity"><?php echo $quantity; ?></span>

                                        <!-- Increase Button -->
                                        <button type="submit" name="update_quantity" class="quantity-btn" value="increase">+</button>
                                        <input type="hidden" name="new_quantity" value="<?php echo $quantity + 1; ?>">
                                    </div>
                                </form>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>Your cart is empty.</p>";
                }
                ?>
                <a href="index.php" class="continue-btn">CONTINUE SHOP</a>
                <?php if ($total_items > 0) { ?>
                    <a href="map.php" class="continue-btn">CONTINUE TO PAY</a>
                <?php } ?>
            </div>

            <div class="right-section">
                <div class="price-details">
                    <div class="price-header">PRICE DETAILS</div>
                    <div class="price-item"><span>MRP (<?php echo $total_items; ?> items)</span> <span>₹<?php echo number_format($grand_total + $total_discount, 2); ?></span></div>
                    <div class="price-item"><span>Product Discount</span> <span class="discount-value">- ₹<?php echo number_format($total_discount, 2); ?></span></div>
                    <div class="price-item"><span>Delivery Fee</span> <span class="free">₹50 Free</span></div>
                    <div class="total-amount"><span>Total Amount</span> <span>₹<?php echo number_format($grand_total, 2); ?></span></div>
                    <div class="savings">You will save ₹<?php echo number_format($total_discount, 2); ?> on this order</div>
                </div>

                <div class="footer">
                    <img src="./Nav/logo2.png" alt="Tick Mark">
                    Safe and Secure Payments. Easy returns. 100% Authentic products.
                </div>
            </div>
        </div>
    </div>
    <?php include "./Nav/footer.php"; ?>
</body>

</html>