<!DOCTYPE html>
<html>
<head>
    <title>Register Merchant</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            background-color: #34495e;
            color: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 5px;
        }
        .form-container input[type="text"],
        .form-container input[type="password"],
        .form-container input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #e67e22;
            border: none;
            border-radius: 5px;
            color: #ecf0f1;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #d35400;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register Merchant</h2>
        <form action="register_merchant_process.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="nama_toko">Nama Toko:</label>
            <input type="text" id="nama_toko" name="nama_toko" required>
            <label for="alamat_toko">Alamat Toko:</label>
            <input type="text" id="alamat_toko" name="alamat_toko" required>
            <label for="nomor_hp">Nomor HP:</label>
            <input type="text" id="nomor_hp" name="nomor_hp" required>
            <label for="alamat_email">Alamat Email:</label>
            <input type="email" id="alamat_email" name="alamat_email" required>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
