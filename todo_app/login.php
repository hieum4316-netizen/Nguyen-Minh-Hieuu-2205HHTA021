<?php
require 'db.php';
session_start();

// Nếu người dùng đã đăng nhập thì chuyển về dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - To-Do App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffe6f2;
            font-family: "Segoe UI", sans-serif;
        }
        .login-card {
            max-width: 420px;
            margin: 80px auto;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .login-header {
            background-color: #ffb6c1;
            color: #fff;
            text-align: center;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            padding: 20px;
            font-weight: 600;
        }
        .btn-login {
            background-color: #ff5c8d;
            border: none;
            width: 100%;
        }
        .btn-login:hover {
            background-color: #e24a79;
        }
        .form-control:focus {
            border-color: #ff85a1;
            box-shadow: 0 0 0 0.2rem rgba(255,133,161,0.25);
        }
        .register-link {
            color: #d63384;
            text-decoration: none;
        }
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            💖 Đăng nhập To-Do App
        </div>
        <div class="card-body p-4">
            <?php if ($error): ?>
                <div class="alert alert-danger text-center"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required>
                </div>
                <button type="submit" class="btn btn-login text-white py-2 fw-bold">Đăng nhập</button>
            </form>
            <p class="text-center mt-3">
                Chưa có tài khoản? <a href="register.php" class="register-link">Đăng ký ngay</a> 💫
            </p>
        </div>
    </div>
</body>
</html>
