# BÀI TẬP LỚN MÔN LẬP TRÌNH WEB - NHÓM 3
## ĐỀ TÀI: XÂY DỰNG WEBSITE TIN TỨC / BLOG CÓ TRANG QUẢN TRỊ (ADMIN PORTAL)

---

## 1. Danh sách thành viên và Phân công công việc

1. **Trần Thị Hà Anh** - MSSV: 224001713 (**Trưởng nhóm**)
   - **Phân công**: Quản lý tiến độ nhóm; Thiết kế và xây dựng Form Đăng ký Admin (`register.php`); Viết xử lý Server-side Validation; Mã hóa dữ liệu chống tấn công XSS và cài đặt chức năng Upload ảnh đại diện an toàn; Quản lý tài khoản, phân quyền và duyệt bình luận; Thiết kế cấu trúc CSDL và truy vấn SQL cá nhân.

2. **Nguyễn Hải Vân** - MSSV: 224001759
   - **Phân công**: Phân tích yêu cầu bài tập lớn; Thiết kế giao diện HTML/CSS, bố cục Layout Responsive cho Form và các trang quản trị; Quản lý Chuyên mục và thiết kế màn hình duyệt bài viết; Viết truy vấn SQL cá nhân.

3. **Đào Khánh Trung** - MSSV: 224001757
   - **Phân công**: Xây dựng sơ đồ Wireframe cho các màn hình/form chính; Tổng hợp bảng quy tắc Server-side Validation; Quản lý bài viết của Tác giả, xử lý Upload file bài viết và thẻ Tag; Viết tài liệu `README.md` dự án nhóm và truy vấn SQL cá nhân.

4. **Nguyễn Trung Vũ Học** - MSSV: 224001719
   - **Phân công**: Thiết kế cấu trúc thư mục dự án; Định nghĩa danh sách Route/Trang chức năng dự kiến; Xây dựng trang hiển thị tin tức, chi tiết bài viết và tìm kiếm; Hỗ trợ kiểm thử (Testing) kịch bản dữ liệu Form và viết truy vấn SQL cá nhân.

---

## 2. Danh sách Route / Trang chức năng dự kiến của Hệ thống

- `/index.php`: Trang chủ hiển thị danh sách tin tức/bài viết mới nhất.
- `/detail.php`: Trang chi tiết bài viết blog/tin tức.
- `/admin/login.php`: Trang đăng nhập dành cho Quản trị viên.
- `/admin/register.php`: Form đăng ký/tạo tài khoản Quản trị viên mới (Đã tích hợp Server-side Validation).
- `/admin/posts.php`: Màn hình quản lý danh sách bài viết tin tức.
- `/uploads/`: Thư mục hệ thống lưu trữ hình ảnh tải lên an toàn (Avatar Admin, Ảnh bài viết).

---

## 3. Bảng Quy tắc Validation phía Server (Server-side Validation Rules)

| Trường dữ liệu | Quy tắc Validation | Thông báo lỗi khi nhập sai |
| :--- | :--- | :--- |
| **Họ và tên** | Bắt buộc (`NotEmpty`), độ dài từ 3 đến 50 ký tự | "Họ và tên phải từ 3 đến 50 ký tự." |
| **Username** | Bắt buộc (`NotEmpty`), từ 4-20 ký tự, chỉ gồm chữ cái, số và `_` (`^a-zA-Z0-9_`) | "Tên đăng nhập từ 4-20 ký tự, không chứa ký tự đặc biệt." |
| **Email** | Bắt buộc (`NotEmpty`), chuẩn định dạng email (`FILTER_VALIDATE_EMAIL`) | "Định dạng Email không hợp lệ." |
| **Mật khẩu** | Bắt buộc (`NotEmpty`), độ dài tối thiểu 6 ký tự | "Mật khẩu phải có ít nhất 6 ký tự." |
| **Xác nhận Mật khẩu**| Khớp hoàn toàn với trường Mật khẩu | "Mật khẩu xác nhận không khớp." |
| **Ảnh đại diện Avatar**| Dung lượng ≤ 2 MB, đuôi mở rộng `.jpg, .jpeg, .png, .gif`, kiểm tra MIME Type `image/*` | "File tải lên không hợp lệ hoặc vượt quá 2MB." |

---

## 4. Các giải pháp Bảo mật đã tích hợp trong Form

1. **Chống tấn công XSS (Cross-Site Scripting):** Toàn bộ dữ liệu đầu ra được lọc và mã hóa bằng hàm `htmlspecialchars($str, ENT_QUOTES, 'UTF-8')`.
2. **Xử lý Upload File An Toàn:**
   - Kiểm tra lỗi Upload và dung lượng file (≤ 2 MB).
   - Kiểm tra cả Đuôi mở rộng lẫn MIME Type thực tế của file tải lên bằng `finfo_file()`.
   - Đổi tên file ngẫu nhiên tự động bằng `uniqid('admin_avatar_', true)` để chống ghi đè file và chặn tên file chứa mã độc.

---

## 5. Mô tả Cơ sở dữ liệu (Database Design - Bài 4)

### 5.1. Cấu trúc các Bảng CSDL (Database Schema)
Hệ thống Cơ sở dữ liệu được thiết kế trên MySQL engine `InnoDB`, hỗ trợ mã hóa tiếng Việt `utf8mb4_unicode_ci`, bao gồm **6 bảng cốt lõi** phục vụ quy trình quản trị tin tức/blog:

1. **`users`**: Lưu trữ thông tin tài khoản người dùng và phân quyền hệ thống (`admin`, `editor`, `author`, `reader`).
2. **`categories`**: Quản lý danh mục bài viết (Tên chuyên mục, `slug` duy nhất).
3. **`posts`**: Lưu nội dung bài viết, tác giả (`author_id`), người duyệt (`editor_id`) và luồng trạng thái bài viết (`draft` -> `pending` -> `published` / `rejected`).
4. **`tags`**: Quản lý các thẻ từ khóa gắn với bài viết.
5. **`post_tags`**: Bảng trung gian giải quyết quan hệ Nhiều - Nhiều ($N-N$) giữa Bài viết (`posts`) và Thẻ (`tags`).
6. **`comments`**: Quản lý bình luận của độc giả kèm trạng thái duyệt bình luận (`pending`, `approved`, `hidden`) để chống spam và chèn mã độc.

---

### 5.2. Sơ đồ Quan hệ Thực thể (ERD Diagram)
![Sơ đồ ERD Cơ sở dữ liệu](docs/erd.png)

*Mô tả mối quan hệ chính:*
- **`categories` (1) — (N) `posts`**: Một chuyên mục có thể chứa nhiều bài viết.
- **`users` (1) — (N) `posts`**: Một người dùng (Tác giả) tạo nhiều bài viết; một người dùng (Biên tập viên) duyệt nhiều bài viết.
- **`posts` (N) — (N) `tags`**: Quan hệ $N-N$ được nối qua bảng trung gian `post_tags`.
- **`posts` (1) — (N) `comments`**: Một bài viết có nhiều bình luận phản hồi từ độc giả.

---

### 5.3. Hướng dẫn khởi tạo và nạp CSDL (Running Database Scripts)

1. Mở công cụ quản trị CSDL (**DBeaver** hoặc **phpMyAdmin**).
2. Tạo một Database mới có tên: **`news_blog_db`** (Chọn Bảng mã / Collation: