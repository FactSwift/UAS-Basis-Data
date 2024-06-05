<?php
include 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['username'])) {
    header('Location: user/user_dashboard.php');
    exit;
} elseif (isset($_SESSION['merchant_username'])) {
    header('Location: merchant/merchant_dashboard.php');
    exit;
} elseif (isset($_SESSION['admin_username'])) {
    header('Location: admin/admin_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Main Index</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url('uang.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed; 
      color: #ecf0f1;
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
    .admin-login {
      position: absolute;
      top: 10px;
      right: 10px;
    }
    .github-link {
      position: absolute;
      top: 10px;
      left: 10px;
    }
    .btn-custom {
      width: 200px;
      margin: 10px 0;
    }
    .container .footer-text {
      margin-top: 30px;
    }
    .github-icon {
      width: 24px;
      height: 24px;
      vertical-align: middle;
      margin-right: 8px;
    }
  </style>
</head>
<body>
  <div class="admin-login">
    <a href="admin/admin_login.php" class="btn btn-warning">Admin Login</a>
  </div>
  <div class="github-link">
    <a href="https://github.com/FactSwift/UAS-ASD" class="btn btn-dark" target="_blank">
      <img src="git.png" alt="GitHub" class="github-icon"> GitHub
    </a>
  </div>
  <div class="container">
    <h1 class="display-3">FinFun</h1>
    <p class="lead">Made by Kelompok Enam - MKB 2A</p>
    <div class="mt-5 d-flex flex-column align-items-center">
      <a href="register_user.php" class="btn btn-primary btn-lg btn-custom">Register as User</a>
      <a href="register_merchant.php" class="btn btn-primary btn-lg btn-custom">Register as Merchant</a>
      <a href="login.php" class="btn btn-primary btn-lg btn-custom">Login</a>
    </div>
    <footer class="footer-text">
      <p class="text-light">Sponsored by GI-Pay</p>
    </footer>
  </div>
  
  <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
