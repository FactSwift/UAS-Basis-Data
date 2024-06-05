<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama_toko = $_POST['nama_toko'];
    $alamat_toko = $_POST['alamat_toko'];
    $nomor_hp = $_POST['nomor_hp'];
    $alamat_email = $_POST['alamat_email'];

    registerMerchant($username, $password, $nama_toko, $alamat_toko, $nomor_hp, $alamat_email);
}

function registerMerchant($username, $password, $nama_toko, $alamat_toko, $nomor_hp, $alamat_email) {
    global $db;
    $password_hashed = md5($password);

    
    $sql = "INSERT INTO Merchant (username, password, nama_toko, alamat_toko, nomor_hp, alamat_email, id_toko_gipay) 
            VALUES ('$username', '$password_hashed', '$nama_toko', '$alamat_toko', '$nomor_hp', '$alamat_email', '')";

    if (mysqli_query($db, $sql)) {
        
        $merchant_id = mysqli_insert_id($db);
        $id_toko_gipay = 'GI-' . $merchant_id;

       
        mysqli_query($db, "UPDATE Merchant SET id_toko_gipay = '$id_toko_gipay' WHERE id_merchant = $merchant_id");

        echo "Merchant registration successful. Your Gi-Pay ID is: $id_toko_gipay";
    } else {
        echo "Error: " . mysqli_error($db);
    }
}
?>