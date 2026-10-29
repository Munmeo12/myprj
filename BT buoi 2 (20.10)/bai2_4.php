<?php
// 1. Khai báo mảng chứa 3 sinh viên (Mảng đa chiều / Mảng chứa mảng kết hợp)
$students = [
    ['name' => 'An', 'score' => 8.0],
    ['name' => 'Bình', 'score' => 6.5],
    ['name' => 'Chi', 'score' => 4.5],
];

// Hàm phụ trợ xếp loại dựa trên điểm số (kết hợp bài trước)
function getXepLoai($score) {
    if ($score >= 8.0) return "<span style='color: green; font-weight: bold;'>Giỏi</span>";
    if ($score >= 6.5) return "<span style='color: blue; font-weight: bold;'>Khá</span>";
    if ($score >= 5.0) return "<span style='color: orange; font-weight: bold;'>Trung bình</span>";
    return "<span style='color: red; font-weight: bold;'>Chưa đạt</span>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 2.4: Mảng Chỉ Số và Mảng Kết Hợp</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f9; }
        .card { background: white; padding: 25px; border-radius: 8px; max-width: 500px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

<div class="card">
    <h2>Danh Sách Sinh Viên</h2>

    <!-- In dạng danh sách text đơn giản (giống minh họa slide) -->
    <h3>1. In dạng văn bản đơn giản:</h3>
    <div style="background: #e9ecef; padding: 10px; border-radius: 4px;">
        <?php
        foreach ($students as $student) {
            echo "Tên: " . $student['name'] . " - Điểm: " . $student['score'] . "<br>";
        }
        ?>
    </div>

    <!-- In dạng Bảng HTML (chuẩn giao diện web thực tế) -->
    <h3>2. In dưới dạng Bảng HTML:</h3>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên Sinh Viên</th>
                <th>Điểm Số</th>
                <th>Xếp Loại</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $stt = 1;
            foreach ($students as $student): 
            ?>
                <tr>
                    <td><?php echo $stt++; ?></td>
                    <td><strong><?php echo $student['name']; ?></strong></td>
                    <td><?php echo $student['score']; ?></td>
                    <td><?php echo getXepLoai($student['score']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>