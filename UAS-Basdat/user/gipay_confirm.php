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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_toko_gipay = $_POST['id_toko_gipay'];
    $amount = $_POST['amount'];

    
    $sql = "SELECT * FROM Merchant WHERE id_toko_gipay = '$id_toko_gipay'";
    $result = mysqli_query($db, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $merchant = mysqli_fetch_assoc($result);
        $storeName = $merchant['nama_toko'];
        $storeAddress = $merchant['alamat_toko'];
        $date = date("Y-m-d");
        $time = date("H:i:s");
    } else {
        echo "Store not found.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Payment</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Confirm Payment</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Please confirm your payment details</h5>
                <p><strong>Store Name:</strong> <?php echo $storeName; ?></p>
                <p><strong>Store Address:</strong> <?php echo $storeAddress; ?></p>
                <p><strong>Date:</strong> <?php echo $date; ?></p>
                <p><strong>Time:</strong> <?php echo $time; ?></p>
                <p><strong>Amount:</strong> <?php echo $amount; ?></p>
                <form action="gipay_process.php" method="POST">
                    <input type="hidden" name="id_toko_gipay" value="<?php echo $id_toko_gipay; ?>">
                    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-success">Confirm and Pay</button>
                    <a href="user_dashboard.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
