<?php
include '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit;
}

$username = $_SESSION['admin_username'];

function getAdminDetails($username) {
    global $db;
    $sql = "SELECT * FROM Admin WHERE username = '$username'";
    $result = mysqli_query($db, $sql);
    return mysqli_fetch_assoc($result);
}

$admin = getAdminDetails($username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('uang2.jpg') no-repeat fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
        }
        .menu-container {
            background: rgba(0, 0, 0, 0.8); 
            color: #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            width: 300px;
            margin: auto;
        }
        .menu-item {
            background: #ab1549;
            padding: 10px;
            margin: 10px 0;
            text-align: center;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .menu-item:hover {
            background-color: #3498db;
            cursor: pointer;
        }
        .menu-item a {
            color: #ecf0f1;
            text-decoration: none;
        }
        .menu-item a:hover {
            text-decoration: none;
        }
        .content-container {
            background-color: rgba(236, 240, 241, 0.8); 
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
        }
        .table-wrapper {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto; 
        }
        .table-title {
            padding-bottom: 15px;
            background: #6c7ae0;
            color: #fff;
            padding: 16px 30px;
            margin: -20px -20px 10px;
            border-radius: 5px 5px 0 0;
        }
        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
        .table th, .table td {
            border-color: #e9e9e9;
            padding: 12px 15px;
            vertical-align: middle;
        }
        .table th {
            background: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <h2>Welcome, <?= $admin['nama_lengkap']; ?></h2>
        <div class="menu-item" onclick="showContent('block_user')">Block User</div>
        <div class="menu-item" onclick="showContent('update_commission')">Update Gi-Pay Commission</div>
        <div class="menu-item" onclick="showContent('view_user_details')">View User Details</div>
        <div class="menu-item" onclick="showContent('view_merchant_details')">View Merchant Details</div>
        <div class="menu-item" onclick="showContent('signup_statistics')">View Signup Statistics</div>
        <div class="menu-item" onclick="logout()">Logout</div> 
    </div>

    <div id="content" class="content-container">
    </div>

    <script>
        function logout() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    window.location.href = 'admin_login.php';
                }
            };
            xhttp.open("GET", "../logout.php", true);
            xhttp.send();
        }

        function showContent(contentType) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("content").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "admin_content.php?type=" + contentType, true);
            xhttp.send();
        }
    </script>
</body>
</html>

