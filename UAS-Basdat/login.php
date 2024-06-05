<!DOCTYPE html>
<html>
<head>
    <title>Formulir Masuk Pengguna </title>
    <style>
        .login-container a {
            color: #ffffff; 
        }
        body {
            background-image: url('uang.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;   
            color: #ecf0f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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
        .login-container h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-container label {
            display: block;
            margin-bottom: 5px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #e67e22;
            border: none;
            border-radius: 5px;
            color: #ecf0f1;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container input[type="submit"]:hover {
            background-color: #d35400;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <header>
            <h3>Masuk</h3>
        </header>
        <form action="loginproses.php" method="POST">
            <div class="input-group">
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" placeholder="Username" required />
            </div>
            <div class="input-group">
                <label for="password">Password: </label>
                <input type="password" id="password" name="password" placeholder="Password" required />
                <input type="checkbox" id="show-password"> Tampilkan Password
            </div>
            <div class="button-container">
                <button type="submit" value="Masuk" name="login" class="login-button">Login</button>
            </div>
        </form>
        <p style="margin-bottom: 1px;">Belum daftar? <a href="javascript:void(0)" onclick="redirectToIndex()">Daftar di sini</a></p>
    </div>
    <script>
        document.getElementById('show-password').addEventListener('change', function() {
            var passwordInput = document.getElementById('password');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
        document.querySelector('.login-container').style.transition = 'opacity 0.5s';
        function redirectToIndex() {
            var container = document.querySelector('.login-container');
            container.style.opacity = 0;
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 500);
        }
    </script>
</body>
</html>
