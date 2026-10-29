<?php
// File: admin_comments.php
require_once 'repositories/CommentRepository.php';

// Xử lý Duyệt / Ẩn bình luận
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = (int)$_GET['id'];
    
    if ($action === 'approve') {
        updateCommentStatus($id, 'approved');
        header('Location: admin_comments.php');
        exit;
    } elseif ($action === 'hide') {
        updateCommentStatus($id, 'hidden');
        header('Location: admin_comments.php');
        exit;
    } elseif ($action === 'delete') {
        deleteComment($id);
        header('Location: admin_comments.php');
        exit;
    }
}

$comments = findAllComments();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Bình luận - BTL Nhóm 3</title>
    <style>
        :root {
            --primary: #4f46e5;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: var(--bg); color: var(--text-main); padding: 30px 15px; line-height: 1.5; }
        .container { max-width: 1050px; margin: 0 auto; }
        
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .btn-back { text-decoration: none; color: var(--text-muted); font-weight: 600; display: inline-flex; align-items: center; gap: 5px; transition: color 0.2s; }
        .btn-back:hover { color: var(--primary); }
        h1 { font-size: 1.75rem; color: var(--primary); font-weight: 700; }

        .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }

        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem; }
        th, td { padding: 14px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        th { background: #f1f5f9; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
        tr:hover { background: #f8fafc; }
        
        .author-info { font-weight: 600; color: var(--text-main); }
        .author-email { font-size: 0.8rem; color: var(--text-muted); font-weight: normal; }
        .post-title { font-size: 0.85rem; color: var(--text-muted); max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-hidden { background: #fee2e2; color: #991b1b; }

        .action-btns { display: flex; gap: 8px; flex-wrap: wrap; }
        .btn-act { text-decoration: none; padding: 5px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; transition: all 0.2s; }
        .btn-approve { background: #ecfdf5; color: var(--success); border: 1px solid #a7f3d0; }
        .btn-approve:hover { background: var(--success); color: white; }
        .btn-hide { background: #fffbe6; color: var(--warning); border: 1px solid #ffe58f; }
        .btn-hide:hover { background: var(--warning); color: white; }
        .btn-del { background: #fff1f0; color: var(--danger); border: 1px solid #ffa39e; }
        .btn-del:hover { background: var(--danger); color: white; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-bar">
            <h1>💬 QUẢN LÝ BÌNH LUẬN ĐỘC GIẢ</h1>
            <a href="index.php" class="btn-back">← Quay lại Trang chủ</a>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Độc giả</th>
                        <th>Nội dung bình luận</th>
                        <th>Bài viết</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($comments)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">Chưa có bình luận nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($comments as $c): ?>
                            <tr>
                                <td><strong>#<?= $c['id'] ?></strong></td>
                                <td>
                                    <div class="author-info"><?= htmlspecialchars($c['author_name']) ?></div>
                                    <div class="author-email"><?= htmlspecialchars($c['author_email']) ?></div>
                                </td>
                                <td style="max-width: 250px;"><?= htmlspecialchars($c['content']) ?></td>
                                <td>
                                    <div class="post-title" title="<?= htmlspecialchars($c['post_title'] ?? '') ?>">
                                        <?= htmlspecialchars($c['post_title'] ?? 'Bài viết không tồn tại') ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($c['status'] === 'approved'): ?>
                                        <span class="status-badge status-approved">Đã duyệt</span>
                                    <?php elseif ($c['status'] === 'pending'): ?>
                                        <span class="status-badge status-pending">Chờ duyệt</span>
                                    <?php else: ?>
                                        <span class="status-badge status-hidden">Đã ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <?php if ($c['status'] !== 'approved'): ?>
                                            <a href="admin_comments.php?action=approve&id=<?= $c['id'] ?>" class="btn-act btn-approve">Duyệt</a>
                                        <?php endif; ?>
                                        <?php if ($c['status'] !== 'hidden'): ?>
                                            <a href="admin_comments.php?action=hide&id=<?= $c['id'] ?>" class="btn-act btn-hide">Ẩn</a>
                                        <?php endif; ?>
                                        <a href="admin_comments.php?action=delete&id=<?= $c['id'] ?>" class="btn-act btn-del" onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')">Xóa</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>