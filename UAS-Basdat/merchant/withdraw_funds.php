<?php
include '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['merchant_username'])) {
    header('Location: ../login.php');
    exit;
}

if (isset($_POST['nomor_rekening']) && isset($_POST['nama_bank']) && isset($_POST['jumlah_penarikan'])) {
    $username = $_SESSION['merchant_username'];
    $merchant = getMerchantDetails($username);
    $id_merchant = $merchant['id_merchant'];
    
    $nomor_rekening = $_POST['nomor_rekening'];
    $nama_bank = $_POST['nama_bank'];
    $jumlah_penarikan = $_POST['jumlah_penarikan'];
    $tanggal_penarikan = date('Y-m-d');
    $waktu_penarikan = date('H:i:s');

    $sql = "INSERT INTO Penarikan_Dana (id_merchant, nomor_rekening, nama_bank, jumlah_penarikan, tanggal_penarikan, waktu_penarikan, status_penarikan) 
            VALUES ($id_merchant, '$nomor_rekening', '$nama_bank', $jumlah_penarikan, '$tanggal_penarikan', '$waktu_penarikan', 'Menunggu')";
    $query = mysqli_query($db, $sql);

    if ($query) {
        header('Location: merchant_dashboard.php?status=penarikansukses');
    } else {
        header('Location: merchant_dashboard.php?status=penarikangagal');
    }
} else {
    header('Location: merchant_dashboard.php?status=penarikangagal');
}
?>