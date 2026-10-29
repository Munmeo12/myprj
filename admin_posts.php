<?php
// File: admin_posts.php
require_once 'repositories/PostRepository.php';
require_once 'repositories/CategoryRepository.php';

// Lấy danh sách chuyên mục cho dropdown
$categories = findAllCategories();

// Xử lý xóa bài viết
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    deletePost((int)$_GET['id']);
    header('Location: admin_posts.php');
    exit;
}

// Lấy danh sách tất cả bài viết
$posts = findAllPosts();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Bài viết - BTL Nhóm 3</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; color: #1e293b; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        h1 { color: #4f46e5; margin-bottom: 15px; }
        a.btn-back { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #64748b; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px 15px; border: 1px solid #e2e8f0; text-align: left; }
        th { background: #f1f5f9; color: #334155; }
        .badge { background: #e0e7ff; color: #4f46e5; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: bold; }
        .btn-delete { color: #ef4444; text-decoration: none; font-weight: 600; }
        .btn-delete:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn-back">← Quay lại Trang chủ</a>
        <h1>📝 QUẢN LÝ BÀI VIẾT</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Chuyên mục</th>
                    <th>Trạng thái</th>
                    <th>Ngày đăng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8;">Chưa có bài viết nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($posts as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
                            <td><span class="badge"><?= htmlspecialchars($p['category_name'] ?? 'Chưa phân loại') ?></span></td>
                            <td><?= htmlspecialchars($p['status']) ?></td>
                            <td><?= htmlspecialchars($p['created_at']) ?></td>
                            <td>
                                <a href="admin_posts.php?action=delete&id=<?= $p['id'] ?>" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>