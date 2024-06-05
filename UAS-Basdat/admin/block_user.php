<?php
include '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit;
}

if (isset($_POST['user_id']) && isset($_POST['user_type'])) {
    $userId = $_POST['user_id'];
    $userType = $_POST['user_type'];
    blockUser($userId, $userType);
}
?>
