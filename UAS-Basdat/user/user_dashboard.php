<?php
include '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

$username = $_SESSION['username'];

function getUserDetails($username) {
    global $db;
    $sql = "SELECT * FROM Pengguna WHERE username = '$username'";
    $result = mysqli_query($db, $sql);
    return mysqli_fetch_assoc($result);
}

$user = getUserDetails($username);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
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
            background: rgba(52, 73, 94, 0.8);
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
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <h2>Welcome, <?= $user['nama_lengkap']; ?></h2>
        <div class="menu-item" onclick="showContent('profil')">Profil</div>
        <div class="menu-item"><a href="user_transaction_history.php">Riwayat Transaksi</a></div>
        <div class="menu-item" onclick="showContent('top_up')">Top Up Saldo</div>
        <div class="menu-item" onclick="showContent('pay')">Bayar dengan Gi-Pay</div>
        <div class="menu-item" onclick="logout()">Logout</div> 
    </div>

    <div id="content" class="content-container">
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
            if (contentType === 'pay') {
                document.getElementById("content").innerHTML = `
                    <div class="container mt-5">
                        <h1 class="mb-4">Welcome to Gi-Pay!</h1>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Bayar dengan Gi-Pay</h5>
                                <form action="gipay_confirm.php" method="POST">
                                    <div class="mb-3">
                                        <label for="id_toko_gipay" class="form-label">ID Toko Gi-Pay</label>
                                        <input type="text" class="form-control" id="id_toko_gipay" name="id_toko_gipay" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" class="form-control" id="amount" name="amount" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                                </form>
                            </div>
                        </div>
                    </div>`;
            } else {
                xhttp.open("GET", "content.php?type=" + contentType, true);
                xhttp.send();
            }
        }
    </script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
