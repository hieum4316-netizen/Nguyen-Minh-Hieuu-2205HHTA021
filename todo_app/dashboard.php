<?php
require 'db.php';
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Thêm công việc
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $title = $_POST['title'];
    $description = $_POST['description'] ?? null;
    $due_date = $_POST['due_date'] ?? null;
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, due_date, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$user_id, $title, $description, $due_date]);
    header("Location: dashboard.php");
    exit;
}

// Cập nhật trạng thái
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $task_id = $_POST['task_id'];
    $new_status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$new_status, $task_id, $user_id]);
    echo "OK";
    exit;
}

// Lấy danh sách công việc
$query = "SELECT * FROM tasks WHERE user_id = ? ORDER BY due_date ASC";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý cá nhân</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --pink-main: #ff5c8d;
  --pink-light: #fff3f7;
  --pink-accent: #ffd6e8;
}
body {
  background: var(--pink-light);
  font-family: "Inter", sans-serif;
  color: #333;
  min-height: 100vh;
}
.navbar-custom {
  background: white;
  border-radius: 16px;
  box-shadow: 0 3px 12px rgba(255, 182, 193, 0.3);
  padding: 1rem 1.5rem;
  margin-bottom: 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.navbar-custom h2 {
  font-weight: 700;
  color: var(--pink-main);
  margin: 0;
}
.btn-pink {
  background-color: var(--pink-main);
  color: #fff;
  border: none;
  border-radius: 10px;
  transition: 0.3s;
}
.btn-pink:hover {
  background-color: #e24a79;
}
.input-modern {
  border: none;
  border-bottom: 2px solid #ffc0cb;
  background: transparent;
  border-radius: 0;
  outline: none;
  transition: 0.3s;
}
.input-modern:focus {
  border-color: var(--pink-main);
  box-shadow: none;
}
.task-card {
  background: white;
  border: none;
  border-radius: 18px;
  box-shadow: 0 4px 12px rgba(255, 182, 193, 0.3);
  padding: 20px 24px;
  margin-bottom: 15px;
  transition: 0.25s;
}
.task-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(255, 182, 193, 0.4);
}
.status-select {
  border: none;
  background: #fff0f6;
  border-radius: 10px;
  padding: 5px 10px;
  color: var(--pink-main);
  font-weight: 500;
  cursor: pointer;
}
.status-select:focus {
  outline: 2px solid #ffd6e8;
}
.fade-in {
  animation: fadeIn 0.7s ease;
}
@keyframes fadeIn {
  from {opacity:0;transform:translateY(8px);}
  to {opacity:1;transform:translateY(0);}
}
.task-updated {
  animation: highlight 1s ease-out;
}
@keyframes highlight {
  0% {background-color: #ffe5ef;}
  100% {background-color: white;}
}
small.text-muted i {
  color: var(--pink-main);
}
</style>
</head>
<body class="fade-in">

<div class="container py-4">
  <div class="navbar-custom">
    <h2><i class="bi bi-person-heart me-2"></i>Quản lý cá nhân</h2>
    <a href="logout.php" class="btn btn-outline-danger"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
  </div>

  <!-- Thêm công việc -->
  <div class="card shadow-sm border-0 mb-4 p-4" style="border-radius:16px;">
    <form method="POST" class="row g-3 align-items-center">
      <div class="col-md-4">
        <input type="text" name="title" class="form-control input-modern" placeholder="Tên công việc..." required>
      </div>
      <div class="col-md-3">
        <input type="date" name="due_date" class="form-control input-modern">
      </div>
      <div class="col-md-5 d-flex">
        <input type="text" name="description" class="form-control input-modern me-2" placeholder="Mô tả...">
        <button type="submit" name="add_task" class="btn btn-pink px-4"><i class="bi bi-plus-lg"></i></button>
      </div>
    </form>
  </div>

  <!-- Danh sách công việc -->
  <?php if (count($tasks) > 0): ?>
    <?php foreach ($tasks as $task): ?>
      <div class="task-card d-flex justify-content-between align-items-center" id="task-<?= $task['id'] ?>">
        <div>
          <h6 class="fw-semibold mb-1"><?= htmlspecialchars($task['title']) ?></h6>
          <small class="text-muted d-block"><?= htmlspecialchars($task['description']) ?></small>
          <small class="text-muted"><i class="bi bi-calendar2-heart"></i> <?= htmlspecialchars($task['due_date']) ?></small>
        </div>
        <div class="d-flex align-items-center">
          <form method="POST" class="me-3 status-form">
            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
            <input type="hidden" name="update_status" value="1">
            <select name="status" class="status-select">
              <option value="pending" <?= $task['status']=='pending'?'selected':'' ?>>Chờ xử lý</option>
              <option value="in_progress" <?= $task['status']=='in_progress'?'selected':'' ?>>Đang làm</option>
              <option value="completed" <?= $task['status']=='completed'?'selected':'' ?>>Hoàn thành</option>
            </select>
          </form>
          <a href="edit_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-pencil"></i></a>
          <a href="delete_task.php?id=<?= $task['id'] ?>" onclick="return confirm('Xóa công việc này?')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="text-center text-muted py-5">🌸 Chưa có công việc nào 🌸</div>
  <?php endif; ?>
</div>

<script>
document.querySelectorAll('.status-select').forEach(sel => {
  sel.addEventListener('change', e => {
    const form = sel.closest('.status-form');
    const data = new FormData(form);
    const card = sel.closest('.task-card');
    fetch('dashboard.php', { method: 'POST', body: data })
      .then(res => res.text())
      .then(() => {
        card.classList.add('task-updated');
        setTimeout(() => card.classList.remove('task-updated'), 1000);
      })
      .catch(err => console.error(err));
  });
});
</script>

</body>
</html>
