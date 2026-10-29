<?php
// Định nghĩa hàm kiểm tra điểm hợp lệ
function kiemTraDiemHopLe($score) {
    // Kiểm tra: phải là số (is_numeric) VÀ >= 0 VÀ <= 10
    return is_numeric($score) && $score >= 0 && $score <= 10;
}

$thongBao = "";
$diemVuaNhap = "";

// Kiểm tra khi người dùng bấm nút Gửi (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $diemVuaNhap = isset($_POST['diem']) ? $_POST['diem'] : '';

    if ($diemVuaNhap === '') {
        $thongBao = "<span style='color: red;'>Vui lòng nhập điểm!</span>";
    } elseif (kiemTraDiemHopLe($diemVuaNhap)) {
        $thongBao = "<span style='color: green; font-weight: bold;'>Điểm $diemVuaNhap HỢP LỆ (nằm trong khoảng từ 0 đến 10).</span>";
    } else {
        $thongBao = "<span style='color: red; font-weight: bold;'>Điểm '$diemVuaNhap' KHÔNG HỢP LỆ! (Phải là số từ 0 đến 10).</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2: Kiểm Tra Điểm Hợp Lệ</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f9; }
        .box { background: white; padding: 20px; border-radius: 8px; max-width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .result { margin-top: 15px; padding: 10px; background: #eee; border-radius: 4px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Kiểm Tra Điểm Hợp Lệ</h2>
    
    <form action="" method="POST">
        <div class="form-group">
            <label>Nhập điểm (từ 0 đến 10):</label>
            <input type="text" name="diem" value="<?php echo htmlspecialchars($diemVuaNhap); ?>" placeholder="Ví dụ: 8.5">
        </div>
        <button type="submit">Kiểm Tra</button>
    </form>

    <?php if ($thongBao != ""): ?>
        <div class="result">
            <strong>Kết quả:</strong> <?php echo $thongBao; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>