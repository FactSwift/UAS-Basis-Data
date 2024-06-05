
<?php
session_start();
include 'config.php'; 

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = md5(mysqli_real_escape_string($db, $_POST['password']));

    
    $sqlUser = "SELECT * FROM Pengguna WHERE username = '$username' AND password = '$password'";
    $resultUser = mysqli_query($db, $sqlUser);

    if (mysqli_num_rows($resultUser) == 1) {
        $user = mysqli_fetch_assoc($resultUser);
        if ($user['status_aktif'] == 0) {
            header('Location: blocked.php');
            exit;
        }
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id_pengguna']; 
        header('Location: user/user_dashboard.php'); 
    } else {
        
        $sqlMerchant = "SELECT * FROM Merchant WHERE username = '$username' AND password = '$password'";
        $resultMerchant = mysqli_query($db, $sqlMerchant);

        if (mysqli_num_rows($resultMerchant) == 1) {
            $merchant = mysqli_fetch_assoc($resultMerchant);
            if ($merchant['status_aktif'] == 0) {
                header('Location: blocked.php');
                exit;
            }
            $_SESSION['merchant_username'] = $username;
            $_SESSION['merchant_id'] = $merchant['id_merchant']; 
            header('Location: merchant/merchant_dashboard.php'); 
        } else {
            header('Location: login.php?error=invalid'); 
        }
    }
}
?>
