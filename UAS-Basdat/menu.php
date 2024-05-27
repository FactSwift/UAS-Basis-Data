<?php
session_start();
include('fungsimenu/functions.php');


if (isset($_GET['logout'])) {

    include('config.php');

    mysqli_close($connection);

    session_destroy();

    header('Location: login.php');
    exit();
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu Utama</title>
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
        <h2>Selamat Datang di Menu Utama</h2>
        <div class="menu-item" onclick="showContent('profil')">Profil</div>
        <div class="menu-item" onclick="showContent('transaksi')">Transaksi</div>
        <div class="menu-item" onclick="showContent('laporan')">Laporan</div>
        <div class="menu-item" onclick="showContent('pengaturan')">Pengaturan</div>
        <div class="menu-item" onclick="logout()">Logout</div> 
    </div>

    <div id="content">
        <?php 
        
        showProfile(); 
        ?>
    </div>

    <script>
        function logout() {
            window.location.href = 'index.php';
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