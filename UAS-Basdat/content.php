<?php
include('fungsimenu/functions.php');

if(isset($_GET['type'])) {
    $type = $_GET['type'];
    switch($type) {
        case 'profil':
            showProfile();
            break;
        case 'transaksi':
            showTransactions();
            break;
        case 'laporan':
            showReports();
            break;
        case 'pengaturan':
            showSettings();
            break;
        default:
            echo "Konten tidak ditemukan.";
    }
}
?>
