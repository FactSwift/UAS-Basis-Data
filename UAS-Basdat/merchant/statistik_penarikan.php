<?php
include '../config.php';
include '../fungsimenu/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['merchant_username'])) {
    header('Location: ../login.php');
    exit;
}

$merchantUsername = $_SESSION['merchant_username'];


$merchantQuery = "SELECT id_merchant FROM Merchant WHERE username = '$merchantUsername'";
$merchantResult = mysqli_query($db, $merchantQuery);
$merchant = mysqli_fetch_assoc($merchantResult);
$merchantId = $merchant['id_merchant'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    showWithdrawalStats($merchantId, $startDate, $endDate);
} else {
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
                .form-control-lg {
                    background-color: #f8f9fa;
                    border: 1px solid #ced4da;
                    color: #495057;
                }
                .form-control-lg:focus {
                    background-color: #fff;
                    border-color: #80bdff;
                    outline: 0;
                    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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
                <h1 class="mb-4">Statistik Penarikan Harian</h1>
                <form method="POST" action="" class="row g-3">
                    <div class="col-md-5">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control form-control-lg text-primary" required>
                    </div>
                    <div class="col-md-5">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control form-control-lg text-primary" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-lg w-100">View Statistics</button>
                    </div>
                </form>
            </div>
          </body>
          <script src="js/bootstrap.bundle.min.js"></script>
          </html>';
}
?>
