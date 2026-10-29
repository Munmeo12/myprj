-- 1. Lấy danh sách 10 bài viết mới xuất bản nhất ra Trang chủ (có phân trang)
SELECT p.id, p.title, p.slug, p.summary, p.thumbnail, p.published_at, c.name AS category_name
FROM posts p
JOIN categories c ON p.category_id = c.id
WHERE p.status = 'published'
ORDER BY p.published_at DESC
LIMIT 10 OFFSET 0;

-- 2. Tìm kiếm bài viết đã xuất bản theo từ khóa tìm kiếm (Ví dụ từ khóa: 'PHP')
SELECT p.id, p.title, p.summary, c.name AS category_name
FROM posts p
JOIN categories c ON p.category_id = c.id
WHERE p.status = 'published' AND (p.title LIKE '%PHP%' OR p.content LIKE '%PHP%');

-- 3. Lấy thông tin bài viết có lượt xem (views) cao nhất
SELECT p.id, p.title, p.views, u.full_name AS author_name
FROM posts p
JOIN users u ON p.author_id = u.id
WHERE p.status = 'published'
ORDER BY p.views DESC
LIMIT 5;