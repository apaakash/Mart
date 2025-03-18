<?php
session_start(); // Start the session

function renderSidebar()
{
    include 'config.php'; // Include database connection

    $isLoggedIn = isset($_SESSION['supplier_id']); // Check if user is logged in
    $supplierImage = "default.jpg"; // Default image
    $supplierName = "Guest"; // Default name

    if ($isLoggedIn) {
        $supplier_id = $_SESSION['supplier_id'];

        // Fetch supplier details
        $query = $conn->prepare("SELECT firstname, image FROM suppliers WHERE id = ?");
        $query->bind_param("i", $supplier_id);
        $query->execute();
        $result = $query->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $supplierName = $user['firstname'];
            $supplierImage = !empty($user['image']) ? $user['image'] : "default.jpg";
        }
    }

    echo '
    <nav class="sidebar">
        <header>
            <div class="image-text">
                <span class="image">
                    <a href="profile.php">
                        <img src="' . $supplierImage . '" alt="Profile Image">
                    </a>
                </span>
                <div class="text logo-text">
                    <span class="name">' . $supplierName . '</span>
                    <span class="profession">Supplier Admin</span>
                </div>
            </div>
        </header>
        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">';

    // Show only Login/Register if not logged in
    if (!$isLoggedIn) {
        echo '<li class="nav-link"><a href="login.php"><i class="bi bi-box-arrow-in-right icon"></i><span class="text nav-text">Login/Register</span></a></li>';
    } else {
        // Show all options if logged in
        echo '
        <li class="nav-link"><a href="index.php"><i class="bi bi-house icon"></i><span class="text nav-text">Dashboard</span></a></li>
        <li class="nav-link"><a href="performance_analytics.php"><i class="bi bi-graph-up-arrow icon"></i><span class="text nav-text">Analytics</span></a></li>
        <li class="nav-link"><a href="product_management.php"><i class="bi bi-box-fill icon"></i><span class="text nav-text">Products</span></a></li>
        <li class="nav-link"><a href="order_management.php"><i class="bi bi-kanban icon"></i><span class="text nav-text">Orders</span></a></li>
        <li class="nav-link"><a href="notifications.php"><i class="bi bi-bell icon"></i><span class="text nav-text">Notifications</span></a></li>
        <li><a href="logout.php"><i class="bi bi-box-arrow-in-left icon"></i><span class="text nav-text">Logout</span></a></li>';
    }

    echo '
                </ul>
            </div>
        </div>
    </nav>';
}
?>
