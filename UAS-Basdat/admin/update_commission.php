
<?php
include '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rate = mysqli_real_escape_string($db, $_POST['rate']);

    $sql = "INSERT INTO GiPay_Commission (rate, effective_date) VALUES ('$rate', CURDATE())";
    if (mysqli_query($db, $sql)) {
        echo "Gi-Pay commission rate updated successfully.";
    } else {
        echo "Error updating commission rate: " . mysqli_error($db);
    }
}
?>
