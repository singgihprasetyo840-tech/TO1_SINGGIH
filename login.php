<?php
require 'db_connect.php';

if(isset($_POST['login'])){
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if($user && password_verify($password, $user['password'])){
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    header("Location: index.php");
    exit;
  } else {
    $error = "Username atau password salah!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
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
    .form-container .register-link {
      display: block;
      margin-top: 15px;
      font-size: 14px;
      color: #555;
    }
    .form-container .register-link a {
      color: #ff5722;
      text-decoration: none;
    }
    .form-container .register-link a:hover {
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
    <h2>Login</h2>
    <?php if(!empty($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
    </form>
    <div class="register-link">
      Belum punya akun? <a href="register.php">Daftar</a>
    </div>
  </div>
</body>
</html>
