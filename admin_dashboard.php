<?php
session_start();
require_once 'config.php';

// Kiểm tra xem người dùng đã đăng nhập và có quyền admin không
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: homepage.php");
    exit();
}

// Hàm đếm số lượng xe
function countCars($conn)
{
    $result = $conn->query("SELECT COUNT(*) as total FROM xe");
    return $result->fetch_assoc()['total'];
}

// Hàm đếm số lượng nhân viên
function countStaff($conn)
{
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role_id = 2"); // Giả sử role_id 2 là nhân viên
    return $result->fetch_assoc()['total'];
}

// Hàm đếm số lượng người dùng (không bao gồm admin và nhân viên)
function countUsers($conn)
{
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .sidebar {
            height: 100vh;
            background-color: #343a40;
        }

        .sidebar a {
            color: #fff;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .card {
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <h2 class="text-center text-white mb-4">Admin Dashboard</h2>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_cars.php">
                                <i class="fas fa-car me-2"></i>
                                Quản lý xe
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_staff.php">
                                <i class="fas fa-user-tie me-2"></i>
                                Quản lý nhân viên
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_users.php">
                                <i class="fas fa-users me-2"></i>
                                Quản lý người dùng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="homepage.php">
                                <i class="fas fa-home me-2"></i>
                                Quay về trang chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h5 class="card-title">Số lượng xe</h5>
                                <p class="card-text display-4"><?= $totalCars ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5 class="card-title">Số lượng nhân viên</h5>
                                <p class="card-text display-4"><?= $totalStaff ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h5 class="card-title">Số lượng người dùng</h5>
                                <p class="card-text display-4"><?= $totalUsers ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>