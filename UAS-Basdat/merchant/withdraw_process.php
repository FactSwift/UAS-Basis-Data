<?php
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

$withdrawQuery = "INSERT INTO Penarikan_Dana (id_merchant, nomor_rekening, nama_bank, jumlah_penarikan, tanggal_penarikan, waktu_penarikan, status_penarikan) 
                  VALUES ('$merchantId', '$nomorRekening', '$namaBank', '$amount', CURDATE(), CURTIME(), 'Menunggu')";
mysqli_query($db, $withdrawQuery);


updateDailyWithdrawalStats($merchantId, $amount);

header('Location: merchant_dashboard.php');
?>
