<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../uang.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #ecf0f1;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .login-container {
            background-color: #34495e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: fadeInTransform 1s ease-out;
        }
        @keyframes fadeInTransform {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .form-control {
            background-color: #2c3e50;
            color: #ecf0f1;
            border: none;
        }
        .form-control:focus {
            background-color: #2c3e50;
        }
        .btn-custom {
            background-color: #e67e22;
            color: #ecf0f1;
        }
        .btn-custom:hover {
            background-color: #d35400;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <header>
            <h3>Admin Login</h3>
        </header>
        <form action="admin_login_process.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                <div class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" id="show-password">
                    <label class="form-check-label" for="show-password">Show Password</label>
                </div>
            </div>
            <button type="submit" value="Masuk" name="login" class="btn btn-custom btn-lg w-100">Login</button>
        </form>
        <div class="mt-3">
            <a href="../index.php" class="btn btn-secondary w-100">Back to Main Page</a>
        </div>
    </div>
    
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('show-password').addEventListener('change', function() {
            var passwordInput = document.getElementById('password');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    </script>
</body>
</html>
