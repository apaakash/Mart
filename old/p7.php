<?php
include 'conn.php';
if(isset($_POST['submit'])){
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $image = $_POST['image'];

    // Fixing the insert query
    $sql = "INSERT INTO insert_profile (firstname, lastname, email, mobile, address, image)
    VALUES ('$first_name', '$last_name', '$email', '$mobile', '$address', '$image')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];

            $uploadFileDir = '../img/';
            $data = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $data)) {
                echo "Profile created successfully!";
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Error in file upload.";
        }
    }
}
?>