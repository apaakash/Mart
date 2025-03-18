<?php
include "init.php";
include 'config.php';


// Redirect to login page if supplier is not logged in
if (!isset($_SESSION['supplier_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch total categories
$query = "SELECT COUNT(*) AS total_categories FROM parent_categories";
$result = mysqli_query($conn, $query);
$totalCategories = ($row = mysqli_fetch_assoc($result)) ? $row['total_categories'] : 0;

// Fetch total child categories
$query = "SELECT COUNT(*) AS total_child_categories FROM child_categories";
$result = mysqli_query($conn, $query);
$totalChildCategories = ($row = mysqli_fetch_assoc($result)) ? $row['total_child_categories'] : 0;

// Fetch total products
$query = "SELECT COUNT(*) AS total_products FROM items";
$result = mysqli_query($conn, $query);
$totalProducts = ($row = mysqli_fetch_assoc($result)) ? $row['total_products'] : 0;

// Fetch recent products (limit 5)
$query = "SELECT i.id, i.name, c.name AS category, i.price 
        FROM items i
        JOIN child_categories c ON i.category_id = c.id
        ORDER BY i.id DESC 
        LIMIT 5";
$recentProducts = mysqli_query($conn, $query);


$query = "SELECT id, name FROM parent_categories ORDER BY id DESC LIMIT 5";
$recentCategories = mysqli_query($conn, $query);

$query = "SELECT c.id, c.name, p.name AS parent_category 
        FROM child_categories c
        JOIN parent_categories p ON c.parent_id = p.id
        ORDER BY c.id DESC 
        LIMIT 5";
$recentChildCategories = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'>
    <title>Product Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: green;
        }

        .dashboard-container {
            display: flex;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        #pro_hrader {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #pro_hrader h1 {
            font-size: 24px;
            color: #333;
        }

        .statistics {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 30%;
            text-align: center;
        }

        .stat-card h3 {
            font-size: 18px;
            color: #333;
        }

        .stat-card p {
            font-size: 24px;
            font-weight: bold;
            color: #2a9d8f;
        }

        .recent-orders {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .recent-orders h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f1f1f1;
        }

        .action-btn {
            background-color: #2a9d8f;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-btn:hover {
            background-color: #21867a;
        }

        .button {
            align-items: end;
            font-family: inherit;
            font-weight: bold;
            cursor: pointer;
            font-size: 20px;
            padding: 10px;
            color: black;
            background: while;
            border: none;
            letter-spacing: 1px;
            border-radius: 20em;
            text-decoration: none;
            margin-left: 70px;
        }

        .button:hover {
            background: #ad5389;
            color: white;
            transition: 0.6s;
        }

        /* General Button Styles */
        .action-btn,
        .delete-btn {
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        /* Edit (Update) Button */
        .action-btn {
            background-color: #2a9d8f;
            /* Green */
            color: white;
        }

        .action-btn:hover {
            background-color: #21867a;
            transform: scale(1.05);
        }

        /* Delete Button */
        .delete-btn {
            background-color: #e74c3c;
            /* Red */
            color: white;
        }

        .delete-btn:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }

        /* Optional: Spacing between buttons */
        .action-btn+.delete-btn {
            margin-left: 8px;
        }
    </style>
</head>

<body>
    <?php renderSidebar(); ?>
    <section class="home">
        <div class="dashboard-container">
            <div class="main-content">
                <header id="pro_hrader">
                    <h1>Product Management <a href="manage-parent-category.php" class="button">Add Parent Category</a><a href="manage-child-Category.php" class="button">Add Child Category</a><a href="manage-product.php" class="button">Add product</a></h1>
                </header>
                <div class="statistics">
                    <div class="stat-card">
                        <h3>Categories</h3>
                        <p><?php echo $totalCategories; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Child Categories</h3>
                        <p><?php echo $totalChildCategories; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Products</h3>
                        <p><?php echo $totalProducts; ?></p>
                    </div>
                </div>
                <div class="recent-orders">
                    <h2>Recent Products</h2>
                    <table>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                        <?php while ($row = mysqli_fetch_assoc($recentProducts)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['category']; ?></td>
                                <td>₹<?php echo number_format($row['price'], 2); ?></td>
                                <td>
                                    <a href="update-products.php?id=<?php echo $row['id']; ?>" class="action-btn">Edit</a>
                                    <a href="delete-products.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <br>
                <div class="recent-orders">
                    <h2>Recent Categories</h2>
                    <table>
                        <tr>
                            <th>Category ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                        <?php while ($row = mysqli_fetch_assoc($recentCategories)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td>
                                    <a href="update-category.php?id=<?php echo $row['id']; ?>" class="action-btn">Edit</a>
                                    <a href="delete-category.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <br>
                <div class="recent-orders">
                    <h2>Recent Child Categories</h2>
                    <table>
                        <tr>
                            <th>Child Category ID</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>Actions</th>
                        </tr>
                        <?php while ($row = mysqli_fetch_assoc($recentChildCategories)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['parent_category']; ?></td>
                                <td>
                                    <a href="update-child-category.php?id=<?php echo $row['id']; ?>" class="action-btn">Edit</a>
                                    <a href="delete-child-category.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this child category?');">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </section>
    <script src="script.js"></script>
</body>

</html>