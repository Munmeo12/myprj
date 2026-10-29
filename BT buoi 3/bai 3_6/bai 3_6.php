<?php
$message = "";
$uploadedImagePath = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["avatar"])) {
    $file = $_FILES["avatar"];

    // Cấu hình quy tắc kiểm tra
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
    $allowedMimeTypes  = ["image/jpeg", "image/png", "image/gif"];
    $maxFileSize       = 2 * 1024 * 1024; // Tối đa 2MB
    $targetDir         = "uploads/";

    // Tự động tạo thư mục uploads/ nếu chưa tồn tại
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName  = $file["name"];
    $fileTmp   = $file["tmp_name"];
    $fileSize  = $file["size"];
    $fileError = $file["error"];

    // Lấy đuôi file mở rộng
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // --- KIỂM TRA 1: Lỗi upload & Kích thước file ---
    if ($fileError !== UPLOAD_ERR_OK) {
        $message = "<div class='alert error'>Lỗi trong quá trình tải file lên server!</div>";
    } elseif ($fileSize > $maxFileSize) {
        $message = "<div class='alert error'>Lỗi: Kích thước file vượt quá dung lượng cho phép (Tối đa 2MB)!</div>";
    } 
    // --- KIỂM TRA 2: Đuôi file mở rộng & MIME type hợp lệ ---
    elseif (!in_array($fileExt, $allowedExtensions)) {
        $message = "<div class='alert error'>Lỗi: Chỉ chấp nhận các định dạng ảnh: .jpg, .jpeg, .png, .gif!</div>";
    } else {
        // Kiểm tra MIME Type thực tế từ nội dung file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileTmp);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimeTypes)) {
            $message = "<div class='alert error'>Lỗi: File không phải là ảnh hợp lệ (MIME Type không hợp lệ)!</div>";
        } else {
            // --- KIỂM TRA 3: Đổi tên file mới an toàn để tránh ghi đè & tên chứa ký tự nguy hiểm ---
            $newFileName = uniqid("avatar_", true) . "." . $fileExt;
            $targetFilePath = $targetDir . $newFileName;

            // Di chuyển file từ bộ nhớ tạm vào thư mục uploads/
            if (move_uploaded_file($fileTmp, $targetFilePath)) {
                $message = "<div class='alert success'>✓ Upload ảnh đại diện thành công!</div>";
                $uploadedImagePath = $targetFilePath;
            } else {
                $message = "<div class='alert error'>Lỗi: Không thể di chuyển và lưu file vào server!</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>3.6 Upload File An Toàn</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f6f9; color: #333; }
        .card { background: white; padding: 25px 30px; border-radius: 8px; max-width: 450px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin: 0 auto; }
        h2 { text-align: center; color: #007bff; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type="file"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #28a745; color: white; border: none; padding: 11px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; font-size: 16px; }
        button:hover { background: #218838; }
        .alert { padding: 12px; border-radius: 4px; margin-top: 15px; font-weight: 500; }
        .alert.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .preview { margin-top: 20px; text-align: center; }
        .preview img { max-width: 200px; height: auto; border-radius: 50%; border: 3px solid #007bff; margin-top: 10px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Upload Ảnh Đại Diện</h2>

    <!-- BẮT BUỘC có enctype="multipart/form-data" khi gửi file -->
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Chọn ảnh đại diện (Tối đa 2MB):</label>
            <input type="file" name="avatar" accept="image/*" required>
        </div>
        <button type="submit">Tải Ảnh Lên</button>
    </form>

    <?= $message ?>

    <?php if ($uploadedImagePath != ""): ?>
        <div class="preview">
            <p><strong>Ảnh đại diện vừa tải lên:</strong></p>
            <img src="<?= htmlspecialchars($uploadedImagePath) ?>" alt="Avatar">
        </div>
    <?php endif; ?>
</div>

</body>
</html>