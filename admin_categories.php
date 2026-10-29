<?php
// Bật hiển thị lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Nạp Repository
require_once 'repositories/CategoryRepository.php';

$message = '';
$editCategory = null;

// Xử lý Xóa chuyên mục
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if (deleteCategory((int)$_GET['id'])) {
        header('Location: admin_categories.php?msg=deleted');
        exit;
    }
}

// Lấy thông tin Chuyên mục để Sửa
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $editCategory = findCategoryById((int)$_GET['id']);
}

// Xử lý Thêm / Cập nhật chuyên mục
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $desc = trim($_POST['description'] ?? '');

    if (!empty($name) && !empty($slug)) {
        if (saveCategory($name, $slug, $desc, $id)) {
            header('Location: admin_categories.php?msg=saved');
            exit;
        }
    } else {
        $message = 'Vui lòng điền đầy đủ Tên và Slug chuyên mục!';
    }
}

$categories = findAllCategories();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Chuyên mục - BTL Nhóm 3</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --danger: #ef4444;
            --success: #10b981;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: var(--bg); color: var(--text-main); padding: 30px 15px; line-height: 1.5; }
        .container { max-width: 1000px; margin: 0 auto; }
        
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .btn-back { text-decoration: none; color: var(--text-muted); font-weight: 600; display: inline-flex; align-items: center; gap: 5px; transition: color 0.2s; }
        .btn-back:hover { color: var(--primary); }
        h1 { font-size: 1.75rem; color: var(--primary); font-weight: 700; }

        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 0.95rem; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }

        .grid-layout { display: grid; grid-template-columns: 320px 1fr; gap: 25px; }
        @media (max-width: 768px) { .grid-layout { grid-template-columns: 1fr; } }

        .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .card-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 15px; color: var(--text-main); border-bottom: 2px solid var(--bg); padding-bottom: 8px; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 0.95rem; outline: none; transition: border 0.2s; }
        .form-control:focus { border-color: var(--primary); }
        textarea.form-control { resize: vertical; min-height: 80px; }

        .btn { display: inline-block; width: 100%; background: var(--primary); color: white; border: none; padding: 10px 16px; border-radius: 8px; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; transition: background 0.2s; }
        .btn:hover { background: var(--primary-hover); }
        .btn-cancel { background: #e2e8f0; color: var(--text-main); margin-top: 8px; }
        .btn-cancel:hover { background: #cbd5e1; }

        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem; }
        th, td { padding: 12px 16px; border-bottom: 1px solid var(--border); }
        th { background: #f1f5f9; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
        tr:hover { background: #f8fafc; }
        
        .action-links { display: flex; gap: 10px; }
        .action-links a { text-decoration: none; font-weight: 600; font-size: 0.85rem; }
        .link-edit { color: var(--primary); }
        .link-delete { color: var(--danger); }
        .link-edit:hover, .link-delete:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-bar">
            <h1>📁 QUẢN LÝ CHUYÊN MỤC</h1>
            <a href="index.php" class="btn-back">← Quay lại Trang chủ</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'saved'): ?>
            <div class="alert alert-success">Lưu chuyên mục thành công!</div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-success">Đã xóa chuyên mục thành công!</div>
        <?php endif; ?>

        <div class="grid-layout">
            <!-- Form Thêm / Sửa -->
            <div class="card">
                <h2 class="card-title"><?= $editCategory ? '✏️ Chỉnh Sửa Chuyên Mục' : '➕ Thêm Chuyên Mục Mới' ?></h2>
                <form method="POST" action="admin_categories.php">
                    <?php if ($editCategory): ?>
                        <input type="hidden" name="id" value="<?= $editCategory['id'] ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Tên Chuyên Mục</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editCategory['name'] ?? '') ?>" placeholder="Ví dụ: Công nghệ" required>
                    </div>

                    <div class="form-group">
                        <label>Slug (Đường dẫn tĩnh)</label>
                        <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($editCategory['slug'] ?? '') ?>" placeholder="cong-nghe" required>
                    </div>

                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control" placeholder="Mô tả ngắn về chuyên mục..."><?= htmlspecialchars($editCategory['description'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn"><?= $editCategory ? 'Cập Nhật' : 'Thêm Mới' ?></button>
                    <?php if ($editCategory): ?>
                        <a href="admin_categories.php" class="btn btn-cancel">Hủy bỏ</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Bảng danh sách -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên chuyên mục</th>
                            <th>Slug</th>
                            <th>Mô tả</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 25px;">Chưa có chuyên mục nào.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><strong>#<?= $cat['id'] ?></strong></td>
                                    <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                                    <td><code><?= htmlspecialchars($cat['slug']) ?></code></td>
                                    <td><?= htmlspecialchars($cat['description'] ?? '---') ?></td>
                                    <td>
                                        <div class="action-links">
                                            <a href="admin_categories.php?action=edit&id=<?= $cat['id'] ?>" class="link-edit">Sửa</a>
                                            <a href="admin_categories.php?action=delete&id=<?= $cat['id'] ?>" class="link-delete" onclick="return confirm('Bạn có chắc muốn xóa chuyên mục này?')">Xóa</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>