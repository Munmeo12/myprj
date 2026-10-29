<?php
// File: detail.php
require_once 'database/db.php';
require_once 'repositories/CommentRepository.php';

$id = (int)($_GET['id'] ?? 0);

// Lấy thông tin bài viết
$stmt = db()->prepare("SELECT p.*, c.name AS category_name, u.full_name AS author_name 
                       FROM posts p 
                       JOIN categories c ON p.category_id = c.id 
                       JOIN users u ON p.author_id = u.id 
                       WHERE p.id = :id AND p.status = 'published'");
$stmt->execute(['id' => $id]);
$post = $stmt->fetch();

if (!$post) {
    die("Bài viết không tồn tại!");
}

// Xử lý gửi bình luận bằng POST[cite: 9, 17]
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    createComment($id, trim($_POST['name']), trim($_POST['email']), trim($_POST['content']));
    $msg = "Cảm ơn bạn! Bình luận đã được gửi và đang chờ Admin duyệt.";
}

// Lấy bình luận đã duyệt
$stmtCm = db()->prepare("SELECT * FROM comments WHERE post_id = :id AND status = 'approved' ORDER BY created_at DESC");
$stmtCm->execute(['id' => $id]);
$approvedComments = $stmtCm->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
</head>
<body>
    <a href="index.php">← Về Trang chủ</a>
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <p><b>Tác giả:</b> <?= htmlspecialchars($post['author_name']) ?> | <b>Chuyên mục:</b> <?= htmlspecialchars($post['category_name']) ?></p>
    <hr>
    <div><?= $post['content'] ?></div>
    <hr>

    <h3>💬 BÌNH LUẬN DỌC GIẢ</h3>
    <?php if ($msg): ?><p style="color: green;"><?= $msg ?></p><?php endif; ?>

    <form method="POST">
        Họ tên: <input type="text" name="name" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Nội dung: <br><textarea name="content" cols="40" rows="3" required></textarea><br><br>
        <button type="submit">Gửi bình luận</button>
    </form><br>

    <?php foreach ($approvedComments as $c): ?>
        <div style="background: #f4f4f4; padding: 5px; margin-bottom: 5px;">
            <b><?= htmlspecialchars($c['author_name']) ?>:</b> <?= htmlspecialchars($c['content']) ?>
        </div>
    <?php endforeach; ?>
</body>
</html>