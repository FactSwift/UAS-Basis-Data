<?php
include '../config.php';
include '../fungsimenu/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id']; 
    $id_toko_gipay = $_POST['id_toko_gipay'];
    $amount = $_POST['amount'];
    $password = $_POST['password'];

    
    useGiPay($userId, $id_toko_gipay, $amount, $password);
}
?>
