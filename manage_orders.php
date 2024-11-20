<?php
session_start();
require_once 'config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: homepage.php");
    exit();
}

// Lấy danh sách đơn hàng
$sql = "SELECT 
            dh.DH_MADON, 
            dh.KH_CCCD, 
            dh.XE_ID, 
            dh.DH_NGAYLAP, 
            dh.DH_SONGAYTHUE, 
            dh.DH_NGAYBD, 
            dh.DH_NGAYKT, 
            dh.DH_TONGTIEN, 
            dh.DH_TIENCOC
        FROM don_hang dh";
$result = $conn->query($sql);

// Kiểm tra lỗi truy vấn
if (!$result) {
    die("Lỗi truy vấn SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn Hàng - CaR88</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff7f00;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #e67300;
            border-color: #e67300;
        }

        .table-hover tbody tr:hover {
            background-color: #ffe4cc;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Quản lý Đơn Hàng</h1>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>STT</th>
                        <th>CCCD Khách Hàng</th>
                        <th>Mã Xe</th>
                        <th>Mã Đơn</th>
                        <th>Ngày Lập</th>
                        <th>Số Ngày Thuê</th>
                        <th>Ngày Bắt Đầu</th>
                        <th>Ngày Kết Thúc</th>
                        <th>Tổng Tiền</th>
                        <th>Tiền Cọc</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        $stt = 1;
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?= $stt++ ?></td>
                                <td><?= $row['KH_CCCD'] ?></td>
                                <td><?= $row['XE_ID'] ?></td>
                                <td><?= $row['DH_MADON'] ?></td>
                                <td><?= $row['DH_NGAYLAP'] ?></td>
                                <td><?= $row['DH_SONGAYTHUE'] ?></td>
                                <td><?= $row['DH_NGAYBD'] ?></td>
                                <td><?= $row['DH_NGAYKT'] ?></td>
                                <td><?= number_format($row['DH_TONGTIEN'], 0, ',', '.') ?> VNĐ</td>
                                <td><?= number_format($row['DH_TIENCOC'], 0, ',', '.') ?> VNĐ</td>
                                <td>
                                    <a href="edit_order.php?id=<?= $row['DH_MADON'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="10" class="text-center">Không có dữ liệu</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay về Dashboard
            </a>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
