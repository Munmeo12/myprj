<?php
/**
 * Hàm tính điểm tổng kết môn học
 * 
 * @param float $cc    Điểm chuyên cần (trọng số 10%)
 * @param float $mid   Điểm giữa kỳ (trọng số 30%)
 * @param float $final Điểm cuối kỳ (trọng số 60%)
 * @return float       Điểm tổng kết môn học
 */
function finalScore(float $cc, float $mid, float $final): float
{
    return $cc * 0.10 + $mid * 0.30 + $final * 0.60;
}

// Khai báo các biến lưu thông báo và kết quả
$ketQua = "";
$cc = $mid = $final = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cc = $_POST['cc'] ?? '';
    $mid = $_POST['mid'] ?? '';
    $final = $_POST['final'] ?? '';

    // Kiểm tra tính hợp lệ của dữ liệu đầu vào
    if (is_numeric($cc) && is_numeric($mid) && is_numeric($final)
        && $cc >= 0 && $cc <= 10
        && $mid >= 0 && $mid <= 10
        && $final >= 0 && $final <= 10) {

        // Gọi hàm tính điểm tổng kết
        $score = finalScore((float)$cc, (float)$mid, (float)$final);
        
        // Làm tròn điểm đến 1 chữ số thập phân
        $scoreFormatted = number_format($score, 1);

        $ketQua = "
            <div style='background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-top: 15px;'>
                <strong>Điểm tổng kết:</strong> $scoreFormatted / 10
            </div>
        ";
    } else {
        $ketQua = "
            <div style='background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-top: 15px;'>
                Vui lòng nhập đầy đủ điểm từ 0 đến 10 cho cả 3 cột điểm!
            </div>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2.5: Tính Điểm Tổng Kết</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f9; }
        .card { background: white; padding: 25px; border-radius: 8px; max-width: 420px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="card">
    <h2>Tính Điểm Tổng Kết Môn Học</h2>
    
    <form action="" method="POST">
        <div class="form-group">
            <label>Điểm Chuyên Cần (10%):</label>
            <input type="text" name="cc" value="<?php echo htmlspecialchars($cc); ?>" placeholder="Ví dụ: 9">
        </div>

        <div class="form-group">
            <label>Điểm Giữa Kỳ (30%):</label>
            <input type="text" name="mid" value="<?php echo htmlspecialchars($mid); ?>" placeholder="Ví dụ: 8">
        </div>

        <div class="form-group">
            <label>Điểm Cuối Kỳ (60%):</label>
            <input type="text" name="final" value="<?php echo htmlspecialchars($final); ?>" placeholder="Ví dụ: 7.5">
        </div>

        <button type="submit">Tính Điểm</button>
    </form>

    <?php echo $ketQua; ?>
</div>

</body>
</html>