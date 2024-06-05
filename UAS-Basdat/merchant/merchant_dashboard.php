<?php
include '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['merchant_username'])) {
    header('Location: ../login.php');
    exit;
}

$username = $_SESSION['merchant_username'];

function getMerchantDetails($username) {
    global $db;
    $sql = "SELECT * FROM Merchant WHERE username = '$username'";
    $result = mysqli_query($db, $sql);
    return mysqli_fetch_assoc($result);
}

$merchant = getMerchantDetails($username);
$id_merchant = $merchant['id_merchant'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Merchant Dashboard</title>
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
        <h2>Welcome, <?= $merchant['nama_toko']; ?></h2>
        <div class="menu-item" onclick="showContent('profil')">Profil</div>
        <div class="menu-item" onclick="showContent('withdraw')">Tarik Pembayaran</div>
        <div class="menu-item"><a href="statistik_transaksi.php">Statistik Transaksi</a></div>
        <div class="menu-item"><a href="statistik_penarikan.php">Statistik Penarikan</a></div>
        <div class="menu-item" onclick="logout()">Logout</div> 
    </div>

    <div id="content">
    </div>

    <script>
        function logout() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    window.location.href = '../login.php';
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
            xhttp.open("GET", "content.php?type=" + contentType, true);
            xhttp.send();
        }
    </script>
</body>
</html>