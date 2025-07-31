<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập quản trị</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Sử dụng lại CSS bạn đang dùng -->
  <link rel="stylesheet" href="assets/tuyensinh/css/style.css"> <!-- Đường dẫn đúng theo bạn -->
  <style>
    .login-container {
      max-width: 400px;
      margin: 80px auto;
      padding: 30px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .login-container input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .login-container button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .login-container button:hover {
      background-color: #0056d2;
    }
  </style>
</head>
<body>
  <!-- Phần header giống tuyensinh.html -->
  <header class="header header__active">
    <div class="header-container">
      <a href="trangchu.html" class="header-logo">HUMG</a>
      <div class="header-bars">
        <a href="tuyensinh.html">Tuyển sinh</a>
        <a href="hosoxettuyen.html">Nguyện vọng ảo</a>
        <a href="web.html">Tự học</a>
        <a href="login.php" class="header-bars__admin">Đăng nhập</a>
      </div>
    </div>
  </header>

  <!-- Form đăng nhập -->
  <div class="login-container">
    <h2>Đăng nhập quản trị</h2>
    <form method="POST" action="auth.php">
      <input type="text" name="username" placeholder="Tên đăng nhập" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <button type="submit">Đăng nhập</button>
    </form>
  </div>
</body>
</html>
