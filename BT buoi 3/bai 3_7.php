<?php
// Mảng lưu thông báo lỗi và mảng lưu dữ liệu người dùng nhập
$errors = [];
$data = [
    'name'     => '',
    'email'    => '',
    'birthday' => ''
];

// Hàm mã hóa an toàn chống XSS
function e($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

// Kiểm tra khi gửi Form (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['name']     = trim($_POST['name'] ?? '');
    $data['email']    = trim($_POST['email'] ?? '');
    $data['birthday'] = trim($_POST['birthday'] ?? '');

    // Kiểm tra trường Họ và tên
    if ($data['name'] === '') {
        $errors['name'] = 'Vui lòng nhập họ tên';
    }

    // Kiểm tra định dạng Email hợp lệ
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ';
    }

    // Kiểm tra Ngày sinh
    if ($data['birthday'] === '') {
        $errors['birthday'] = 'Vui lòng nhập ngày sinh';
    }

    // Nếu không có lỗi nào -> Chuyển hướng hoặc xử lý thành công
    if (!$errors) {
        // Có thể chuyển hướng sang trang success.php hoặc hiển thị thông báo thành công
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>3.7 Form Đăng Ký Sinh Viên</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f6f9; color: #333; }
        .card { background: white; padding: 25px 30px; border-radius: 8px; max-width: 480px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin: 0 auto; }
        h2 { text-align: center; color: #007bff; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="date"] { width: 100%; padding: 9px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .error { color: #dc3545; font-size: 13px; margin-top: 4px; display: block; }
        button { background: #007bff; color: white; border: none; padding: 11px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; font-size: 16px; }
        button:hover { background: #0056b3; }
        .success-box { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="card">
    <h2>Đăng Ký Sinh Viên</h2>

    <?php if (isset($success) && $success): ?>
        <div class="success-box">
            <strong>✓ Đăng ký thành công!</strong>
            <p><strong>Họ tên:</strong> <?= e($data['name']) ?></p>
            <p><strong>Email:</strong> <?= e($data['email']) ?></p>
            <p><strong>Ngày sinh:</strong> <?= e($data['birthday']) ?></p>
        </div>
    <?php endif; ?>

    <form action="" method="POST" novalidate>
        <!-- Họ và tên -->
        <div class="form-group">
            <label>Họ và Tên:</label>
            <input type="text" name="name" value="<?= e($data['name']) ?>" placeholder="Nhập họ và tên">
            <?php if (isset($errors['name'])): ?>
                <span class="error"><?= e($errors['name']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?= e($data['email']) ?>" placeholder="example@domain.com">
            <?php if (isset($errors['email'])): ?>
                <span class="error"><?= e($errors['email']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Ngày sinh -->
        <div class="form-group">
            <label>Ngày sinh:</label>
            <input type="date" name="birthday" value="<?= e($data['birthday']) ?>">
            <?php if (isset($errors['birthday'])): ?>
                <span class="error"><?= e($errors['birthday']) ?></span>
            <?php endif; ?>
        </div>

        <button type="submit">Đăng Ký</button>
    </form>
</div>

</body>
</html>