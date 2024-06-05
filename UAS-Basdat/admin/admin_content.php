
<?php
include '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit;
}

function getBlockUserForm() {
    return '
        <form action="update_status.php" method="POST">
            <label for="account_type">Account Type:</label>
            <select id="account_type" name="account_type">
                <option value="user">User</option>
                <option value="merchant">Merchant</option>
            </select>
            <br>
            <label for="account_id">Account ID:</label>
            <input type="number" id="account_id" name="account_id" required>
            <br>
            <label for="status_aktif">Status:</label>
            <select id="status_aktif" name="status_aktif">
                <option value="1">Active</option>
                <option value="0">Blocked</option>
            </select>
            <br>
            <input type="submit" value="Update Status">
        </form>
    ';
}

function updateGiPayCommissionForm() {
    return '
        <form action="update_commission.php" method="POST">
            <label for="rate">New Commission Rate (%):</label>
            <input type="number" step="0.01" id="rate" name="rate" required>
            <br>
            <input type="submit" value="Update Commission">
        </form>
    ';
}

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    switch ($type) {
        case 'block_user':
            echo getBlockUserForm();
            break;
        case 'update_commission':
            echo updateGiPayCommissionForm();
            break;
        case 'view_user':
            break;
        case 'signup_stats':
            break;
        default:
            echo "Content not found.";
    }
}
?>
