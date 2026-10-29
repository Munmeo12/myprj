<?php
$name  = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$note  = $_POST['note'] ?? '';

$errors = [
    'name'  => '',
    'email' => '',
    'phone' => ''
];

$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name  = trim($name);
    $email = trim($email);
    $phone = trim($phone);
    $note  = trim($note);

    $isValid = true;

    // Validate Họ tên (3-50 ký tự)
    if (empty($name)) {
        $errors['name'] = "Họ và tên không được để trống.";
        $isValid = false;
    } elseif (mb_strlen($name) < 3 || mb_strlen($name) > 50) {
        $errors['name'] = "Họ và tên phải từ 3 đến 50 ký tự.";
        $isValid = false;
    }

    // Validate Email
    if (empty($email)) {
        $errors['email'] = "Email không được để trống.";
        $isValid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Định dạng Email không hợp lệ.";
        $isValid = false;
    }

    // Validate SĐT
    if (!empty($phone)) {
        if (!preg_match('/^0[0-9]{9}$/', $phone)) {
            $errors['phone'] = "Số điện thoại phải gồm 10 chữ số bắt đầu bằng số 0.";
            $isValid = false;
        }
    }

    if ($isValid) {
        $successMessage = "Gửi thông tin liên hệ thành công! Dữ liệu đã kiểm tra an toàn phía Server.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 3.9: Form liên hệ an toàn</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f6f9; color: #333; }
        .card { background: white; padding: 25px 30px; border-radius: 8px; max-width: 480px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin: 0 auto; }
        h2 { text-align: center; color: #007bff; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 9px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .error-text { color: #dc3545; font-size: 13px; margin-top: 4px; display: block; }
        button { background: #007bff; color: white; border: none; padding: 11px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; font-size: 16px; }
        button:hover { background: #0056b3; }
        .success-box { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="card">
    <h2>Form Liên Hệ An Toàn</h2>

    <?php if ($successMessage != ""): ?>
        <div class="success-box">
            <strong>✓ <?= htmlspecialchars($successMessage) ?></strong>
            <p style="margin-top: 10px;"><strong>Họ tên:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>SĐT:</strong> <?= htmlspecialchars($phone ?: 'Chưa nhập') ?></p>
            <p><strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($note ?: 'Không có')) ?></p>
        </div>
    <?php endif; ?>

    <form action="" method="POST" novalidate>
        <div class="form-group">
            <label>Họ và Tên (*):</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="Nhập từ 3 - 50 ký tự">
            <?php if ($errors['name']): ?>
                <span class="error-text"><?= htmlspecialchars($errors['name']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Email (*):</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="example@gmail.com">
            <?php if ($errors['email']): ?>
                <span class="error-text"><?= htmlspecialchars($errors['email']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Số điện thoại:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" placeholder="0912345678">
            <?php if ($errors['phone']): ?>
                <span class="error-text"><?= htmlspecialchars($errors['phone']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Ghi chú / Nội dung:</label>
            <textarea name="note" rows="3"><?= htmlspecialchars($note) ?></textarea>
        </div>

        <button type="submit">Gửi Thông Tin</button>
    </form>
</div>

</body>
</html>