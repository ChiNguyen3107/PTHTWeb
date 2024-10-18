<?php
session_start();
require_once 'config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: homepage.php");
    exit();
}

// Xử lý xóa xe
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM xe WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Lấy danh sách xe
$sql = "SELECT xe.*, hang_xe.ten_hang_xe, dong_xe.ten_dong_xe 
        FROM xe 
        JOIN hang_xe ON xe.hang_xe_id = hang_xe.id 
        JOIN dong_xe ON xe.dong_xe_id = dong_xe.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý xe - CaR88</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary-color: #ff7f00;
            /* Màu chính */
            --secondary-color: #f44336;
            /* Màu phụ */
            --background-color: #f4f4f4;
            /* Màu nền */
            --table-header-color: #ff7f00;
            /* Màu tiêu đề bảng */
            --table-row-color: #ffffff;
            /* Màu hàng bảng */
            --table-row-hover-color: #ffe4cc;
            /* Màu hàng khi hover */
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-container {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-add {
            background-color: var(--primary-color);
        }

        .btn-add:hover {
            background-color: var(--hover-color);
        }

        .btn-back {
            background-color: var(--primary-color);
        }

        .btn-back:hover {
            background-color: var(--hover-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--table-row-color);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--table-header-color);
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: var(--table-row-color);
        }

        tr:hover {
            background-color: var(--table-row-hover-color);
        }

        .btn-edit,
        .btn-delete {
            padding: 5px 10px;
            font-size: 14px;
        }

        .btn-edit {
            background-color: var(--primary-color);
        }

        .btn-edit:hover {
            background-color: var(--hover-color);
        }

        .btn-delete {
            background-color: var(--secondary-color);
        }

        .btn-delete:hover {
            background-color: #c62828;
        }
    </style>
</head>

<body>
    <h1>Quản lý xe</h1>
    <table>
        <tr>
            <th>STT</th>
            <th>Hãng xe</th>
            <th>Dòng xe</th>
            <th>Phiên bản</th>
            <th>Năm sản xuất</th>
            <th>Giá</th>
            <th>Thao tác</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['ten_hang_xe'] ?></td>
                    <td><?= $row['ten_dong_xe'] ?></td>
                    <td><?= $row['phien_ban'] ?></td>
                    <td><?= $row['nam_san_xuat'] ?></td>
                    <td><?= number_format($row['gia'], 0, ',', '.') ?> VNĐ</td>
                    <td>
                        <a href="edit_car.php?id=<?= $row['id'] ?>" class="btn btn-edit">Sửa</a>
                        <a href="manage_cars.php?delete=<?= $row['id'] ?>" class="btn btn-delete">Xóa</a>
                    </td>
                </tr>
            <?php }
        } else { ?>

            <tr>
                <a href="formthemxe.php" class="btn btn-add">Thêm xe mới</a>
                <td colspan="7">Không có dữ liệu</td>
            </tr>
        <?php } ?>
    </table>
    <div class="btn-container">
        <a href="formthemxe.php" class="btn btn-add">Thêm xe mới</a>
        <a href="admin_dashboard.php" class="btn btn-back">Quay về Dashboard</a>
    </div>
</body>

</html>