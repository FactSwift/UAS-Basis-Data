<?php
include '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['merchant_username'])) {
    header('Location: ../login.php');
    exit;
}

$merchantId = $_SESSION['merchant_id'];
$startDate = $_POST['start_date'];
$endDate = $_POST['end_date'];

viewWithdrawalTransactionStats($merchantId, $startDate, $endDate);

header('Location: merchant_dashboard.php');
?>