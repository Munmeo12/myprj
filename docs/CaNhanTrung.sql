-- 1. Lấy tất cả bài viết (bao gồm cả bản nháp) của chính Tác giả đó
SELECT p.id, p.title, p.status, c.name AS category_name, p.created_at
FROM posts p
JOIN categories c ON p.category_id = c.id
JOIN users u ON p.author_id = u.id
WHERE u.username = 'author_trung'
ORDER BY p.created_at DESC;

-- 2. Lấy danh sách các thẻ (tags) đính kèm của một bài viết cụ thể
SELECT p.title, t.name AS tag_name, t.slug AS tag_slug
FROM posts p
JOIN post_tags pt ON p.id = pt.post_id
JOIN tags t ON pt.tag_id = t.id
WHERE p.id = 1;

-- 3. Thống kê số lượng bài viết được gắn theo từng Thẻ (Tag)
SELECT t.name AS tag_name, COUNT(pt.post_id) AS total_posts
FROM tags t
LEFT JOIN post_tags pt ON t.id = pt.tag_id
GROUP BY t.id, t.name
ORDER BY total_posts DESC;