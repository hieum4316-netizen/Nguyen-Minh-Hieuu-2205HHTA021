<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: dashboard.php");
    exit;
}

// Kiểm tra công việc có tồn tại không
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id=? AND user_id=?");
$stmt->execute([$id, $user_id]);
$task = $stmt->fetch();

if (!$task) {
    echo "Không tìm thấy công việc!";
    exit;
}

// Nếu người dùng xác nhận xóa
if (isset($_POST['confirm_delete'])) {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
    $stmt->execute([$id, $user_id]);
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa công việc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffe6f2;
            font-family: "Segoe UI", sans-serif;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .card-header {
            background-color: #ffb6c1 !important;
            color: #fff;
            font-weight: 600;
        }
        .btn-danger {
            background-color: #ff6482;
            border: none;
        }
        .btn-danger:hover {
            background-color: #e24a79;
        }
        .btn-secondary {
            background-color: #ffb3c6;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #ff94ae;
        }
        .page-title {
            color: #d63384;
            font-weight: 700;
        }
    </style>
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <h3 class="text-center mb-4 page-title">💔 Xóa công việc</h3>
    <div class="card">
        <div class="card-header">Xác nhận xóa</div>
        <div class="card-body text-center">
            <p>Bạn có chắc chắn muốn xóa công việc <strong>"<?= htmlspecialchars($task['title']) ?>"</strong> không?</p>
            <form method="POST">
                <button type="submit" name="confirm_delete" class="btn btn-danger px-4">🗑️ Xóa</button>
                <a href="dashboard.php" class="btn btn-secondary px-4">Hủy</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
