<?php
include '../fungsimenu/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['merchant_username'])) {
    header('Location: ../login.php');
    exit;
}

$merchantId = $_SESSION['merchant_id'];

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
