<?php
// Hàm xếp loại học lực dựa trên điểm số
function xepLoaiHocLuc($diem) {
    // 1. Kiểm tra dữ liệu hợp lệ trước khi tính toán
    if (!is_numeric($diem) || $diem < 0 || $diem > 10) {
        return "Điểm không hợp lệ! (Phải là số từ 0 đến 10)";
    }

    // 2. Rẽ nhánh điều kiện từ CAO xuống THẤP
    if ($diem >= 8.0) {
        return "<span style='color: green; font-weight: bold;'>Giỏi</span>";
    } elseif ($diem >= 6.5) {
        return "<span style='color: blue; font-weight: bold;'>Khá</span>";
    } elseif ($diem >= 5.0) {
        return "<span style='color: orange; font-weight: bold;'>Trung bình</span>";
    } else {
        return "<span style='color: red; font-weight: bold;'>Chưa đạt</span>";
    }
}

$ketQua = "";
$diemNhap = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $diemNhap = $_POST['diem'] ?? '';
    
    if ($diemNhap !== '') {
        $xepLoai = xepLoaiHocLuc($diemNhap);
        $ketQua = "Với điểm số <strong>$diemNhap</strong>, xếp loại của bạn là: $xepLoai";
    } else {
        $ketQua = "<span style='color: red;'>Vui lòng nhập điểm số!</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2.3: Xếp Loại Học lực</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f9; }
        .card { background: white; padding: 25px; border-radius: 8px; max-width: 420px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #218838; }
        .result { margin-top: 15px; padding: 12px; background: #e9ecef; border-radius: 4px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Xếp Loại Học Lực</h2>
    
    <form action="" method="POST">
        <div class="form-group">
            <label>Nhập điểm số (0 - 10):</label>
            <input type="text" name="diem" value="<?php echo htmlspecialchars($diemNhap); ?>" placeholder="Ví dụ: 7.5">
        </div>
        <button type="submit">Xem Xếp Loại</button>
    </form>

    <?php if ($ketQua != ""): ?>
        <div class="result">
            <?php echo $ketQua; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>