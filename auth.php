<?php
session_start();

// Login giả lập: tài khoản cố định
$correct_username = "admin";
$correct_password = "123456";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === $correct_username && $password === $correct_password) {
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;
    header("Location: admin.php");
} else {
    echo "<script>alert('Sai tài khoản hoặc mật khẩu!'); window.location='login.php';</script>";
}
?>
