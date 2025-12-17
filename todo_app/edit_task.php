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

// Lấy công việc
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$task = $stmt->fetch();

if (!$task) {
    echo "Không tìm thấy công việc!";
    exit;
}

// Cập nhật công việc
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE tasks SET title=?, description=?, due_date=?, status=? WHERE id=? AND user_id=?");
    $stmt->execute([$title, $description, $due_date, $status, $id, $user_id]);
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa công việc</title>
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
            color: white;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #ff5c8d;
            border: none;
        }
        .btn-primary:hover {
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
<div class="container mt-5" style="max-width: 650px;">
    <h3 class="text-center mb-4 page-title">✨ Chỉnh sửa công việc ✨</h3>
    <div class="card">
        <div class="card-header">Cập nhật thông tin</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên công việc</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($task['title']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control"><?= htmlspecialchars($task['description']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ngày hết hạn</label>
                    <input type="date" name="due_date" class="form-control" value="<?= $task['due_date'] ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="pending" <?= $task['status'] === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                        <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>Đang làm</option>
                        <option value="completed" <?= $task['status'] === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="dashboard.php" class="btn btn-secondary">← Quay lại</a>
                    <button type="submit" class="btn btn-primary px-4">💾 Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
