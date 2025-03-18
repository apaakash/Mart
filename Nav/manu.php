<?php
include 'config.php';
$categories = $conn->query("SELECT * FROM parent_categories");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Responsive Navbar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="style2.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-3">
        <a class="navbar-brand fw-bold text-white" href="index.php"><em>Grocery</em></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Search Form -->
            <form action="search.php" method="GET" class="search-box d-flex mx-auto position-relative">
    <input type="text" class="form-control search-input" name="query" id="searchBox"
        placeholder="Search grocery products" autocomplete="off" onkeyup="showSuggestions(this.value)" required />
    &nbsp;&nbsp;
    <button type="submit" class="search-btn px-2">
        <i class="bi bi-search"></i>
    </button>
    <!-- Suggestions List -->
    <ul id="suggestionsList" class="list-group position-absolute w-100" style="z-index: 1000;"></ul>
</form>


            <div class="ms-auto d-flex align-items-center flex-column flex-lg-row mt-3 mt-lg-0">
                <!-- My Account Dropdown -->
                <div class="dropdown mx-3">
                    <?php if (empty($_SESSION["user_id"])) { ?>
                        <a href="register.php" class="nav-item">Login/Register</a>
                    <?php } else { ?>
                        <a href="#" class="nav-item dropdown-toggle" id="accountDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">My Account</a>
                        <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                            <li><a class="dropdown-item" href="my-orders.php">My Orders</a></li>
                            <li><a class="dropdown-item" href="#">Wishlist</a></li>
                            <li><a class="dropdown-item" href="#">Notifications</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    <?php } ?>
                </div>

                <!-- More Dropdown -->
                <div class="dropdown mx-3">
                    <a href="#" class="nav-item dropdown-toggle" id="moreDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">More</a>
                    <ul class="dropdown-menu" aria-labelledby="moreDropdown">
                        <li><a class="dropdown-item" href="supplier/index.php">Become a Seller</a></li>
                        <li><a class="dropdown-item" href="#">24*7 Customer Care</a></li>
                    </ul>
                </div>

                <!-- Cart & Logo -->
                <a href="cart.php" class="nav-item mx-3"><i class="bi bi-bag-check"></i></a>
                <a href="#" class="nav-item mx-3"><img id="logo" src="Nav/logo1.png" alt="Logo" /></a>
            </div>
        </div>
    </nav>

    <!-- Line after navbar -->
    <hr style="border: 1px solid #ccc; margin: 0;">

    <!-- JavaScript for Search Suggestions -->

</body>
</html>
