<?php
include $_SERVER['DOCUMENT_ROOT'] . '/UAS-Basdat/config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function updateDailyTransactionStats($merchantId, $amount) {
    global $db;
    $date = date('Y-m-d');

    
    $checkQuery = "SELECT * FROM Statistik_Transaksi_Harian WHERE id_merchant = '$merchantId' AND tanggal = '$date'";
    $checkResult = mysqli_query($db, $checkQuery);
    if (!$checkResult) {
        throw new Exception("Error checking daily transaction stats: " . mysqli_error($db));
    }

    if (mysqli_num_rows($checkResult) > 0) {
        
        $updateQuery = "UPDATE Statistik_Transaksi_Harian 
                        SET total_transaksi = total_transaksi + 1, total_pembayaran = total_pembayaran + $amount 
                        WHERE id_merchant = '$merchantId' AND tanggal = '$date'";
        mysqli_query($db, $updateQuery);
        if (mysqli_affected_rows($db) <= 0) {
            throw new Exception("Failed to update daily transaction statistics: " . mysqli_error($db));
        }
    } else {
        
        $insertQuery = "INSERT INTO Statistik_Transaksi_Harian (tanggal, id_merchant, total_transaksi, total_pembayaran) 
                        VALUES ('$date', '$merchantId', 1, $amount)";
        mysqli_query($db, $insertQuery);
        if (mysqli_affected_rows($db) <= 0) {
            throw new Exception("Failed to insert daily transaction statistics: " . mysqli_error($db));
        }
    }
}

function updateDailyWithdrawalStats($merchantId, $amount) {
    global $db;

    $today = date('Y-m-d');

    
    $checkQuery = "SELECT * FROM Statistik_Penarikan_Dana_Harian WHERE id_merchant = '$merchantId' AND tanggal = '$today'";
    $checkResult = mysqli_query($db, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        
        $updateQuery = "UPDATE Statistik_Penarikan_Dana_Harian 
                        SET total_penarikan = total_penarikan + 1, total_penarikan_dana = total_penarikan_dana + $amount 
                        WHERE id_merchant = '$merchantId' AND tanggal = '$today'";
        mysqli_query($db, $updateQuery);
    } else {
        
        $insertQuery = "INSERT INTO Statistik_Penarikan_Dana_Harian (tanggal, id_merchant, total_penarikan, total_penarikan_dana) 
                        VALUES ('$today', '$merchantId', 1, '$amount')";
        mysqli_query($db, $insertQuery);
    }
}

function withdrawPayment($merchantId, $accountNumber, $bankName, $amount) {
    global $db;
    $merchant = mysqli_fetch_assoc(mysqli_query($db, "SELECT saldo_akhir FROM Merchant WHERE id_merchant = $merchantId"));
    
    if ($merchant && $merchant['saldo_akhir'] >= $amount) {
        mysqli_query($db, "INSERT INTO Penarikan_Dana (id_merchant, nomor_rekening, nama_bank, jumlah_penarikan, tanggal_penarikan, waktu_penarikan, status_penarikan) VALUES ($merchantId, '$accountNumber', '$bankName', $amount, CURDATE(), CURTIME(), 'Diproses')");
        mysqli_query($db, "UPDATE Merchant SET saldo_akhir = saldo_akhir - $amount WHERE id_merchant = $merchantId");
        echo "Withdrawal request successfully submitted.";
    } else {
        echo "Insufficient balance or merchant not found.";
    }
}

function checkAccountStatus($username, $isUser = true) {
    global $db;
    $table = $isUser ? 'Pengguna' : 'Merchant';
    $field = 'username';
    
    $sql = "SELECT status_aktif FROM $table WHERE $field = '$username'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['status_aktif'] == 0) {
        header('Location: blocked.php');
        exit;
    }
}

function topUpSaldo($userId, $amount) {
    global $db;
    $sql = "UPDATE Pengguna SET saldo = saldo + $amount WHERE id_pengguna = $userId";
    if (mysqli_query($db, $sql)) {
        echo "Saldo successfully topped up.";
    } else {
        echo "Error topping up saldo: " . mysqli_error($db);
    }
}

function useGiPay($userId, $merchantGiPayId, $amount, $password) {
    global $db;
    $userId = mysqli_real_escape_string($db, $userId);
    $merchantGiPayId = mysqli_real_escape_string($db, $merchantGiPayId);
    $amount = mysqli_real_escape_string($db, $amount);
    $password = mysqli_real_escape_string($db, $password);

    $userQuery = "SELECT * FROM Pengguna WHERE id_pengguna = '$userId'";
    $userResult = mysqli_query($db, $userQuery);
    $user = mysqli_fetch_assoc($userResult);

    $merchantQuery = "SELECT * FROM Merchant WHERE id_toko_gipay = '$merchantGiPayId'";
    $merchantResult = mysqli_query($db, $merchantQuery);
    $merchant = mysqli_fetch_assoc($merchantResult);

    $commissionQuery = "SELECT rate FROM GiPay_Commission ORDER BY effective_date DESC LIMIT 1";
    $commissionResult = mysqli_query($db, $commissionQuery);
    $commissionRate = mysqli_fetch_assoc($commissionResult)['rate'];

    if ($user && $merchant && $user['password'] == md5($password)) {
        if ($user['saldo'] >= $amount) {
            $netAmount = $amount - ($amount * $commissionRate / 100);
            $insertTransaction = "INSERT INTO Transaksi (id_pengguna, id_merchant, jumlah_pembayaran, tanggal_transaksi, waktu_transaksi, status_transaksi) VALUES ('$userId', '{$merchant['id_merchant']}', '$amount', CURDATE(), CURTIME(), 'Sukses')";
            mysqli_query($db, $insertTransaction);

            $updateUserSaldo = "UPDATE Pengguna SET saldo = saldo - $amount WHERE id_pengguna = '$userId'";
            mysqli_query($db, $updateUserSaldo);

            $updateMerchantSaldo = "UPDATE Merchant SET saldo_akhir = saldo_akhir + $netAmount WHERE id_merchant = '{$merchant['id_merchant']}'";
            mysqli_query($db, $updateMerchantSaldo);

            echo "Payment successful. Transaction recorded. You will be redirected shortly.";
        } else {
            echo "Insufficient balance.";
        }
    } else {
        echo "Invalid credentials or merchant not found.";
    }
    echo "<meta http-equiv='refresh' content='2;url=user_dashboard.php'>";
}


function showProfile() {
    global $db;
    if (!isset($_SESSION['username']) && !isset($_SESSION['merchant_username'])) {
        echo "<p>Session not started. Please log in.</p>";
        return;
    }
    
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $table = "Pengguna";
    } else {
        $username = $_SESSION['merchant_username'];
        $table = "Merchant";
    }

    $sql = "SELECT * FROM $table WHERE username = '$username'";
    $result = mysqli_query($db, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<style>
                .container-xl {
                    margin: 50px auto;
                    max-width: 800px;
                }
                .table-wrapper {
                    background: #fff;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .table-title {
                    padding-bottom: 15px;
                    background: #6c7ae0;
                    color: #fff;
                    padding: 16px 30px;
                    margin: -20px -20px 10px;
                    border-radius: 5px 5px 0 0;
                }
                .table-title h2 {
                    margin: 5px 0 0;
                    font-size: 24px;
                }
                .table-responsive {
                    margin: 30px 0;
                }
                .table-striped tbody tr:nth-of-type(odd) {
                    background-color: #f9f9f9;
                }
                .table th, .table td {
                    border-color: #e9e9e9;
                    padding: 12px 15px;
                    vertical-align: middle;
                }
                .table th {
                    width: 30%;
                    background: #f2f2f2;
                    font-weight: bold;
                }
              </style>
              <div class='container-xl'>
                <div class='table-responsive'>
                    <div class='table-wrapper'>
                        <div class='table-title'>
                            <div class='row'>
                                <div class='col-sm-6'>
                                    <h2>Profil <b>{$table}</b></h2>
                                </div>
                            </div>
                        </div>
                        <table class='table table-striped table-hover'>
                            <tbody>
                                <tr><th>Username</th><td>{$row['username']}</td></tr>";
        
        if ($table == "Pengguna") {
            echo "<tr><th>Nama Lengkap</th><td>{$row['nama_lengkap']}</td></tr>
                  <tr><th>Nomor HP</th><td>{$row['nomor_hp']}</td></tr>
                  <tr><th>Alamat Email</th><td>{$row['alamat_email']}</td></tr>
                  <tr><th>Saldo</th><td>{$row['saldo']}</td></tr>";
        } else {
            echo "<tr><th>Nama Toko</th><td>{$row['nama_toko']}</td></tr>
                  <tr><th>Alamat Toko</th><td>{$row['alamat_toko']}</td></tr>
                  <tr><th>Nomor HP</th><td>{$row['nomor_hp']}</td></tr>
                  <tr><th>Alamat Email</th><td>{$row['alamat_email']}</td></tr>
                  <tr><th>ID Toko Gipay</th><td>{$row['id_toko_gipay']}</td></tr>
                  <tr><th>Saldo Akhir</th><td>{$row['saldo_akhir']}</td></tr>";
        }

        echo "<tr><th>Status Aktif</th><td>" . ($row['status_aktif'] ? 'Aktif' : 'Tidak Aktif') . "</td></tr>
              </tbody>
              </table>
              </div>
              </div>
              </div>";
    } else {
        echo "<p>Profil tidak ditemukan.</p>";
    }
}

function blockUser($userId, $userType) {
    global $db;
    $table = $userType == 'Pengguna' ? 'Pengguna' : 'Merchant';
    $idField = $userType == 'Pengguna' ? 'id_pengguna' : 'id_merchant';
    
    $sql = "UPDATE $table SET status_aktif = FALSE WHERE $idField = $userId";
    if (mysqli_query($db, $sql)) {
        echo "User account has been blocked.";
    } else {
        echo "Error blocking user account: " . mysqli_error($db);
    }
}

function updateGiPayCommission($rate) {
    global $db;
    $effectiveDate = date('Y-m-d');
    
    $sql = "INSERT INTO GiPay_Commission (rate, effective_date) VALUES ($rate, '$effectiveDate')";
    if (mysqli_query($db, $sql)) {
        echo "Gi-Pay commission rate has been updated.";
    } else {
        echo "Error updating Gi-Pay commission rate: " . mysqli_error($db);
    }
}

function viewUserDetails($userId, $userType) {
    global $db;
    $table = $userType == 'Pengguna' ? 'Pengguna' : 'Merchant';
    $idField = $userType == 'Pengguna' ? 'id_pengguna' : 'id_merchant';
    
    $sql = "SELECT * FROM $table WHERE $idField = $userId";
    $result = mysqli_query($db, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<h2>User Details</h2>";
        echo "<p>Username: {$row['username']}</p>";
        if ($userType == 'Pengguna') {
            echo "<p>Nama Lengkap: {$row['nama_lengkap']}</p>";
            echo "<p>Nomor HP: {$row['nomor_hp']}</p>";
            echo "<p>Alamat Email: {$row['alamat_email']}</p>";
            echo "<p>Saldo: {$row['saldo']}</p>";
        } else {
            echo "<p>Nama Toko: {$row['nama_toko']}</p>";
            echo "<p>Alamat Toko: {$row['alamat_toko']}</p>";
            echo "<p>Nomor HP: {$row['nomor_hp']}</p>";
            echo "<p>Alamat Email: {$row['alamat_email']}</p>";
            echo "<p>ID Toko Gipay: {$row['id_toko_gipay']}</p>";
            echo "<p>Saldo Akhir: {$row['saldo_akhir']}</p>";
        }
        echo "<p>Status Aktif: " . ($row['status_aktif'] ? 'Aktif' : 'Tidak Aktif') . "</p>";
    } else {
        echo "User details not found.";
    }
}

function viewSignupStatistics() {
    global $db;
    
    $sqlPengguna = "SELECT COUNT(*) as total_pengguna FROM Pengguna";
    $resultPengguna = mysqli_query($db, $sqlPengguna);
    $totalPengguna = mysqli_fetch_assoc($resultPengguna)['total_pengguna'];
    
    $sqlMerchant = "SELECT COUNT(*) as total_merchant FROM Merchant";
    $resultMerchant = mysqli_query($db, $sqlMerchant);
    $totalMerchant = mysqli_fetch_assoc($resultMerchant)['total_merchant'];
    
    echo "<h2>Signup Statistics</h2>";
    echo "<p>Total Pengguna: $totalPengguna</p>";
    echo "<p>Total Merchant: $totalMerchant</p>";
}

function viewTransactionHistory($userId) {
    global $db;
    $sql = "SELECT * FROM Transaksi WHERE id_pengguna = $userId ORDER BY tanggal_transaksi DESC, waktu_transaksi DESC";
    $result = mysqli_query($db, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<h2>Transaction History</h2><ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>{$row['tanggal_transaksi']} {$row['waktu_transaksi']} - {$row['jumlah_pembayaran']} to Merchant ID: {$row['id_merchant']} - Status: {$row['status_transaksi']}</li>";
        }
        echo "</ul>";
    } else {
        echo "No transaction history found.";
    }
}

function viewMerchantBalance($merchantId) {
    global $db;
    $merchant = mysqli_fetch_assoc(mysqli_query($db, "SELECT saldo_akhir FROM Merchant WHERE id_merchant = $merchantId"));
    if ($merchant) {
        echo "Current balance: {$merchant['saldo_akhir']}";
    } else {
        echo "Merchant not found.";
    }
}

function getPaymentTransactions($merchantId, $startDate, $endDate) {
    global $db;
    $sql = "SELECT * FROM Transaksi WHERE id_merchant = $merchantId AND tanggal_transaksi BETWEEN '$startDate' AND '$endDate' ORDER BY tanggal_transaksi DESC, waktu_transaksi DESC";
    $result = mysqli_query($db, $sql);
    $transactions = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions[] = $row;
    }
    return $transactions;
}

function viewPaymentTransactionStats($merchantId, $startDate, $endDate) {
    global $db;

    
    if (empty($merchantId) || empty($startDate) || empty($endDate)) {
        echo "Invalid parameters provided.";
        return;
    }

    
    $sql = "SELECT * FROM Transaksi WHERE id_merchant = ? AND tanggal_transaksi BETWEEN ? AND ?";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        echo "Failed to prepare statement: " . $db->error;
        return;
    }

    
    if (!$stmt->bind_param("iss", $merchantId, $startDate, $endDate)) {
        echo "Failed to bind parameters: " . $stmt->error;
        return;
    }

    
    if (!$stmt->execute()) {
        echo "Failed to execute statement: " . $stmt->error;
        return;
    }

    
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID Transaksi</th><th>ID Pengguna</th><th>Jumlah Pembayaran</th><th>Tanggal</th><th>Waktu</th><th>Status</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['id_transaksi']) . "</td>
                    <td>" . htmlspecialchars($row['id_pengguna']) . "</td>
                    <td>" . htmlspecialchars($row['jumlah_pembayaran']) . "</td>
                    <td>" . htmlspecialchars($row['tanggal_transaksi']) . "</td>
                    <td>" . htmlspecialchars($row['waktu_transaksi']) . "</td>
                    <td>" . htmlspecialchars($row['status_transaksi']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No transactions found for the selected date range.";
    }

    $stmt->close();
}



function viewWithdrawalTransactionStats($merchantId, $startDate, $endDate) {
    global $db;
    $sql = "SELECT * FROM Penarikan_Dana WHERE id_merchant = $merchantId AND tanggal_penarikan BETWEEN '$startDate' AND '$endDate' ORDER BY tanggal_penarikan DESC, waktu_penarikan DESC";
    $result = mysqli_query($db, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<h2>Withdrawal Transaction Statistics from $startDate to $endDate</h2><ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>{$row['tanggal_penarikan']} {$row['waktu_penarikan']} - {$row['jumlah_penarikan']} to Account: {$row['nomor_rekening']} at {$row['nama_bank']} - Status: {$row['status_penarikan']}</li>";
        }
        echo "</ul>";
    } else {
        echo "No withdrawal transaction statistics found for the given date range.";
    }
}

function registerUser($username, $password, $nama_lengkap, $nomor_hp, $alamat_email) {
    global $db;
    $virtual_account = 'VA-' . $nomor_hp;
    $password_hashed = md5($password);

    $sql = "INSERT INTO Pengguna (username, password, nama_lengkap, nomor_hp, alamat_email, virtual_account) 
            VALUES ('$username', '$password_hashed', '$nama_lengkap', '$nomor_hp', '$alamat_email', '$virtual_account')";

    if (mysqli_query($db, $sql)) {
        echo "User registration successful.";
    } else {
        echo "Error: " . mysqli_error($db);
    }
}

function registerMerchant($username, $password, $nama_toko, $alamat_toko, $nomor_hp, $alamat_email) {
    global $db;
    $password_hashed = md5($password);

    $sql = "INSERT INTO Merchant (username, password, nama_toko, alamat_toko, nomor_hp, alamat_email, id_toko_gipay) 
            VALUES ('$username', '$password_hashed', '$nama_toko', '$alamat_toko', '$nomor_hp', '$alamat_email', '')";

    if (mysqli_query($db, $sql)) {
        $merchant_id = mysqli_insert_id($db);
        $id_toko_gipay = 'GI-' . $merchant_id;
        mysqli_query($db, "UPDATE Merchant SET id_toko_gipay = '$id_toko_gipay' WHERE id_merchant = $merchant_id");
        echo "Merchant registration successful. Gi-Pay ID: $id_toko_gipay";
    } else {
        echo "Error: " . mysqli_error($db);
    }
}

function showTransactionStats($merchantId, $startDate, $endDate) {
    global $db;
    $merchantId = mysqli_real_escape_string($db, $merchantId);
    $startDate = mysqli_real_escape_string($db, $startDate);
    $endDate = mysqli_real_escape_string($db, $endDate);

    $sql = "SELECT T.tanggal_transaksi, T.waktu_transaksi, P.username AS pengguna, T.jumlah_pembayaran, T.status_transaksi 
            FROM Transaksi T
            JOIN Pengguna P ON T.id_pengguna = P.id_pengguna
            WHERE T.id_merchant = '$merchantId' AND T.tanggal_transaksi BETWEEN '$startDate' AND '$endDate'
            ORDER BY T.tanggal_transaksi DESC, T.waktu_transaksi DESC";
    $result = mysqli_query($db, $sql);

    echo '<!DOCTYPE html>
          <html lang="en">
          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Statistik Transaksi Harian</title>
            <link href="css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                }
                .container-xl {
                    margin: 50px auto;
                    max-width: 800px;
                }
                .table-wrapper {
                    background: #fff;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .table-title {
                    padding-bottom: 15px;
                    background: #6c7ae0;
                    color: #fff;
                    padding: 16px 30px;
                    margin: -20px -20px 10px;
                    border-radius: 5px 5px 0 0;
                }
                .table-title h2 {
                    margin: 5px 0 0;
                    font-size: 24px;
                }
                .table-responsive {
                    margin: 30px 0;
                }
                .table-striped tbody tr:nth-of-type(odd) {
                    background-color: #f9f9f9;
                }
                .table th, .table td {
                    border-color: #e9e9e9;
                    padding: 12px 15px;
                    vertical-align: middle;
                }
                .table th {
                    width: 30%;
                    background: #f2f2f2;
                    font-weight: bold;
                }
            </style>
          </head>
          <body>
            <div class="container mt-5">
                <h2 class="mb-4">Statistik Transaksi Harian</h2>';
    
    if ($result && mysqli_num_rows($result) > 0) {
        echo '<div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Jumlah Pembayaran</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['tanggal_transaksi']}</td>
                    <td>{$row['waktu_transaksi']}</td>
                    <td>{$row['pengguna']}</td>
                    <td>{$row['jumlah_pembayaran']}</td>
                    <td>{$row['status_transaksi']}</td>
                  </tr>";
        }

        echo '      </tbody>
                </table>
              </div>
            </div>';
    } else {
        echo '<p class="text-center mt-4">No transaction statistics found for the given date range.</p>';
    }

    echo '  </body>
            <script src="js/bootstrap.bundle.min.js"></script>
          </html>';
}

function showUserTransactionHistory($userId, $startDate, $endDate) {
    global $db;
    $userId = mysqli_real_escape_string($db, $userId);
    $startDate = mysqli_real_escape_string($db, $startDate);
    $endDate = mysqli_real_escape_string($db, $endDate);

    $sql = "SELECT T.tanggal_transaksi, T.waktu_transaksi, M.nama_toko AS merchant, T.jumlah_pembayaran, T.status_transaksi 
            FROM Transaksi T
            JOIN Merchant M ON T.id_merchant = M.id_merchant
            WHERE T.id_pengguna = '$userId' AND T.tanggal_transaksi BETWEEN '$startDate' AND '$endDate'
            ORDER BY T.tanggal_transaksi DESC, T.waktu_transaksi DESC";
    $result = mysqli_query($db, $sql);

    echo '<!DOCTYPE html>
          <html lang="en">
          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Riwayat Transaksi Pengguna</title>
            <link href="css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                }
                .container-xl {
                    margin: 50px auto;
                    max-width: 800px;
                }
                .table-wrapper {
                    background: #fff;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .table-title {
                    padding-bottom: 15px;
                    background: #6c7ae0;
                    color: #fff;
                    padding: 16px 30px;
                    margin: -20px -20px 10px;
                    border-radius: 5px 5px 0 0;
                }
                .table-title h2 {
                    margin: 5px 0 0;
                    font-size: 24px;
                }
                .table-responsive {
                    margin: 30px 0;
                }
                .table-striped tbody tr:nth-of-type(odd) {
                    background-color: #f9f9f9;
                }
                .table th, .table td {
                    border-color: #e9e9e9;
                    padding: 12px 15px;
                    vertical-align: middle;
                }
                .table th {
                    width: 30%;
                    background: #f2f2f2;
                    font-weight: bold;
                }
            </style>
          </head>
          <body>
            <div class="container mt-5">
                <h2 class="mb-4">Riwayat Transaksi Pengguna</h2>';

    if ($result && mysqli_num_rows($result) > 0) {
        echo '<div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Merchant</th>
                            <th>Jumlah Pembayaran</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['tanggal_transaksi']}</td>
                    <td>{$row['waktu_transaksi']}</td>
                    <td>{$row['merchant']}</td>
                    <td>{$row['jumlah_pembayaran']}</td>
                    <td>{$row['status_transaksi']}</td>
                  </tr>";
        }

        echo '      </tbody>
                </table>
              </div>
            </div>';
    } else {
        echo '<p class="text-center mt-4">No transaction history found for the given date range.</p>';
    }

    echo '  </body>
            <script src="js/bootstrap.bundle.min.js"></script>
          </html>';
}


function showWithdrawalStats($merchantId, $startDate, $endDate) {
    global $db;
    $merchantId = mysqli_real_escape_string($db, $merchantId);
    $startDate = mysqli_real_escape_string($db, $startDate);
    $endDate = mysqli_real_escape_string($db, $endDate);

    $sql = "SELECT * FROM Statistik_Penarikan_Dana_Harian WHERE id_merchant = '$merchantId' AND tanggal BETWEEN '$startDate' AND '$endDate' ORDER BY tanggal DESC";
    $result = mysqli_query($db, $sql);

    echo '<!DOCTYPE html>
          <html lang="en">
          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Statistik Penarikan Harian</title>
            <link href="css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                }
                .container-xl {
                    margin: 50px auto;
                    max-width: 800px;
                }
                .table-wrapper {
                    background: #fff;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .table-title {
                    padding-bottom: 15px;
                    background: #6c7ae0;
                    color: #fff;
                    padding: 16px 30px;
                    margin: -20px -20px 10px;
                    border-radius: 5px 5px 0 0;
                }
                .table-title h2 {
                    margin: 5px 0 0;
                    font-size: 24px;
                }
                .table-responsive {
                    margin: 30px 0;
                }
                .table-striped tbody tr:nth-of-type(odd) {
                    background-color: #f9f9f9;
                }
                .table th, .table td {
                    border-color: #e9e9e9;
                    padding: 12px 15px;
                    vertical-align: middle;
                }
                .table th {
                    width: 30%;
                    background: #f2f2f2;
                    font-weight: bold;
                }
            </style>
          </head>
          <body>
            <div class="container mt-5">
                <h2 class="mb-4">Statistik Penarikan Harian</h2>';

    if ($result && mysqli_num_rows($result) > 0) {
        echo '<div class="table-responsive">
                <table class="table table-hover table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Total Penarikan</th>
                            <th>Total Penarikan Dana</th>
                        </tr>
                    </thead>
                    <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['tanggal']}</td>
                    <td>{$row['total_penarikan']}</td>
                    <td>{$row['total_penarikan_dana']}</td>
                  </tr>";
        }

        echo '      </tbody>
                </table>
              </div>
            </div>';
    } else {
        echo '<p class="text-center mt-4">No withdrawal statistics found for the given date range.</p>';
    }

    echo '  </body>
            <script src="js/bootstrap.bundle.min.js"></script>
          </html>';
}

function processWithdrawal($merchantId, $amount) {
    global $db;

    
    $merchantQuery = "SELECT saldo_akhir FROM Merchant WHERE id_merchant = '$merchantId'";
    $merchantResult = mysqli_query($db, $merchantQuery);
    $merchant = mysqli_fetch_assoc($merchantResult);

    if ($merchant && $merchant['saldo_akhir'] >= $amount) {
        
        $newBalance = $merchant['saldo_akhir'] - $amount;
        $updateBalanceQuery = "UPDATE Merchant SET saldo_akhir = '$newBalance' WHERE id_merchant = '$merchantId'";
        if (mysqli_query($db, $updateBalanceQuery)) {
            
            $insertWithdrawalQuery = "INSERT INTO Penarikan_Dana (id_merchant, jumlah_penarikan, tanggal_penarikan, waktu_penarikan, status_penarikan)
                                      VALUES ('$merchantId', '$amount', CURDATE(), CURTIME(), 'Selesai')";
            if (mysqli_query($db, $insertWithdrawalQuery)) {
                return true;
            }
        }
    }
    return false;
}

function showWithdrawFunds() {
    echo "<h2>Penarikan Dana</h2>";
    echo "<form action='withdraw_funds.php' method='POST'>
            <label for='nomor_rekening'>Nomor Rekening:</label>
            <input type='text' id='nomor_rekening' name='nomor_rekening' required><br>
            <label for='nama_bank'>Nama Bank:</label>
            <input type='text' id='nama_bank' name='nama_bank' required><br>
            <label for='jumlah_penarikan'>Jumlah Penarikan:</label>
            <input type='number' id='jumlah_penarikan' name='jumlah_penarikan' required><br>
            <input type='submit' value='Withdraw'>
          </form>";
}
?>
