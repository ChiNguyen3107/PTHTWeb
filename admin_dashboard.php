<?php
session_start();
require_once 'config.php';

// Kiểm tra xem người dùng đã đăng nhập và có quyền admin không
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: homepage.php");
    exit();
}

// Hàm đếm số lượng xe
function countCars($conn) {
    $result = $conn->query("SELECT COUNT(*) as total FROM xe");
    return $result->fetch_assoc()['total'];
}

// Hàm đếm số lượng nhân viên
function countStaff($conn) {
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role_id = 2"); // Giả sử role_id 2 là nhân viên
    return $result->fetch_assoc()['total'];
}

// Hàm đếm số lượng người dùng (không bao gồm admin và nhân viên)
function countUsers($conn) {
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role_id = 3"); // Giả sử role_id 3 là người dùng thông thường
    return $result->fetch_assoc()['total'];
}

$totalCars = countCars($conn);
$totalStaff = countStaff($conn);
$totalUsers = countUsers($conn);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CaR88</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            height: 100vh;
            padding-top: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px 20px;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
        }
        .sidebar ul li:hover {
            background-color: #444;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .dashboard-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .card {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            width: 30%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card h3 {
            margin-top: 0;
            color: #333;
        }
        .card p {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 0;
            color: #007 bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_cars.php"><i class="fas fa-car"></i> Quản lý xe</a></li>
                <li><a href="manage_staff.php"><i class="fas fa-user-tie"></i> Quản lý nhân viên</a></li>
                <li><a href="manage_users.php"><i class="fas fa-users"></i> Quản lý người dùng</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Dashboard</h1>
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Số lượng xe</h3>
                    <p><?= $totalCars ?></p>
                </div>
                <div class="card">
                    <h3>Số lượng nhân viên</h3>
                    <p><?= $totalStaff ?></p>
                </div>
                <div class="card">
                    <h3>Số lượng người dùng</h3>
                    <p><?= $totalUsers ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>