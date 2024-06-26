<?php
include '../fungsimenu/functions.php';

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    switch ($type) {
        case 'profil':
            showProfile();
            break;
        case 'transaksi':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $startDate = $_POST['start_date'];
                $endDate = $_POST['end_date'];
                $merchantId = $_SESSION['merchant_id'];
                showTransactionStats($merchantId, $startDate, $endDate);
            } else {
                echo '<form method="POST" action="">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" required>
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" required>
                        <input type="submit" value="View Statistics">
                      </form>';
            }
            break;
        case 'penarikan':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $startDate = $_POST['start_date'];
                $endDate = $_POST['end_date'];
                $merchantId = $_SESSION['merchant_id'];
                showWithdrawalStats($merchantId, $startDate, $endDate);
            } else {
                echo '<form method="POST" action="">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" required>
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" required>
                        <input type="submit" value="View Statistics">
                      </form>';
            }
            break;
        case 'top_up':
            echo '<form action="top_up_process.php" method="POST">
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Top Up</button>
                  </form>';
            break;
        case 'pay':
            echo '<form action="pay_process.php" method="POST">
                    <div class="form-group">
                        <label for="merchant_id">Merchant ID:</label>
                        <input type="text" class="form-control" id="merchant_id" name="merchant_id" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Pay</button>
                  </form>';
            break;
        case 'view_balance':
            $merchantId = $_SESSION['merchant_id'];
            viewMerchantBalance($merchantId);
            break;
        case 'withdraw':
            echo '<form action="withdraw_process.php" method="POST">
                    <label for="account_number">Account Number:</label>
                    <input type="text" id="account_number" name="account_number" required>
                    <label for="bank_name">Bank Name:</label>
                    <input type="text" id="bank_name" name="bank_name" required>
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" required>
                    <input type="submit" value="Withdraw">
                  </form>';
            break;
        case 'payment_stats':
            echo '<form action="payment_stats_process.php" method="POST">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required>
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" required>
                    <input type="submit" value="View Stats">
                  </form>';
            break;
        case 'withdrawal_stats':
            echo '<form action="withdrawal_stats_process.php" method="POST">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required>
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" required>
                    <input type="submit" value="View Stats">
                  </form>';
            break;
        default:
            echo "Content not found.";
    }
}
?>
