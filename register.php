<?php
require 'db_connect.php';

if(isset($_POST['register'])){
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO users(username,password) VALUES(?,?)");
  $stmt->bind_param("ss", $username,$password);
  
  if($stmt->execute()){
    header("Location: login.php");
    exit;
  } else {
    $error = "Username sudah terdaftar!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .form-container {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      width: 350px;
      text-align: center;
    }
    .form-container h2 {
      margin-bottom: 20px;
      color: #ff5722;
    }
    .form-container input {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }
    .form-container button {
      width: 100%;
      padding: 12px;
      background: #ff5722;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 10px;
    }
    .form-container button:hover {
      background: #e64a19;
    }
    .form-container .login-link {
      display: block;
      margin-top: 15px;
      font-size: 14px;
      color: #555;
    }
    .form-container .login-link a {
      color: #ff5722;
      text-decoration: none;
    }
    .form-container .login-link a:hover {
      text-decoration: underline;
    }
    .error {
      background: #f8d7da;
      color: #721c24;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Register</h2>
    <?php if(!empty($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="register">Register</button>
    </form>
    <div class="login-link">
      Sudah punya akun? <a href="login.php">Login</a>
    </div>
  </div>
</body>
</html>
