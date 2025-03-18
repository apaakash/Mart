<?php
include "config.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch user details
$user_sql = "SELECT * FROM users WHERE id = $user_id";
$user_result = $conn->query($user_sql);

if ($user_result->num_rows === 0) {
    echo "<p>User not found. Please go back to the <a href='orders.php'>Orders page</a>.</p>";
    exit();
}

$user = $user_result->fetch_assoc();

// Fetch cart details for the user
$cart_sql = "SELECT * FROM cart WHERE u_id = $user_id";
$cart_result = $conn->query($cart_sql);

if ($cart_result->num_rows === 0) {
    echo "<p>No items in the cart. Please go back to the <a href='orders.php'>Orders page</a>.</p>";
    exit();
}

$cart = $cart_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Bill - Flipkart Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url(b1.png);
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 0;
        }

        .container {
            max-width: 700px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            transform: scale(0.8);
            opacity: 0;
            animation: popupFade 0.4s ease-out forwards;
        }

        @keyframes popupFade {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f0f0f0;
            color: #333;
            font-weight: bold;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .pay-button {
            display: block;
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background-color: #fb641b;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .pay-button:hover {
            background-color: #e05b1b;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body>

    <div class="container">
        <h2>Order Summary</h2>
        <table>
            <tr>
                <th>User ID</th>
                <td><?php echo $user['id']; ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo htmlspecialchars($user['firstname'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <th>Products</th>
                <td><?php echo htmlspecialchars($cart['name'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td><?php echo $cart['quantity'] ?? 0; ?></td>
            </tr>
            <tr>
                <th>Grand Total</th>
                <td id="grand_total">₹<?php echo number_format($cart['total_price'] ?? 0, 2); ?></td>
            </tr>
        </table>

        <button id="pay-now" class="pay-button">Proceed to Pay</button>
    </div>

    <script>
        $('#pay-now').click(function(e) {
            var amount = <?php echo ($cart['total_price'] ?? 0) * 100; ?>;
            var name = '<?php echo htmlspecialchars($user['firstname'] ?? ''); ?>';
            var address = '<?php echo htmlspecialchars($user['address'] ?? ''); ?>';
            var email = '<?php echo htmlspecialchars($user['email'] ?? ''); ?>';
            var productName = '<?php echo htmlspecialchars($cart['name'] ?? ''); ?>';
            var quantity = <?php echo $cart['quantity'] ?? 0; ?>;
            var user_id = <?php echo $user['id']; ?>;

            if (!name || !address || !email) {
                alert('Please fill all the fields.');
                return;
            }

            var options = {
                "key": "rzp_test_oFL88BWKa4IHEK",
                "amount": amount,
                "currency": "INR",
                "name": "Silvassa Mart",
                "description": "Payment for your purchase",
                "image": "https://via.placeholder.com/150",
                "prefill": {
                    "name": name,
                    "email": email
                },
                "theme": {
                    "color": "#2874f0"
                },
                "handler": function(response) {
                    $.ajax({
                        url: 'charge.php',
                        type: 'POST',
                        data: {
                            razorpay_payment_id: response.razorpay_payment_id,
                            amount: amount,
                            name: name,
                            address: address,
                            email: email,
                            productName: productName,
                            quantity: quantity
                        },
                        success: function(data) {
                            if (data.trim() === "success") {
                                alert('Payment successful! Redirecting to your orders.');
                                window.location.href = 'my-orders.php';
                            } else {
                                alert('Payment failed: ' + data);
                            }
                        },
                        error: function(xhr) {
                            alert('Payment failed. Error: ' + xhr.responseText);
                        }
                    });
                }
            };

            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();
        });
    </script>

</body>

</html>