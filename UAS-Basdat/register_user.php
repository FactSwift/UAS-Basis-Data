<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
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
      padding: 20px; 
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column; 
      align-items: center; 
      width: 300px; 
      margin: auto; 
      animation: fadeInTransform 1s ease-out; 
    }
    @keyframes fadeInTransform {
      0% { opacity: 0; transform: translateY(-20px); }
      100% { opacity: 1; transform: translateY(0); }
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
      padding: 10px; 
      margin-bottom: 10px; 
      border: none;
      border-radius: 5px;
      box-sizing: border-box; 
      width: 100%; 
    }
    .form-container .checkbox-container {
      display: flex;
      align-items: center;
      margin-bottom: 10px; 
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
    <h2>User Registration</h2>
    <form action="register_user_process.php" method="POST">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>
      
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
      
      <label for="nama_lengkap">Nama Lengkap:</label>
      <input type="text" id="nama_lengkap" name="nama_lengkap" required>
      
      <label for="nomor_hp">Nomor HP:</label>
      <input type="text" id="nomor_hp" name="nomor_hp" required>
      
      <label for="alamat_email">Alamat Email:</label>
      <input type="email" id="alamat_email" name="alamat_email" required>
      
      <div class="checkbox-container">
        <input type="checkbox" id="show-password-checkbox" onchange="togglePasswordVisibility()">
        <label for="show-password-checkbox">Tampilkan Password</label>
      </div>
      
      <input type="submit" value="Submit">
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
  </script>
</body>
</html>
