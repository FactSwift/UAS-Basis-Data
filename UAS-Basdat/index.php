<!DOCTYPE html>
<html>
<head>
  <title>Form Pendaftaran</title>
  <style>
    body {
      background-image: url('uang.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed; 
      color: #ecf0f1;
      display: flex;
      justify-content: flex-start; 
      align-items: center;
      height: 100vh;
      margin: 0;
      padding-top: 20px; 
    }
    .form-container {
      background-color: #34495e;
      padding: 10px; 
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column; 
      align-items: center; 
      width: 250px; 
      margin: auto; 
      animation: fadeInTransform 1s ease-out; 
    }
    @keyframes fadeInTransform {
      0% { opacity: 0; transform: translateY(-20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    .form-container h2 {
      text-align: center;
      margin-bottom: 10px; 
    }
    .form-container label {
      display: block; 
      margin-bottom: 1px; 
    }
    .form-container input[type="text"],
    .form-container input[type="password"],
    .form-container input[type="email"] {
      padding: 5px 10px; 
      margin-bottom: 5px; 
      border: none;
      border-radius: 5px;
      box-sizing: border-box; 
      width: 100%; 
    }
    .form-container input[type="radio"] {
      margin-right: 10px;
    }
    .form-container .checkbox-container {
      display: flex;
      align-items: center;
      margin-bottom: 5px; 
      justify-content: center; 
    }
    .form-container .checkbox-container label {
      margin-left: 5px;
      margin-bottom: 0; 
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
      margin-top: 10px; 
    }
    .form-container input[type="submit"]:hover {
      background-color: #d35400;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Form Pendaftaran</h2>
    <form action="indexproses.php" method="POST">
      <label for="username">Username:</label><br>
      <input type="text" id="username" name="username" required><br>
      <label for="password">Password:</label><br>
      <input type="password" id="password" name="password" required>

      <label for="nama_lengkap">Nama Lengkap:</label><br>
      <input type="text" id="nama_lengkap" name="nama_lengkap" required><br>
      <label for="nomor_hp">Nomor HP:</label><br>
      <input type="text" id="nomor_hp" name="nomor_hp" required><br>
      <label for="alamat_email">Alamat Email:</label><br>
      <input type="email" id="alamat_email" name="alamat_email" required><br><br>
      <div class="checkbox-container">
        <input type="checkbox" id="show-password-checkbox"
        <input type="checkbox" id="show-password-checkbox" onchange="togglePasswordVisibility()">
        <label for="show-password-checkbox">Tampilkan Password</label>
      </div>
      <button type="submit" value="Masuk" name="login" class="login-button">Submit</button>
      <a href="javascript:void(0)" onclick="redirectToLogin()" style="color: #ffffff;">Sudah punya akun?</a>
    </form>
  </div>

  <script>
    function togglePasswordVisibility() {
      var passwordInput = document.getElementById('password');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
      } else {
        passwordInput.type = 'password';
      }
    }

    function redirectToLogin() {
      var container = document.querySelector('.form-container');
      container.style.opacity = 0;
      container.style.transition = 'opacity 0.5s ease-out';
      setTimeout(function() {
        window.location.href = 'login.php';
      }, 500); 
    }
  </script>
</body>
</html>
