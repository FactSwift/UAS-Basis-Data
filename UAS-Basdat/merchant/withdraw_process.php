<?php
include '../config.php';
include '../fungsimenu/functions.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['merchant_username'])) {
    header('Location: ../login.php');
    exit;
}

$merchantId = $_SESSION['merchant_id'];
$accountNumber = $_POST['account_number'];
$bankName = $_POST['bank_name'];
$amount = $_POST['amount'];

withdrawPayment($merchantId, $accountNumber, $bankName, $amount);

header('Location: merchant_dashboard.php');
?>