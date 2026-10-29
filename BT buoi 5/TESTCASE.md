# BÁO CÁO TEST CASE CHỨC NĂNG CÁ NHÂN (QUẢN LÝ CHUYÊN MỤC)
**Họ và tên:** Trần Thị Hà Anh  
**Học phần:** Lập trình Web - Bài tập buổi 5  

---

### 1. Test Case 1: Thêm chuyên mục hợp lệ (ĐÚNG)
- **Đầu vào:** Tên: `Góc Sinh Viên`, Slug: `goc-sinh-vien`, Mô tả: `Tin tức hoạt động học đường`.
- **Thao tác:** Nhấn nút **Thêm Mới**.
- **Kết quả kỳ vọng:** Thêm thành công vào CSDL, xuất hiện thông báo thành công và hiển thị ở bảng danh sách.

### 2. Test Case 2: Để trống Tên chuyên mục (SAI)
- **Đầu vào:** Tên: *(để trống)*, Slug: `tin-hoat-dong`.
- **Thao tác:** Nhấn nút **Thêm Mới**.
- **Kết quả kỳ vọng:** Hệ thống báo lỗi "Vui lòng điền đầy đủ Tên và Slug chuyên mục!", ngăn không cho gửi form.

### 3. Test Case 3: Nhập trùng Slug đã có trong CSDL (SAI)
- **Đầu vào:** Tên: `Công Nghệ Mới`, Slug: `cong-nghe` *(slug đã tồn tại)*.
- **Thao tác:** Nhấn nút **Thêm Mới**.
- **Kết quả kỳ vọng:** Hệ thống bắt ngoại lệ PDOException do vi phạm ràng buộc UNIQUE, hiển thị thông báo trùng lặp mà không bị sập trang.

### 4. Test Case 4: Tìm kiếm theo từ khóa hợp lệ (ĐÚNG)
- **Đầu vào:** Ô tìm kiếm nhập `Công nghệ`.
- **Thao tác:** Nhấn nút **Tìm kiếm**.
- **Kết quả kỳ vọng:** Bảng dữ liệu lọc chính xác bài viết/chuyên mục có từ khóa "Công nghệ".

### 5. Test Case 5: Nhập tham số trang không hợp lệ (SAI/BIÊN)
- **Đầu vào:** Truy cập URL `index.php?page=9999` hoặc `index.php?page=-5`.
- **Thao tác:** Nhấn Enter.
- **Kết quả kỳ vọng:** Hàm xử lý tự động đưa về trang an toàn ($page = 1$), hiển thị giao diện bình thường không bị lỗi PHP/SQL.