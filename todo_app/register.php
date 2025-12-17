<?php
require 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $password])) {
        header("Location: login.php");
        exit;
    } else {
        echo "<script>alert('Lỗi đăng ký!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng ký tài khoản</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --pink-main: #ff5c8d;
  --pink-light: #fff6f9;
  --pink-accent: #ffd6e8;
}
body {
  background: var(--pink-light);
  font-family: "Inter", sans-serif;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}
.card-register {
  background: #fff;
  border: none;
  border-radius: 22px;
  box-shadow: 0 4px 20px rgba(255, 182, 193, 0.3);
  width: 400px;
  padding: 2rem;
  animation: fadeIn 0.8s ease;
}
@keyframes fadeIn {
  from {opacity:0; transform:translateY(15px);}
  to {opacity:1; transform:translateY(0);}
}
h3 {
  text-align: center;
  color: var(--pink-main);
  font-weight: 700;
  margin-bottom: 1.5rem;
}
.form-control {
  border: none;
  border-bottom: 2px solid #ffc0cb;
  border-radius: 0;
  background: transparent;
  padding-left: 0;
  color: #333;
  transition: all 0.3s;
}
.form-control:focus {
  border-color: var(--pink-main);
  box-shadow: none;
}
.btn-register {
  background-color: var(--pink-main);
  border: none;
  color: #fff;
  width: 100%;
  border-radius: 12px;
  font-weight: 600;
  transition: 0.3s;
}
.btn-register:hover {
  background-color: #e24a79;
}
a {
  color: var(--pink-main);
  text-decoration: none;
}
a:hover {
  text-decoration: underline;
}
</style>
</head>
<body>

<div class="card-register shadow-lg">
  <h3><i class="bi bi-person-plus-fill me-2"></i>Đăng ký tài khoản</h3>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Tên đăng nhập</label>
      <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập..." required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" placeholder="Nhập email...">
    </div>
    <div class="mb-3">
      <label class="form-label">Mật khẩu</label>
      <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required>
    </div>
    <button type="submit" class="btn btn-register mt-2 py-2">Tạo tài khoản</button>
  </form>
  <p class="text-center mt-3 mb-0">
    Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a> 💫
  </p>
</div>

</body>
</html>
