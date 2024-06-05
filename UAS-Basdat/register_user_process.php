<?php
include 'config.php';

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $nama_lengkap = $_POST['nama_lengkap'];
    $nomor_hp = $_POST['nomor_hp'];
    $alamat_email = $_POST['alamat_email'];

    $cekUsername = "SELECT username FROM Pengguna WHERE username = '$username'";
    $hasil = mysqli_query($db, $cekUsername);
    if (mysqli_num_rows($hasil) > 0) {
        header('Location: register_user.php?status=usernameada');
        exit;
    }

    $sql = "INSERT INTO Pengguna (username, password, nama_lengkap, nomor_hp, alamat_email, status_aktif) 
            VALUES ('$username', '$password', '$nama_lengkap', '$nomor_hp', '$alamat_email', 1)";
    $query = mysqli_query($db, $sql);

    if ($query) {
        header('Location: login.php?status=sukses');
    } else {
        header('Location: register_user.php?status=gagal');
    }
}
?>
