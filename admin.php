<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản trị viên HUMG</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/tuyensinh/css/style.css">
</head>
<body>
  <!-- Giữ lại header -->
  <header class="header header__active">
    <div class="header-container">
      <a href="trangchu.html" class="header-logo">HUMG</a>
      <div class="header-bars">
        <a href="admin.php">Trang quản trị</a>
        <a href="logout.php">Đăng xuất</a>
      </div>
    </div>
  </header>

  <div class="login-container">
    <h2>Chào mừng quản trị viên: <?php echo $_SESSION['username']; ?></h2>
    <p>Trang này sẽ hiển thị danh sách đăng ký tư vấn (trong bước tiếp theo).</p>
  </div>
</body>
</html>
