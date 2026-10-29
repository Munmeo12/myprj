<?php
$ketQua = "";
$tenTaiLieu = "";
$soLuong = "";
$donGia = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenTaiLieu = isset($_POST['tenTaiLieu']) ? trim($_POST['tenTaiLieu']) : '';
    $soLuong = isset($_POST['soLuong']) ? $_POST['soLuong'] : '';
    $donGia = isset($_POST['donGia']) ? $_POST['donGia'] : '';

    // Kiểm tra dữ liệu hợp lệ
    if ($tenTaiLieu != "" && is_numeric($soLuong) && is_numeric($donGia) && $soLuong > 0 && $donGia >= 0) {
        $tongTien = $soLuong * $donGia;
        // Định dạng tiền tệ dạng x.xxx.xxx đ
        $tongTienFormatted = number_format($tongTien, 0, ',', '.') . " đ";
        
        $ketQua = htmlspecialchars($tenTaiLieu) . ": " . $tongTienFormatted;
    } else {
        $ketQua = "<span style='color: red;'>Vui lòng nhập đầy đủ và chính xác thông tin!</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài tập: Tính tiền tài liệu</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-container { display: flex; gap: 8px; align-items: center; margin-bottom: 20px; }
        input[type="text"], input[type="number"] { padding: 6px 10px; border: 1px solid #767676; border-radius: 2px; }
        button { padding: 6px 12px; cursor: pointer; }
        .result { font-size: 18px; margin-top: 15px; }
    </style>
</head>
<body>

    <form action="" method="POST">
        <div class="form-container">
            <input type="text" name="tenTaiLieu" placeholder="Tên tài liệu" value="<?php echo htmlspecialchars($tenTaiLieu); ?>">
            <input type="number" name="soLuong" placeholder="Số lượng" value="<?php echo htmlspecialchars($soLuong); ?>">
            <input type="number" name="donGia" placeholder="Đơn giá" value="<?php echo htmlspecialchars($donGia); ?>">
            <button type="submit">Tính</button>
        </div>
    </form>

    <?php if ($ketQua != ""): ?>
        <div class="result">
            <?php echo $ketQua; ?>
        </div>
    <?php endif; ?>

</body>
</html>