<?php
// Hàm định dạng số tiền dạng x.xxx.xxx đ
function money(float $value): string {
    return number_format($value, 0, ',', '.') . ' đ';
}

// Đọc dữ liệu từ POST và ép kiểu an toàn
$name = $_POST['name'] ?? '';
$qty = (int)($_POST['qty'] ?? 0);
$price = (float)($_POST['price'] ?? 0);

// Tính thành tiền
$total = $qty * $price;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>2.7 Demo: Hóa đơn học liệu</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        form { display: flex; gap: 8px; align-items: center; margin-bottom: 20px; }
        input { padding: 6px 10px; border: 1px solid #767676; border-radius: 2px; }
        button { padding: 6px 12px; cursor: pointer; }
        p { font-size: 18px; font-weight: 500; }
    </style>
</head>
<body>

    <form method="post">
        <input name="name" placeholder="Tên tài liệu" value="<?= htmlspecialchars($name) ?>">
        <input name="qty" type="number" min="1" placeholder="Số lượng" value="<?= $qty ?: '' ?>">
        <input name="price" type="number" min="0" placeholder="Đơn giá" value="<?= $price ?: '' ?>">
        <button type="submit">Tính</button>
    </form>

    <?php if ($total > 0): ?>
        <p><?= htmlspecialchars($name) ?>: <?= money($total) ?></p>
    <?php endif; ?>

</body>
</html>