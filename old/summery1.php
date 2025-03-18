<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? 'Not Provided';
    $address = $_POST['address'] ?? 'Not Provided';
    $phone = $_POST['phone'] ?? 'Not Provided';
    $email = $_POST['email'] ?? 'Not Provided';
    $city = $_POST['city'] ?? 'Not Selected';
    $street = $_POST['street'] ?? 'Not Selected';

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('popup-content').innerHTML = `
                <h2>Order Summary</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Address:</strong> $address</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>City:</strong> $city</p>
                <p><strong>Street:</strong> $street</p>
                <button onclick='closePopup()'>Close</button>
            `;
            document.getElementById('order-popup').style.display = 'block';
        });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Address</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: greenyellow;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }

        .popup-container {
            display: flex;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.4);
            width: 70%;
            max-width: 900px;
            height: 75%;
        }

        .map-section {
            flex: 1;
            height: 100%;
        }

        #map {
            width: 100%;
            height: 100%;
            min-height: 400px;
            border-radius: 10px;
        }

        .form-section {
            width: 45%;
            padding: 20px;
            overflow-y: auto;
        }

        .form-section h2 {
            margin-bottom: 15px;
            font-size: 20px;
        }

        .input-group {
            margin-bottom: 10px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .input-group select,
        .input-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-container {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }

        .btn {
            width: 48%;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            color: #fff;
            border-radius: 5px;
            background: #008000;
        }

        .btn:hover {
            background: #006400;
        }

        .order-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.4);
            width: 80%;
            max-width: 400px;
            text-align: center;
            z-index: 100;
        }

        .order-popup h2 {
            margin-bottom: 10px;
        }

        .order-popup button {
            margin-top: 10px;
            padding: 8px 15px;
            background: #008000;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .order-popup button:hover {
            background: #006400;
        }
    </style>
</head>

<body>

    <div class="popup-container">
        <div class="map-section">
            <div id="map"></div>
        </div>
        <div class="form-section">
            <h2>Enter Complete Address</h2>
            <form method="POST" action="">
                <div class="input-group">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Enter your name">
                </div>
                <div class="input-group">
                    <label>Address</label>
                    <input type="text" name="address" placeholder="Enter your address">
                </div>
                <div class="input-group">
                    <label>Mobile</label>
                    <input type="text" name="phone" placeholder="Enter your phone number">
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter your email">
                </div>
                <div class="input-group">
                    <label>City</label>
                    <select name="city" id="city" onchange="updateStreets()">
                        <option value="">Select City</option>
                        <option value="Silvassa">Silvassa</option>
                        <option value="Vapi">Vapi</option>
                        <option value="Daman">Daman</option>
                        <option value="Valsad">Valsad</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Street</label>
                    <select name="street" id="street">
                        <option value="">Select Street</option>
                    </select>
                </div>
                <div class="btn-container">
                    <button type="submit" class="btn">Place Order</button>
                </div>
            </form>
        </div>
    </div>

    <div class="order-popup" id="order-popup">
        <div id="popup-content"></div>
    </div>

    <script>
        function closePopup() {
            document.getElementById('order-popup').style.display = 'none';
        }

        function updateStreets() {
            let city = document.getElementById("city").value;
            let streetDropdown = document.getElementById("street");
            streetDropdown.innerHTML = '<option value="">Select Street</option>';
            if (city) {
                let streets = {
                    "Silvassa": ["Kilvani Naka", "Dadra Road"],
                    "Vapi": ["Gunjan", "Chala"],
                    "Daman": ["Devka Beach", "Moti Daman"],
                    "Valsad": ["Tithal Road", "Dharampur"]
                };
                if (streets[city]) {
                    streets[city].forEach(street => {
                        let option = document.createElement("option");
                        option.value = street;
                        option.textContent = street;
                        streetDropdown.appendChild(option);
                    });
                }
            }
        }
    </script>

</body>

</html>