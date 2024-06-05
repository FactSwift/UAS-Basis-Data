<?php
session_start();
include '../config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM Admin WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin_username'] = $username;
        header('Location: admin_dashboard.php');
    } else {
        header('Location: admin_login.php?error=invalid');
    }
}
?>
