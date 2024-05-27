<?php
session_start();
include 'config.php'; 

if (isset($_POST['login'])){
$username = $_POST['fullname'];
$password = md5($_POST['password']);

$sql = "SELECT * FROM Pengguna WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($db, $sql);

if (mysqli_num_rows($result) == 1) {
    $_SESSION['username'] = $username;
    header('Location: menu.php'); 
} else {
    header('Location: login.php?error=invalid'); 
}
}
?>
