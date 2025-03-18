<?php
include "../config.php";

include "sidebar.php";

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='admin-login.php';</script>";
    exit();
}

if (isset($_GET['updateid'])) {
    $uid = intval($_GET['updateid']); // Ensure ID is an integer

    // Fetch user details
    $sql = "SELECT * FROM users WHERE id = $uid";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $email = $row['email'];
        $mobile = $row['mobile'];
        $status = $row['status'];
    } else {
        echo "<script>alert('User not found!'); window.location.href='manage-user.php';</script>";
        exit;
    }

    if (isset($_POST['update'])) {
        $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        // Update query
        $update_sql = "UPDATE users 
                       SET firstname='$firstname', lastname='$lastname', email='$email', mobile='$mobile', status='$status' 
                       WHERE id=$uid";
        $update_result = mysqli_query($conn, $update_sql);

        if ($update_result) {
            echo "<script>alert('User updated successfully!'); window.location.href='manage-user.php';</script>";
        } else {
            echo "<script>alert('Error updating user: " . mysqli_error($conn) . "');</script>";
        }
    }
} else {
    echo "<script>window.location.href='manage-user.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            background:green;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100%;
        }

        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group label {
            font-weight: bold;
            color: #2c3e50;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 12px;
            transition: all 0.3s ease;
            outline: none;
            background: #f9f9f9;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #56ab2f;
            box-shadow: 0 0 10px rgba(86, 171, 47, 0.5);
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-primary {
            background: #56ab2f;
            color: white;
        }

        .btn-primary:hover {
            background: #3a8e1a;
        }

        .btn-secondary {
            margin-top: 10px;
            background: rgb(244, 56, 19);
            color: white;
        }

        .btn-secondary:hover {
            background: #95a5a6;
        }

        @media (max-width: 576px) {
            .container {
                width: 100%;
                padding: 20px;
            }

            h2 {
                font-size: 1.8rem;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-2">
        <h2 class="text-center">Update User</h2>
        <form method="POST">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="firstname" class="form-control" value="<?php echo $firstname; ?>" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="lastname" class="form-control" value="<?php echo $lastname; ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label>Mobile</label>
                <input type="text" name="mobile" class="form-control" value="<?php echo $mobile; ?>" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="inactive" <?php echo ($status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    <option value="active" <?php echo ($status == 'active') ? 'selected' : ''; ?>>Active</option>
                </select>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="manage-user.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
