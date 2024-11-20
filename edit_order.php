<?php
session_start();
require_once 'config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: homepage.php");
    exit();
}

// Kiểm tra nếu có `id` được truyền qua URL
if (!isset($_GET['id'])) {
    header("Location: manage_orders.php");
    exit();
}

$id = $_GET['id'];

// Lấy thông tin đơn hàng dựa trên `id`
$sql = "SELECT * FROM don_hang WHERE DH_MADON = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "Không tìm thấy đơn hàng!";
    exit();
}

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ngay_bat_dau = $_POST['ngay_bat_dau'];
    $ngay_ket_thuc = $_POST['ngay_ket_thuc'];

    // Tính số ngày thuê
    $date1 = new DateTime($ngay_bat_dau);
    $date2 = new DateTime($ngay_ket_thuc);
    $interval = $date1->diff($date2);
    $so_ngay_thue = $interval->days;  // Số ngày thuê

    // Cập nhật thông tin đơn hàng
    $sql_update = "UPDATE don_hang SET 
                    DH_NGAYBD = ?, 
                    DH_NGAYKT = ?, 
                    DH_SONGAYTHUE = ? 
                    WHERE DH_MADON = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param(
        "ssii",
        $ngay_bat_dau,
        $ngay_ket_thuc,
        $so_ngay_thue,
        $id
    );

    if ($stmt_update->execute()) {
        header("Location: manage_orders.php?success=update");
    } else {
        echo "Lỗi khi cập nhật đơn hàng: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Đơn Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Chỉnh sửa Đơn Hàng</h1>
        <form method="POST" class="needs-validation" novalidate>
            <!-- Các trường không cần sửa đổi bị loại bỏ -->
            <div class="mb-3">
                <label for="ngay_bat_dau" class="form-label">Ngày Nhận (Ngày Bắt Đầu)</label>
                <input type="datetime-local" class="form-control" id="ngay_bat_dau" name="ngay_bat_dau" value="<?= $order['DH_NGAYBD'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="ngay_ket_thuc" class="form-label">Ngày Trả (Ngày Kết Thúc)</label>
                <input type="datetime-local" class="form-control" id="ngay_ket_thuc" name="ngay_ket_thuc" value="<?= $order['DH_NGAYKT'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="manage_orders.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <script>
        // Hàm tính số ngày thuê tự động nhưng không hiển thị số ngày
        function calculateDays() {
            var ngayBatDau = new Date(document.getElementById('ngay_bat_dau').value);
            var ngayKetThuc = new Date(document.getElementById('ngay_ket_thuc').value);

            if (ngayBatDau && ngayKetThuc && ngayKetThuc >= ngayBatDau) {
                var timeDiff = ngayKetThuc - ngayBatDau;
                var dayDiff = timeDiff / (1000 * 3600 * 24);  // Chuyển từ mili giây sang ngày
                // Không cần hiển thị số ngày thuê, nhưng có thể log hoặc gửi về server nếu cần
                console.log('Số Ngày Thuê: ' + dayDiff);
            }
        }

        // Gắn sự kiện để tính lại số ngày thuê khi ngày bắt đầu hoặc ngày kết thúc thay đổi
        document.getElementById('ngay_bat_dau').addEventListener('change', calculateDays);
        document.getElementById('ngay_ket_thuc').addEventListener('change', calculateDays);
    </script>
</body>

</html>
