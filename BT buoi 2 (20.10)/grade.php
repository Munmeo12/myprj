<?php
// 1. Hàm kiểm tra điểm hợp lệ (0 - 10)
function isValidScore($score): bool {
    return is_numeric($score) && $score >= 0 && $score <= 10;
}

// 2. Hàm tính điểm tổng kết (10% CC, 30% GK, 60% CK)
function finalScore(float $cc, float $mid, float $final): float {
    return $cc * 0.10 + $mid * 0.30 + $final * 0.60;
}

// 3. Hàm xếp loại
function getGradeCategory(float $score): string {
    if ($score >= 8.0) return "<span style='color: #28a745; font-weight: bold;'>Giỏi</span>";
    if ($score >= 6.5) return "<span style='color: #007bff; font-weight: bold;'>Khá</span>";
    if ($score >= 5.0) return "<span style='color: #fd7e14; font-weight: bold;'>Trung bình</span>";
    return "<span style='color: #dc3545; font-weight: bold;'>Chưa đạt</span>";
}

$name  = $_POST['name'] ?? '';
$cc    = $_POST['cc'] ?? '';
$mid   = $_POST['mid'] ?? '';
$final = $_POST['final'] ?? '';

$errors = [];
$resultHtml = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($name);

    if (empty($name)) {
        $errors[] = "Vui lòng nhập Họ và Tên sinh viên.";
    }
    if ($cc === '' || !isValidScore($cc)) {
        $errors[] = "Điểm chuyên cần không hợp lệ (Phải là số từ 0 đến 10).";
    }
    if ($mid === '' || !isValidScore($mid)) {
        $errors[] = "Điểm giữa kỳ không hợp lệ (Phải là số từ 0 đến 10).";
    }
    if ($final === '' || !isValidScore($final)) {
        $errors[] = "Điểm cuối kỳ không hợp lệ (Phải là số từ 0 đến 10).";
    }

    if (empty($errors)) {
        $total = finalScore((float)$cc, (float)$mid, (float)$final);
        $totalFormatted = number_format($total, 1);
        $category = getGradeCategory($total);

        $resultHtml = "
            <div class='result-box'>
                <h3>KẾT QUẢ HỌC PHẦN</h3>
                <p><strong>Họ và tên:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>Điểm Chuyên cần (10%):</strong> " . number_format((float)$cc, 1) . "</p>
                <p><strong>Điểm Giữa kỳ (30%):</strong> " . number_format((float)$mid, 1) . "</p>
                <p><strong>Điểm Cuối kỳ (60%):</strong> " . number_format((float)$final, 1) . "</p>
                <hr>
                <p><strong>Điểm Tổng Kết:</strong> <span style='font-size: 1.2em; color: #007bff; font-weight: bold;'>$totalFormatted</span> / 10</p>
                <p><strong>Xếp loại:</strong> $category</p>
            </div>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2.9: Máy tính điểm học phần</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f6f9; color: #333; }
        .card { background: white; padding: 25px 30px; border-radius: 8px; max-width: 480px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin: 0 auto; }
        h2 { text-align: center; color: #007bff; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 9px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        button { background: #007bff; color: white; border: none; padding: 11px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; font-size: 16px; }
        button:hover { background: #0056b3; }
        .error-box { background: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; }
        .error-box ul { margin: 0; padding-left: 20px; }
        .result-box { background: #e8f4f8; padding: 15px 20px; border-radius: 6px; margin-top: 20px; border-left: 4px solid #007bff; }
        .result-box h3 { margin-top: 0; color: #007bff; }
    </style>
</head>
<body>

<div class="card">
    <h2>Máy Tính Điểm Học Phần</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <strong>Có lỗi xảy ra:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label>Họ và Tên Sinh Viên:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="Ví dụ: Trần Thị Hà Anh">
        </div>

        <div class="form-group">
            <label>Điểm Chuyên Cần (10%):</label>
            <input type="text" name="cc" value="<?= htmlspecialchars($cc) ?>" placeholder="Nhập điểm từ 0 - 10">
        </div>

        <div class="form-group">
            <label>Điểm Giữa Kỳ (30%):</label>
            <input type="text" name="mid" value="<?= htmlspecialchars($mid) ?>" placeholder="Nhập điểm từ 0 - 10">
        </div>

        <div class="form-group">
            <label>Điểm Cuối Kỳ (60%):</label>
            <input type="text" name="final" value="<?= htmlspecialchars($final) ?>" placeholder="Nhập điểm từ 0 - 10">
        </div>

        <button type="submit">Tính Điểm & Xếp Loại</button>
    </form>

    <?= $resultHtml ?>
</div>

</body>
</html>