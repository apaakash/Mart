<?php
include 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first!'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];

    // Image Upload Handling
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $upload_dir = "uploads/";

    if (!empty($image)) {
        $image_path = $upload_dir . basename($image);
        move_uploaded_file($image_tmp, $image_path);
    } else {
        $image_path = $_POST['existing_image']; // Keep old image if no new one uploaded
    }

    // Update query
    $updateQuery = $conn->prepare("UPDATE users SET firstname=?, lastname=?, email=?, mobile=?, address=?, image=? WHERE id=?");
    $updateQuery->bind_param("ssssssi", $first_name, $last_name, $email, $mobile, $address, $image_path, $user_id);

    if ($updateQuery->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Failed to update profile. Please try again.');</script>";
    }
}

// Fetch user details
$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>alert('User not found! Please register.'); window.location.href='register.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" profile_content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            min-height: 100vh;
        }

        .profile_container {
            display: flex;
            width: 80%;
            height: 90%;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 0 4%;
            padding: 20px;
        }

        .sidebar {
            width: 280px;
            background: #4CAF50;
            padding: 25px;
            border-right: 1px solid #ddd;
            border-radius: 15px;
            transition: background-color 0.3s ease;
        }

        .profile-header {
            text-align: center;
            padding-bottom: 20px;
        }

        .profile-header img {
            width: 150px;
            /* Increase width */
            height: 150px;
            /* Increase height */
            border-radius: 50%;
            border: 3px solid #2e7d32;
            transition: transform 0.3s ease-in-out;
        }


        .profile-header img:hover {
            transform: scale(1.1);
        }

        .profile-header h2 {
            font-size: 1.3em;
            margin: 10px 0;
            color: #ffffff;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .sidebar li {
            padding: 12px 0;
            border-bottom: 1px solid #ddd;
        }

        .sidebar a {
            text-decoration: none;
            color: #ffffff;
            display: block;
            font-size: 1.1em;
            transition: all 0.3s ease;
        }

        .sidebar a:hover {
            font-weight: bold;
            transform: translateX(5px);
        }

        .profile_content {
            flex: 1;
            padding: 40px;
        }

        .profile_content h1 {
            font-size: 1.8em;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            color: #4CAF50;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .profile-info label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #2e7d32;
        }

        .profile-info input,
        .profile-info select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .profile-info button {
            background-color: #4CAF50;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
        }

        .profile-info button:hover {
            background-color: #45a049;
        }

        .edit-link,
        .cancel-link {
            text-decoration: none;
            color: #1976d2;
            font-size: 14px;
            cursor: pointer;
            margin-left: 200px;

        }
    </style>
</head>

<body>
    <div class="profile_container">
        <div class="sidebar">
            <div class="profile-header">
                <img src="<?php echo !empty($user['image']) ? $user['image'] : 'default.jpg'; ?>" alt="Profile Picture">
                <h2><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></h2>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
                <br>
                <br>
                <br>
                <br>
                <a href="index" style="text-decoration: none; color:blue;">Back</a>
            </div>
        </div>

        <div class="profile_content">
            <form action="" method="post" enctype="multipart/form-data">
                <h1>Personal Information
                    <a class="edit-link" onclick="toggleEdit(true)">Edit</a>
                    <a class="cancel-link" onclick="toggleEdit(false)" style="display: none;">Cancel</a>
                </h1>
                <div class="profile-info">
                    <input type="hidden" name="existing_image" value="<?php echo $user['image']; ?>">

                    <div>
                        <label>First Name</label>
                        <input type="text" name="first_name" value="<?php echo $user['firstname']; ?>" required readonly>
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input type="text" name="last_name" value="<?php echo $user['lastname']; ?>" required readonly>
                    </div>
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $user['email']; ?>" required readonly>
                    </div>
                    <div>
                        <label>Address</label>
                        <input type="text" name="address" value="<?php echo $user['address']; ?>" required readonly>
                    </div>
                    <div>
                        <label>Profile Image</label>
                        <input type="file" name="image" disabled>
                    </div>

                    <button type="submit" id="saveButton" style="display: none;">Save Changes</button>
                </div>

            </form>
        </div>
    </div>
    <script>
        function toggleEdit(enable) {
            document.querySelectorAll('.profile-info input').forEach(input => {
                if (input.type !== 'hidden') {
                    input.readOnly = !enable;
                    input.disabled = !enable;
                }
            });

            document.querySelector('.edit-link').style.display = enable ? 'none' : 'inline';
            document.querySelector('.cancel-link').style.display = enable ? 'inline' : 'none';
            document.getElementById('saveButton').style.display = enable ? 'inline' : 'none';

            if (!enable) {
                document.querySelector("form").reset(); // Reset form on cancel
            }
        }
    </script>
</body>

</html>