<?php
// Mảng lưu thông báo lỗi và dữ liệu người dùng nhập
$errors = [];
$data = [
    'name'    => '',
    'email'   => '',
    'subject' => 'Hỗ trợ kỹ thuật',
    'message' => ''
];
$success = false;
$uploadedImagePath = '';

// Hàm mã hóa an toàn chống XSS
function e($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

// Xử lý khi Submit Form (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['name']    = trim($_POST['name'] ?? '');
    $data['email']   = trim($_POST['email'] ?? '');
    $data['subject'] = trim($_POST['subject'] ?? 'Hỗ trợ kỹ thuật');
    $data['message'] = trim($_POST['message'] ?? '');

    // 2. Kiểm tra họ tên không rỗng[cite: 10]
    if ($data['name'] === '') {
        $errors['name'] = 'Vui lòng nhập họ tên.';
    }

    // 3. Kiểm tra email đúng định dạng[cite: 10]
    if ($data['email'] === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không đúng định dạng.';
    }

    // 2. Kiểm tra nội dung không rỗng[cite: 10]
    if ($data['message'] === '') {
        $errors['message'] = 'Vui lòng nhập nội dung liên hệ.';
    } elseif (mb_strlen($data['message']) < 10) {
        $errors['message'] = 'Nội dung phải từ 10 ký tự trở lên.';
    }

    // 1. Kiểm tra Upload ảnh đại diện (áp dụng đủ 3 kiểm tra bắt buộc)[cite: 10]
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['avatar'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $allowedMimeTypes  = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize       = 2 * 1024 * 1024; // 2MB
        $targetDir         = 'uploads/';

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Kiểm tra 1: Lỗi upload & Dung lượng
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['avatar'] = 'Lỗi trong quá trình upload ảnh.';
        } elseif ($file['size'] > $maxFileSize) {
            $errors['avatar'] = 'Kích thước ảnh vượt quá 2MB.';
        }
        // Kiểm tra 2: Đuôi file & MIME type
        elseif (!in_array($fileExt, $allowedExtensions)) {
            $errors['avatar'] = 'Chỉ chấp nhận file ảnh JPG, JPEG, PNG, GIF.';
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowedMimeTypes)) {
                $errors['avatar'] = 'File tải lên không phải là ảnh hợp lệ.';
            } else {
                // Kiểm tra 3: Đổi tên file an toàn
                $newFileName = uniqid('avatar_', true) . '.' . $fileExt;
                $targetFilePath = $targetDir . $newFileName;

                if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                    $uploadedImagePath = $targetFilePath;
                } else {
                    $errors['avatar'] = 'Không thể lưu file ảnh tải lên.';
                }
            }
        }
    } else {
        $errors['avatar'] = 'Vui lòng chọn ảnh đại diện.';
    }

    // 5. Hiển thị thông báo thành công nếu hợp lệ[cite: 10]
    if (empty($errors)) {
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>3.8 Thực hành trên lớp: Form liên hệ</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f6f9; color: #333; }
        .card { background: white; padding: 30px; border-radius: 12px; max-width: 520px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); margin: 0 auto; }
        h2 { text-align: center; color: #1a5bb8; margin-bottom: 5px; font-size: 26px; }
        .subtitle { text-align: center; color: #666; font-size: 14px; margin-bottom: 25px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; font-size: 14px; }
        input[type="text"], input[type="email"], select, textarea, input[type="file"] { 
            width: 100%; padding: 10px 12px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; 
        }
        textarea { resize: vertical; height: 100px; }
        .error { color: #dc3545; font-size: 13px; margin-top: 5px; display: block; }
        button { background: #1a73e8; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%; font-size: 16px; margin-top: 10px; }
        button:hover { background: #1557b0; }
        .success-box { background: #e6f4ea; color: #137333; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ceead6; }
        .avatar-preview { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #1a73e8; margin-top: 10px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Liên hệ</h2>
    <div class="subtitle">Vui lòng nhập đầy đủ thông tin bên dưới.</div>

    <!-- 5. Hiển thị thông báo thành công nếu hợp lệ[cite: 10] -->
    <?php if ($success): ?>
        <div class="success-box">
            <h3 style="margin-top:0;">✓ Gửi liên hệ thành công!</h3>
            <p><strong>Họ tên:</strong> <?= e($data['name']) ?></p>
            <p><strong>Email:</strong> <?= e($data['email']) ?></p>
            <p><strong>Chủ đề:</strong> <?= e($data['subject']) ?></p>
            <p><strong>Nội dung:</strong> <?= nl2br(e($data['message'])) ?></p>
            <?php if ($uploadedImagePath): ?>
                <p><strong>Ảnh đại diện:</strong></p>
                <img src="<?= e($uploadedImagePath) ?>" alt="Avatar" class="avatar-preview">
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" novalidate>
        <!-- Họ tên (4. Giữ lại dữ liệu nhập)[cite: 10] -->
        <div class="form-group">
            <label>Họ tên</label>
            <input type="text" name="name" value="<?= e($data['name']) ?>" placeholder="Lê Văn Hảo">
            <?php if (isset($errors['name'])): ?>
                <span class="error"><?= e($errors['name']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= e($data['email']) ?>" placeholder="haovanle@edu.vn">
            <?php if (isset($errors['email'])): ?>
                <span class="error"><?= e($errors['email']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Chủ đề -->
        <div class="form-group">
            <label>Chủ đề</label>
            <select name="subject">
                <option value="Hỗ trợ kỹ thuật" <?= $data['subject'] === 'Hỗ trợ kỹ thuật' ? 'selected' : '' ?>>Hỗ trợ kỹ thuật</option>
                <option value="Góp ý dịch vụ" <?= $data['subject'] === 'Góp ý dịch vụ' ? 'selected' : '' ?>>Góp ý dịch vụ</option>
                <option value="Hợp tác kinh doanh" <?= $data['subject'] === 'Hợp tác kinh doanh' ? 'selected' : '' ?>>Hợp tác kinh doanh</option>
            </select>
        </div>

        <!-- Nội dung -->
        <div class="form-group">
            <label>Nội dung</label>
            <textarea name="message" placeholder="Nhập nội dung liên hệ..."><?= e($data['message']) ?></textarea>
            <?php if (isset($errors['message'])): ?>
                <span class="error"><?= e($errors['message']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Upload ảnh đại diện (Có 3 kiểm tra bắt buộc)[cite: 10] -->
        <div class="form-group">
            <label>Ảnh đại diện (Tối đa 2MB)</label>
            <input type="file" name="avatar" accept="image/*">
            <?php if (isset($errors['avatar'])): ?>
                <span class="error"><?= e($errors['avatar']) ?></span>
            <?php endif; ?>
        </div>

        <button type="submit">Gửi liên hệ</button>
    </form>
</div>

</body>
</html>