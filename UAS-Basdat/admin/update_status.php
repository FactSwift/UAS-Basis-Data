
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
    $accountType = $_POST['account_type'];
    $accountId = $_POST['account_id'];
    $newStatus = $_POST['status_aktif'];

    $table = ($accountType == 'user') ? 'Pengguna' : 'Merchant';
    $idField = ($accountType == 'user') ? 'id_pengguna' : 'id_merchant';

    $sql = "UPDATE $table SET status_aktif = $newStatus WHERE $idField = $accountId";
    if (mysqli_query($db, $sql)) {
        echo "Account status updated successfully.";
    } else {
        echo "Error updating account status: " . mysqli_error($db);
    }
}
?>
