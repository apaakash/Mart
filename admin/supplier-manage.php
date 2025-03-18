<?php
include "../config.php";
include 'sidebar.php';

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='admin-login.php';</script>";
    exit();
}

if (isset($_POST['approve'])) {
    $id = $_POST['id'];
    mysqli_query($conn, "UPDATE suppliers SET status='approved' WHERE id=$id");
} elseif (isset($_POST['deny'])) {
    $id = $_POST['id'];
    mysqli_query($conn, "UPDATE suppliers SET status='denied' WHERE id=$id");
} elseif (isset($_POST['pending'])) {
    $id = $_POST['id'];
    mysqli_query($conn, "UPDATE suppliers SET status='pending' WHERE id=$id");
}

$suppliers = mysqli_query($conn, "SELECT * FROM suppliers");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Panel</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: green;
        }

        .sidebar {
            width: 200px;
            background: #4CAF50;
            color: #fff;
            padding: 20px;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background: #45a049;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            color: white;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: green;
            color: white;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        button {
            padding: 8px 12px;
            margin: 3px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        button[name="approve"] {
            background-color: #28a745;
            color: white;
        }

        button[name="deny"] {
            background-color: #dc3545;
            color: white;
        }

        button[name="pending"] {
            background-color: #ffc107;
            color: black;
        }

        button:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 150px;
            }

            .main-content {
                margin-left: 170px;
            }

            table {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <h2>Supplier Approval</h2>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Shop</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($suppliers)) { ?>
                <tr>
                    <td><?= $row['firstname'] ?></td>
                    <td><?= $row['shop'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" name="approve">Approve</button>
                            <button type="submit" name="deny">Deny</button>
                            <button type="submit" name="pending">Pending</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>

</html>
