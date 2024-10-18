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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            display: inline-block;
            padding: 5px 10px;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
        }
        .btn-edit {
            background-color: #007bff;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .btn-add {
            background-color: #28a745;
            padding: 10px 20px;
            font-size: 16px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Quản lý xe</h1>
    <a href="formthemxe.php" class="btn btn-add">Thêm xe mới</a>
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
            <td><?= $row['ten_hang_xe'] ?></td> <td><?= $row['ten_dong_xe'] ?></td>
            <td><?= $row['phien_ban'] ?></td>
            <td><?= $row['nam_san_xuat'] ?></td>
            <td><?= number_format($row['gia'], 0, ',', '.') ?> VNĐ</td>
            <td>
                <a href="edit_car.php?id=<?= $row['id'] ?>" class="btn btn-edit">Sửa</a>
                <a href="manage_cars.php?delete=<?= $row['id'] ?>" class="btn btn-delete">Xóa</a>
            </td>
        </tr>
        <?php } } else { ?>
        <tr>
            <td colspan="7">Không có dữ liệu</td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>