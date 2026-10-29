-- 1. Lấy danh sách tất cả chuyên mục và số lượng bài viết tương ứng
SELECT c.id, c.name, c.slug, COUNT(p.id) AS total_posts
FROM categories c
LEFT JOIN posts p ON c.id = p.category_id
GROUP BY c.id, c.name, c.slug;

-- 2. Tìm danh sách các bài viết đang chờ duyệt (pending) kèm tên Tác giả
SELECT p.id, p.title, p.created_at, u.full_name AS author_name
FROM posts p
JOIN users u ON p.author_id = u.id
WHERE p.status = 'pending'
ORDER BY p.created_at ASC;

-- 3. Lấy danh sách bài viết do một Biên tập viên cụ thể duyệt
SELECT p.id, p.title, p.published_at, editor.full_name AS editor_name
FROM posts p
JOIN users editor ON p.editor_id = editor.id
WHERE p.status = 'published' AND editor.username = 'editor_van';