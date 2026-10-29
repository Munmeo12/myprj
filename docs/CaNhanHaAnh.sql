-- 1. Tìm tất cả tài khoản Admin hoặc Editor đang hoạt động
SELECT id, username, email, full_name, role 
FROM users 
WHERE role IN ('admin', 'editor') AND status = 'active';

-- 2. Lấy danh sách bình luận đang chờ duyệt (pending) kèm tiêu đề bài viết
SELECT c.id, c.author_name, c.content, c.created_at, p.title AS post_title
FROM comments c
JOIN posts p ON c.post_id = p.id
WHERE c.status = 'pending'
ORDER BY c.created_at DESC;

-- 3. Thống kê số bình luận đã được duyệt theo từng bài viết
SELECT p.id, p.title, COUNT(c.id) AS total_approved_comments
FROM posts p
LEFT JOIN comments c ON p.id = c.post_id AND c.status = 'approved'
GROUP BY p.id, p.title
ORDER BY total_approved_comments DESC;