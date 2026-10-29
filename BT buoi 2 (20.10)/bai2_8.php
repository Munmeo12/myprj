<?php
// 1. Tạo mảng $students gồm 3 sinh viên với name, midterm, final
$students = [
    ['name' => 'Nguyễn Văn An', 'midterm' => 7.5, 'final' => 8.0],
    ['name' => 'Trần Thị Bình', 'midterm' => 4.0, 'final' => 5.5],
    ['name' => 'Lê Hoàng Chi',  'midterm' => 3.5, 'final' => 4.0]
];

// 2. Viết hàm calculateAverage() tính điểm trung bình (lấy trung bình cộng giữa kỳ và cuối kỳ)
function calculateAverage(float $midterm, float $final): float {
    return ($midterm + $final) / 2;
}

// Hàm phụ trợ lấy kết quả Đạt/Không đạt
function getResult(float $avg): string {
    if ($avg >= 5.0) {
        return "<span style='color: green; font-weight: bold;'>Đạt</span>";
    }
    return "<span style='color: red; font-weight: bold;'>Không đạt</span>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>2.8 Bảng điểm 3 sinh viên</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f8f9fa; }
        .card { background: white; padding: 25px; border-radius: 8px; max-width: 600px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin: 0 auto; }
        h2 { color: #333; text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #dee2e6; padding: 10px 12px; text-align: center; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .text-left { text-align: left; }
    </style>
</head>
<body>

<div class="card">
    <h2>BẢNG ĐIỂM SINH VIÊN</h2>

    <!-- 3. Dùng foreach để in bảng HTML -->
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên Sinh Viên</th>
                <th>Giữa Kỳ</th>
                <th>Cuối Kỳ</th>
                <th>ĐTB</th>
                <th>Kết Quả</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $stt = 1;
            foreach ($students as $student): 
                // Tính điểm trung bình
                $avg = calculateAverage((float)$student['midterm'], (float)$student['final']);
            ?>
                <tr>
                    <td><?= $stt++ ?></td>
                    <!-- 5. Mã hóa tên bằng htmlspecialchars() trước khi in -->
                    <td class="text-left"><strong><?= htmlspecialchars($student['name']) ?></strong></td>
                    <td><?= number_format($student['midterm'], 1) ?></td>
                    <td><?= number_format($student['final'], 1) ?></td>
                    <td><strong><?= number_format($avg, 1) ?></strong></td>
                    <!-- 4. Thêm cột kết quả: Đạt nếu ĐTB >= 5 -->
                    <td><?= getResult($avg) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>