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
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .menu-container {
            background: #34495e;
            color: #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            width: 300px;
            margin: auto;
        }
        .menu-item {
            background: #2980b9;
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
    </style>
</head>
<body>
    <div class="menu-container">
        <h2>Welcome, <?= $admin['nama_lengkap']; ?></h2>
        <div class="menu-item" onclick="showContent('block_user')">Block User</div>
        <div class="menu-item" onclick="showContent('update_commission')">Update Gi-Pay Commission</div>
        <div class="menu-item"><a href="view_user_details.php">View User Details</a></div>
        <div class="menu-item"><a href="view_merchant_details.php">View Merchant Details</a></div>
        <div class="menu-item"><a href="signup_statistics.php">View Signup Statistics</a></div>
        <div class="menu-item" onclick="logout()">Logout</div> 
    </div>

    <div id="content">
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
