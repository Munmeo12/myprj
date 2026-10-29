-- ============================================================================
-- DỰ ÁN BÀI TẬP LỚN: WEBSITE TIN TỨC / BLOG CÓ TRANG QUẢN TRỊ
-- File: database/seed.sql
-- Mô tả: Chèn dữ liệu thử nghiệm (Seed Data) bằng tiếng Việt có dấu
-- ============================================================================

USE news_blog_db;

-- Xóa dữ liệu cũ trong các bảng trước khi nạp mới
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE comments;
TRUNCATE TABLE post_tags;
TRUNCATE TABLE tags;
TRUNCATE TABLE posts;
TRUNCATE TABLE categories;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Nạp dữ liệu Bảng Người dùng (users)
INSERT INTO users (id, username, email, password_hash, full_name, role, status) VALUES
(1, 'admin_haanh', 'haanh@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeF', 'Trần Thị Hà Anh', 'admin', 'active'),
(2, 'editor_van', 'van@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeF', 'Nguyễn Hải Vân', 'editor', 'active'),
(3, 'author_trung', 'trung@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeF', 'Đào Khánh Trung', 'author', 'active'),
(4, 'author_hoc', 'hoc@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeF', 'Nguyễn Trung Vũ Học', 'author', 'active'),
(5, 'reader_minhtri', 'minhtri@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeF', 'Phạm Minh Trí', 'reader', 'active');

-- 2. Nạp dữ liệu Bảng Chuyên mục (categories)
INSERT INTO categories (id, name, slug, description) VALUES
(1, 'Công nghệ', 'cong-nghe', 'Cập nhật xu hướng công nghệ, AI và thiết bị di động mới nhất'),
(2, 'Lập trình Web', 'lap-trinh-web', 'Chia sẻ kiến thức HTML, CSS, PHP, MySQL và Framework'),
(3, 'Đời sống Sinh viên', 'doi-song-sinh-vien', 'Góc chia sẻ kinh nghiệm học tập, làm bài tập lớn và hoạt động câu lạc bộ');

-- 3. Nạp dữ liệu Bảng Bài viết (posts)
INSERT INTO posts (id, category_id, author_id, editor_id, title, slug, summary, content, thumbnail, status, views, published_at) VALUES
(1, 2, 3, 2, 'Hướng dẫn kết nối PHP PDO với CSDL MySQL an toàn', 'huong-dan-ket-noi-php-pdo-voi-csdl-mysql-an-toan', 
 'Bài viết hướng dẫn cách sử dụng PDO Prepared Statement trong PHP để phòng chống tấn công SQL Injection hiệu quả.', 
 '<p>Trong lập trình web với PHP, việc kết nối cơ sở dữ liệu an toàn là vô cùng quan trọng. <b>PDO (PHP Data Objects)</b> cung cấp một giao diện nhất quán và an toàn để thao tác với MySQL...</p>', 
 'pdo_guide.jpg', 'published', 125, '2026-10-20 08:00:00'),

(2, 1, 4, 2, 'Top các ứng dụng Trí tuệ Nhân tạo nổi bật trong năm 2026', 'top-cac-ung-dung-tri-tue-nhan-tao-noi-bat-trong-nam-2026', 
 'Khám phá các công cụ AI đột phá hỗ trợ lập trình viên và nhà thiết kế tối ưu hóa hiệu suất công việc.', 
 '<p>Năm 2026 đánh dấu sự bùng nổ của các mô hình AI thế hệ mới. Việc ứng dụng AI vào tự động hóa quy trình phát triển phần mềm đang giúp tăng tốc độ làm dự án lên gấp nhiều lần...</p>', 
 'ai_2026.jpg', 'published', 89, '2026-10-22 10:30:00'),

(3, 2, 3, NULL, 'Thiết kế Hệ thống Cơ sở dữ liệu chuẩn hóa từ A-Z', 'thiet-ke-he-thong-co-so-du-lieu-chuan-hoa-tu-a-z', 
 'Tìm hiểu cách xác định thực thể, thuộc tính và xây dựng sơ đồ ERD cho dự án Bài tập lớn.', 
 '<p>Một hệ thống CSDL tốt sẽ giúp ứng dụng chạy mượt mà và dễ bảo trì. Trong bài viết này, chúng ta sẽ cùng phân tích quy trình chuẩn hóa CSDL...</p>', 
 'database_design.jpg', 'pending', 0, NULL),

(4, 3, 4, NULL, 'Kinh nghiệm làm Bài tập lớn môn Lập trình Web đạt điểm cao', 'kinh-nghiem-lam-bai-tap-lon-mon-lap-trinh-web-dat-diem-cao', 
 'Những lưu ý quan trọng về Git, phân chia công việc nhóm và bảo vệ bài tập lớn trước giảng viên.', 
 '<p>Làm bài tập lớn theo nhóm đòi hỏi sự phối hợp ăn ý giữa các thành viên. Việc phân chia nhánh Git rõ ràng và thiết kế CSDL chuẩn là chìa khóa giúp nhóm đạt kết quả tốt...</p>', 
 'btl_tips.jpg', 'draft', 0, NULL);

-- 4. Nạp dữ liệu Bảng Thẻ (tags)
INSERT INTO tags (id, name, slug) VALUES
(1, 'PHP', 'php'),
(2, 'MySQL', 'mysql'),
(3, 'AI', 'ai'),
(4, 'Bài tập lớn', 'bai-tap-lon');

-- 5. Nạp dữ liệu Bảng Trung gian Bài viết - Thẻ (post_tags)
INSERT INTO post_tags (post_id, tag_id) VALUES
(1, 1), -- Bài viết 1 gắn tag PHP
(1, 2), -- Bài viết 1 gắn tag MySQL
(2, 3), -- Bài viết 2 gắn tag AI
(3, 2), -- Bài viết 3 gắn tag MySQL
(3, 4); -- Bài viết 3 gắn tag Bài tập lớn

-- 6. Nạp dữ liệu Bảng Bình luận (comments)
INSERT INTO comments (id, post_id, user_id, author_name, author_email, content, status, created_at) VALUES
(1, 1, 5, 'Phạm Minh Trí', 'minhtri@gmail.com', 'Bài viết rất chi tiết và dễ hiểu, đúng phần mình đang vướng mắc!', 'approved', '2026-10-21 09:15:00'),
(2, 1, NULL, 'Độc giả ẩn danh', 'khach@gmail.com', 'Cho mình hỏi dùng PDO thì có kết nối được với SQL Server không ạ?', 'approved', '2026-10-21 14:30:00'),
(3, 1, NULL, 'Spammer', 'spam@gmail.com', '<script>alert("hack")</script> Quảng cáo dịch vụ...', 'pending', '2026-10-22 08:00:00'),
(4, 2, 5, 'Phạm Minh Trí', 'minhtri@gmail.com', 'Các ứng dụng AI năm nay đỉnh thật sự!', 'approved', '2026-10-23 11:00:00');