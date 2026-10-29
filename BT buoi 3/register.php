<?php
// Khai báo mảng lưu dữ liệu nhập và mảng lỗi
$errors = [];
$data = [
    'fullname' => '',
    'username' => '',
    'email'    => '',
    'role'     => 'Editor',
    'bio'      => ''
];
$success = false;
$uploadedAvatar = '';

// Hàm mã hóa an toàn chống tấn công XSS
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Chuẩn hóa dữ liệu đầu vào (Trim)
    $data['fullname'] = trim($_POST['fullname'] ?? '');
    $data['username'] = trim($_POST['username'] ?? '');
    $data['email']    = trim($_POST['email'] ?? '');
    $data['role']     = trim($_POST['role'] ?? 'Editor');
    $data['bio']      = trim($_POST['bio'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 2. Validate Họ và tên (3 - 50 ký tự)
    if (empty($data['fullname'])) {
        $errors['fullname'] = 'Họ và tên không được để trống.';
    } elseif (mb_strlen($data['fullname']) < 3 || mb_strlen($data['fullname']) > 50) {
        $errors['fullname'] = 'Họ và tên phải từ 3 đến 50 ký tự.';
    }

    // 3. Validate Tên đăng nhập Username (Chỉ gồm chữ, số, gạch dưới, 4 - 20 ký tự)
    if (empty($data['username'])) {
        $errors['username'] = 'Tên đăng nhập không được để trống.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $data['username'])) {
        $errors['username'] = 'Tên đăng nhập từ 4-20 ký tự, chỉ gồm chữ cái, số và dấu gạch dưới (_).';
    }

    // 4. Validate Email (FILTER_VALIDATE_EMAIL)
    if (empty($data['email'])) {
        $errors['email'] = 'Email không được để trống.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Định dạng Email không hợp lệ (Ví dụ: admin@news.com).';
    }

    // 5. Validate Mật khẩu (Tối thiểu 6 ký tự & Khớp mật khẩu xác nhận)
    if (empty($password)) {
        $errors['password'] = 'Mật khẩu không được để trống.';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự.';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp.';
    }

    // 6. Xử lý Upload ảnh đại diện an toàn (Check Size, Extension, MIME type, Rename)
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

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['avatar'] = 'Lỗi trong quá trình upload ảnh đại diện.';
        } elseif ($file['size'] > $maxFileSize) {
            $errors['avatar'] = 'Dung lượng ảnh đại diện vượt quá 2MB.';
        } elseif (!in_array($fileExt, $allowedExtensions)) {
            $errors['avatar'] = 'Chỉ chấp nhận các định dạng ảnh: JPG, JPEG, PNG, GIF.';
        } else {
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowedMimeTypes)) {
                $errors['avatar'] = 'File tải lên không phải là ảnh hợp lệ (MIME Type sai).';
            } else {
                // Đổi tên file ngẫu nhiên tránh ghi đè
                $newFileName = uniqid('admin_avatar_', true) . '.' . $fileExt;
                $targetFilePath = $targetDir . $newFileName;

                if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                    $uploadedAvatar = $targetFilePath;
                } else {
                    $errors['avatar'] = 'Không thể lưu file ảnh vào hệ thống.';
                }
            }
        }
    }

    // Nếu không có lỗi -> Thành công
    if (empty($errors)) {
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ Thống Quản Trị Blog - Đăng Ký Admin</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; margin: 40px 0; color: #333; }
        .card { background: white; padding: 30px 35px; border-radius: 10px; max-width: 520px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin: 0 auto; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h2 { color: #1877f2; margin-bottom: 5px; }
        .header p { color: #65676b; font-size: 14px; margin: 0; }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; }
        input[type="text"], input[type="email"], input[type="password"], select, textarea, input[type="file"] {
            width: 100%; padding: 10px 12px; box-sizing: border-box; border: 1px solid #ccd0d5; border-radius: 6px; font-size: 14px;
        }
        input:focus, select:focus, textarea:focus { border-color: #1877f2; outline: none; }
        .error { color: #dc3545; font-size: 13px; margin-top: 4px; display: block; }
        button { background: #1877f2; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%; font-size: 16px; margin-top: 10px; }
        button:hover { background: #166fe5; }
        .alert-success { background: #e7f3ff; color: #1877f2; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #b8daff; }
        .avatar-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #1877f2; margin-top: 8px; }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <h2>WEBSITE TIN TỨC / BLOG</h2>
        <p>Tạo tài khoản Quản trị viên (Admin Portal)</p>
    </div>

    <?php if ($success): ?>
        <div class="alert-success">
            <h3 style="margin-top:0;">✓ Đăng ký tài khoản Admin thành công!</h3>
            <p><strong>Họ và tên:</strong> <?= e($data['fullname']) ?></p>
            <p><strong>Username:</strong> <?= e($data['username']) ?></p>
            <p><strong>Email:</strong> <?= e($data['email']) ?></p>
            <p><strong>Phân quyền:</strong> <?= e($data['role']) ?></p>
            <p><strong>Tiểu sử:</strong> <?= nl2br(e($data['bio'] ?: 'Chưa cập nhật')) ?></p>
            <?php if ($uploadedAvatar): ?>
                <p><strong>Ảnh đại diện:</strong><br><img src="<?= e($uploadedAvatar) ?>" class="avatar-img" alt="Avatar"></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" novalidate>
        <!-- Họ và tên -->
        <div class="form-group">
            <label>Họ và Tên (*):</label>
            <input type="text" name="fullname" value="<?= e($data['fullname']) ?>" placeholder="Ví dụ: Trần Thị Hà Anh">
            <?php if (isset($errors['fullname'])): ?><span class="error"><?= e($errors['fullname']) ?></span><?php endif; ?>
        </div>

        <!-- Username -->
        <div class="form-group">
            <label>Tên đăng nhập (Username) (*):</label>
            <input type="text" name="username" value="<?= e($data['username']) ?>" placeholder="admin_haanh">
            <?php if (isset($errors['username'])): ?><span class="error"><?= e($errors['username']) ?></span><?php endif; ?>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label>Email (*):</label>
            <input type="email" name="email" value="<?= e($data['email']) ?>" placeholder="haanh@newssite.com">
            <?php if (isset($errors['email'])): ?><span class="error"><?= e($errors['email']) ?></span><?php endif; ?>
        </div>

        <!-- Mật khẩu -->
        <div class="form-group">
            <label>Mật khẩu (*):</label>
            <input type="password" name="password" placeholder="Tối thiểu 6 ký tự">
            <?php if (isset($errors['password'])): ?><span class="error"><?= e($errors['password']) ?></span><?php endif; ?>
        </div>

        <!-- Xác nhận Mật khẩu -->
        <div class="form-group">
            <label>Xác nhận Mật khẩu (*):</label>
            <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu">
            <?php if (isset($errors['confirm_password'])): ?><span class="error"><?= e($errors['confirm_password']) ?></span><?php endif; ?>
        </div>

        <!-- Phân quyền -->
        <div class="form-group">
            <label>Vai trò / Phân quyền:</label>
            <select name="role">
                <option value="Admin" <?= $data['role'] === 'Admin' ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                <option value="Editor" <?= $data['role'] === 'Editor' ? 'selected' : '' ?>>Biên tập viên (Editor)</option>
                <option value="Author" <?= $data['role'] === 'Author' ? 'selected' : '' ?>>Tác giả (Author)</option>
            </select>
        </div>

        <!-- Tiểu sử -->
        <div class="form-group">
            <label>Giới thiệu / Tiểu sử ngắn:</label>
            <textarea name="bio" rows="2" placeholder="Nhập một vài dòng giới thiệu..."><?= e($data['bio']) ?></textarea>
        </div>

        <!-- Ảnh đại diện -->
        <div class="form-group">
            <label>Ảnh đại diện (Avatar - Tối đa 2MB):</label>
            <input type="file" name="avatar" accept="image/*">
            <?php if (isset($errors['avatar'])): ?><span class="error"><?= e($errors['avatar']) ?></span><?php endif; ?>
        </div>

        <button type="submit">Đăng Ký Tài Khoản Admin</button>
    </form>
</div>

</body>
</html>