<?php
include '../config.php';
include '../fungsimenu/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

$username = $_SESSION['username'];


$userQuery = "SELECT id_pengguna FROM Pengguna WHERE username = '$username'";
$userResult = mysqli_query($db, $userQuery);
$user = mysqli_fetch_assoc($userResult);
$userId = $user['id_pengguna'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    showUserTransactionHistory($userId, $startDate, $endDate);
} else {
    echo '<form method="POST" action="">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>
            <input type="submit" value="View History">
          </form>';
}
?>
