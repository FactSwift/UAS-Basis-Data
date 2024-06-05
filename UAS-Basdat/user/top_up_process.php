<?php
include '../fungsimenu/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id']; 
$amount = $_POST['amount'];

topUpSaldo($userId, $amount);

header('Location: user_dashboard.php');
?>
