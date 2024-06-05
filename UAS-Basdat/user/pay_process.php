
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
$merchantGiPayId = mysqli_real_escape_string($db, $_POST['merchant_id']);
$amount = mysqli_real_escape_string($db, $_POST['amount']);
$password = mysqli_real_escape_string($db, $_POST['password']);

useGiPay($userId, $merchantGiPayId, $amount, $password);

header('Location: user_dashboard.php');
?>