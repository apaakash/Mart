<?php
session_start();
include "config.php"; // Database connection

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Set user status to inactive
    $update_query = "UPDATE users SET status='inactive' WHERE id='$user_id'";
    mysqli_query($conn, $update_query);
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login page
echo "<script>window.location.href = 'index.php';</script>";
exit();
?>
