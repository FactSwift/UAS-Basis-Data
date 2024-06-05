<?php
include '../config.php';
include '../fungsimenu/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit;
}

function getBlockUserForm() {
    return '
        <form action="update_status.php" method="POST" class="form">
            <div class="mb-3">
                <label for="account_type" class="form-label">Account Type:</label>
                <select id="account_type" name="account_type" class="form-select">
                    <option value="user">User</option>
                    <option value="merchant">Merchant</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="account_id" class="form-label">Account ID:</label>
                <input type="number" id="account_id" name="account_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="status_aktif" class="form-label">Status:</label>
                <select id="status_aktif" name="status_aktif" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Blocked</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
        </form>
    ';
}

function updateGiPayCommissionForm() {
    return '
        <form action="update_commission.php" method="POST" class="form">
            <div class="mb-3">
                <label for="rate" class="form-label">New Commission Rate (%):</label>
                <input type="number" step="0.01" id="rate" name="rate" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Commission</button>
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
        case 'view_user_details':
            include 'view_user_details.php';
            break;
        case 'view_merchant_details':
            include 'view_merchant_details.php';
            break;
        case 'signup_statistics':
            include 'signup_statistics.php';
            break;
        default:
            echo "Content not found.";
    }
}
?>
