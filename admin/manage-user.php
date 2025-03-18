<?php
include "../config.php";
include "sidebar.php";

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='admin-login.php';</script>";
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            background:green;
            display: flex;
            align-items: flex-start;
            min-height: 100vh;
            width: 100%;
        }

        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            width: calc(100% - 220px);
            max-width: 900px;
            margin-left: 310px;
            margin-top: 80px;
        }

        .head {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .btn img {
            width: 20px;
            height: 20px;
            margin: 0 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin-left: 170px;
                width: calc(100% - 170px);
            }
        }

        @media (max-width: 576px) {
            .container {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }

            th,
            td {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="head">User List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Signup Time</th>
                    <th>Status</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM users";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $uid = $row['id'];  // Corrected from 'uid' to 'id'
                        $name = $row['firstname'] . " " . $row['lastname']; // Concatenating first and last name
                        $email = $row['email'];
                        $mobile = $row['mobile']; // Corrected from 'mobile_number' to 'mobile'
                        $signup_time = $row['created_at']; // Corrected from 'signup_time' to 'created_at'
                        $status = ucfirst($row['status']);

                        echo '<tr>
                            <td>' . $uid . '</td>
                            <td>' . $name . '</td>
                            <td>' . $email . '</td>
                            <td>' . $mobile . '</td>
                            <td>' . $signup_time . '</td>
                            <td>' . $status . '</td>
                            <td>
                                <a href="update-user.php?updateid=' . $uid . '" class="btn"><img src="./img/update.png" alt="Update"></a>
                                <a href="delete-user.php?deleteid=' . $uid . '" class="btn"><img src="./img/delete.png" alt="Delete"></a>
                            </td>
                        </tr>';
                    }
                }

                ?>
            </tbody>
        </table>
    </div>
</body>

</html>