<?php
include "../config.php";

if (isset($_GET['deleteid'])) {
    $uid = $_GET['deleteid'];

    // Validate and sanitize input
    $uid = intval($uid); // Ensures it's an integer to prevent SQL injection

    // Delete query
    $delete_sql = "DELETE FROM users WHERE id = $uid";
    $delete_result = mysqli_query($conn, $delete_sql);

    if ($delete_result) {
        echo "<script>alert('User deleted successfully!'); window.location.href='manage-user.php';</script>";
    } else {
        echo "<script>alert('Error deleting user: " . mysqli_error($conn) . "'); window.location.href='manage-user.php';</script>";
    }
} else {
    echo "<script>window.location.href='manage-user.php';</script>";
}
?>
