<?php
// File: index.php
require_once 'database/db.php';

// Phân trang an toàn
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 6;
$offset = ($page - 1) * $perPage;

$keyword = trim($_GET['kw'] ?? '');
$searchParam = '%' . $keyword . '%';

// Lấy danh sách bài viết
$sql = "SELECT p.*, c.name AS category_name 
        FROM posts p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'published' AND (p.title LIKE :kw1 OR p.summary LIKE :kw2)
        ORDER BY p.published_at DESC 
        LIMIT :limit OFFSET :offset";

$stmt = db()->prepare($sql);
$stmt->bindValue(':kw1', $searchParam, PDO::PARAM_STR);
$stmt->bindValue(':kw2', $searchParam, PDO::PARAM_STR);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

// Tính tổng trang
$stmtCount = db()->prepare("SELECT COUNT(*) FROM posts WHERE status = 'published' AND (title LIKE :kw1 OR summary LIKE :kw2)");
$stmtCount->bindValue(':kw1', $searchParam, PDO::PARAM_STR);
$stmtCount->bindValue(':kw2', $searchParam, PDO::PARAM_STR);
$stmtCount->execute();
$totalPosts = $stmtCount->fetchColumn();
$totalPages = ceil($totalPosts / $perPage);

// Hàm kiểm tra đường dẫn file an toàn
function getSafeLink($path, $fallback = '#') {
    return file_exists($path) ? $path : $fallback;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ Tin Tức - BTL Nhóm 3</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: var(--bg); color: var(--text-main); line-height: 1.6; padding-bottom: 50px; }
        
        header { background: #ffffff; border-bottom: 1px solid var(--border); padding: 1.5rem 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .header-container { max-width: 1100px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .logo { font-size: 1.5rem; font-weight: 700; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; }
        
        nav { display: flex; gap: 1rem; }
        nav a { text-decoration: none; color: var(--text-muted); font-weight: 600; font-size: 0.95rem; padding: 0.5rem 1rem; border-radius: 8px; transition: all 0.2s; }
        nav a:hover { color: var(--primary); background: #f1f5f9; }
        
        .main-container { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }
        
        .search-section { margin-bottom: 2rem; display: flex; justify-content: center; }
        .search-form { display: flex; gap: 0.5rem; width: 100%; max-width: 500px; }
        .search-input { flex: 1; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 8px; font-size: 1rem; outline: none; transition: border 0.2s; }
        .search-input:focus { border-color: var(--primary); }
        .search-btn { background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .search-btn:hover { background: var(--primary-hover); }

        .posts-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; }
        .post-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: transform 0.2s, box-shadow 0.2s; display: flex; flex-direction: column; }
        .post-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        .post-content { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
        .category-badge { display: inline-block; background: #e0e7ff; color: var(--primary); font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 20px; text-transform: uppercase; margin-bottom: 0.75rem; width: fit-content; }
        .post-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem; line-height: 1.4; }
        .post-title a { color: var(--text-main); text-decoration: none; transition: color 0.2s; }
        .post-title a:hover { color: var(--primary); }
        .post-summary { color: var(--text-muted); font-size: 0.95rem; flex: 1; margin-bottom: 1rem; }
        
        .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 2.5rem; }
        .pagination a { padding: 0.5rem 1rem; border: 1px solid var(--border); background: white; color: var(--text-main); text-decoration: none; border-radius: 6px; font-weight: 600; transition: all 0.2s; }
        .pagination a.active, .pagination a:hover { background: var(--primary); color: white; border-color: var(--primary); }
    </style>
</head>
<body>

    <header>
        <div class="header-container">
            <a href="index.php" class="logo">🌐 BTL NHÓM 3 BLOG</a>
            <nav>
                <a href="<?= getSafeLink('admin/posts.php', getSafeLink('admin_posts.php')) ?>">📝 Bài viết</a>
                <a href="<?= getSafeLink('admin/categories.php', getSafeLink('admin_categories.php')) ?>">📁 Chuyên mục</a>
                <a href="<?= getSafeLink('admin/comments.php', getSafeLink('admin_comments.php')) ?>">💬 Bình luận</a>
            </nav>
        </div>
    </header>

    <div class="main-container">
        <div class="search-section">
            <form method="GET" class="search-form">
                <input type="text" name="kw" class="search-input" value="<?= htmlspecialchars($keyword) ?>" placeholder="Tìm kiếm bài viết...">
                <button type="submit" class="search-btn">Tìm kiếm</button>
            </form>
        </div>

        <?php if (empty($posts)): ?>
            <p style="text-align: center; color: var(--text-muted); padding: 3rem 0;">Không tìm thấy bài viết nào.</p>
        <?php else: ?>
            <div class="posts-grid">
                <?php foreach ($posts as $p): ?>
                    <article class="post-card">
                        <div class="post-content">
                            <span class="category-badge"><?= htmlspecialchars($p['category_name']) ?></span>
                            <h2 class="post-title">
                                <a href="<?= getSafeLink('detail.php?id='.$p['id'], getSafeLink('post.php?id='.$p['id'])) ?>"><?= htmlspecialchars($p['title']) ?></a>
                            </h2>
                            <p class="post-summary"><?= htmlspecialchars($p['summary']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="index.php?page=<?= $i ?>&kw=<?= urlencode($keyword) ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>