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
$nomorRekening = mysqli_real_escape_string($db, $_POST['nomor_rekening']);
$namaBank = mysqli_real_escape_string($db, $_POST['nama_bank']);
$amount = mysqli_real_escape_string($db, $_POST['amount']);


$balanceQuery = "SELECT saldo_akhir FROM Merchant WHERE id_merchant = '$merchantId'";
$balanceResult = mysqli_query($db, $balanceQuery);
$merchant = mysqli_fetch_assoc($balanceResult);

if ($merchant['saldo_akhir'] < $amount) {
    
    header('Location: merchant_dashboard.php?status=insufficient_balance');
    exit;
}


mysqli_begin_transaction($db);

try {
    
    $withdrawQuery = "INSERT INTO Penarikan_Dana (id_merchant, nomor_rekening, nama_bank, jumlah_penarikan, tanggal_penarikan, waktu_penarikan, status_penarikan) 
                      VALUES ('$merchantId', '$nomorRekening', '$namaBank', '$amount', CURDATE(), CURTIME(), 'Menunggu')";
    mysqli_query($db, $withdrawQuery);

    
    $updateMerchantSaldo = "UPDATE Merchant SET saldo_akhir = saldo_akhir - $amount WHERE id_merchant = '$merchantId'";
    mysqli_query($db, $updateMerchantSaldo);

    
    updateDailyWithdrawalStats($merchantId, $amount);

    
    mysqli_commit($db);
    header('Location: merchant_dashboard.php?status=withdrawal_success');
} catch (Exception $e) {
    
    mysqli_rollback($db);
    header('Location: merchant_dashboard.php?status=withdrawal_error');
}
?>
