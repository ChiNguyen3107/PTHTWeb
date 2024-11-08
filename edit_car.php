<?php
session_start();
require_once 'config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: homepage.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: manage_cars.php");
    exit();
}

// Lấy thông tin xe
$sql = "SELECT xe.*, hang_xe.ten_hang_xe, dong_xe.ten_dong_xe 
        FROM xe 
        JOIN hang_xe ON xe.hang_xe_id = hang_xe.id 
        JOIN dong_xe ON xe.dong_xe_id = dong_xe.id
        WHERE xe.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();

if (!$car) {
    header("Location: manage_cars.php");
    exit();
}

// Xử lý form khi submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hang_xe_id = $_POST['hang_xe_id'];
    $dong_xe_id = $_POST['dong_xe_id'];
    $phien_ban = $_POST['phien_ban'];
    $nam_san_xuat = $_POST['nam_san_xuat'];
    $kieu_dang = $_POST['kieu_dang'];
    $xuat_xu = $_POST['xuat_xu'];
    $so_ghe_ngoi = $_POST['so_ghe_ngoi'];
    $odo = $_POST['odo'];
    $nhien_lieu = $_POST['nhien_lieu'];
    $hop_so = $_POST['hop_so'];
    $gia = $_POST['gia'];
    $mo_ta = $_POST['mo_ta'];

    $sql = "UPDATE xe SET 
    hang_xe_id = ?, dong_xe_id = ?, phien_ban = ?, nam_san_xuat = ?,
    kieu_dang = ?, xuat_xu = ?, so_ghe_ngoi = ?, odo = ?,
    nhien_lieu = ?, hop_so = ?, gia = ?, mo_ta = ?
    WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iisisssissssi",
        $hang_xe_id,
        $dong_xe_id,
        $phien_ban,
        $nam_san_xuat,
        $kieu_dang,
        $xuat_xu,
        $so_ghe_ngoi,
        $odo,
        $nhien_lieu,
        $hop_so,
        $gia,
        $mo_ta,
        $id
    );

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Cập nhật thông tin xe thành công.";
        header("Location: manage_cars.php");
        exit();
    } else {
        $error = "Có lỗi xảy ra khi cập nhật thông tin xe.";
    }
}

// Lấy danh sách hãng xe và dòng xe
$hang_xe_list = $conn->query("SELECT * FROM hang_xe");
// $dong_xe_list = $conn->query("SELECT * FROM dong_xe");
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật thông tin xe - CaR88</title>
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
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="admin_dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="manage_cars.php">
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
                    <h1 class="h2">Cập nhật thông tin xe</h1>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post" class="needs-validation" novalidate>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="hang_xe_id" class="form-label required-field">Hãng xe:</label>
                            <select name="hang_xe_id" id="hang_xe_id" class="form-select" required>
                                <option value="">Chọn hãng xe</option>
                                <?php while ($row = $hang_xe_list->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>" <?= $car['hang_xe_id'] == $row['id'] ? 'selected' : '' ?>>
                                        <?= $row['ten_hang_xe'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="dong_xe_id" class="form-label required-field">Dòng xe:</label>
                            <select name="dong_xe_id" id="dong_xe_id" class="form-select" required>
                                <option value="">Chọn dòng xe</option>
                                <!-- Các option sẽ được load động bằng JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phien_ban" class="form-label">Phiên bản:</label>
                        <input type="text" name="phien_ban" id="phien_ban" class="form-control"
                            value="<?= $car['phien_ban'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nam_san_xuat" class="form-label">Năm sản xuất:</label>
                        <input type="number" name="nam_san_xuat" id="nam_san_xuat" class="form-control"
                            value="<?= $car['nam_san_xuat'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kieu_dang" class="form-label">Kiểu dáng:</label>
                        <input type="text" name="kieu_dang" id="kieu_dang" class="form-control"
                            value="<?= $car['kieu_dang'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="xuat_xu" class="form-label">Xuất xứ:</label>
                        <input type="text" name="xuat_xu" id="xuat_xu" class="form-control"
                            value="<?= $car['xuat_xu'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="so_ghe_ngoi" class="form-label">Số ghế ngồi:</label>
                        <input type="number" name="so_ghe_ngoi" id="so_ghe_ngoi" class="form-control"
                            value="<?= $car['so_ghe_ngoi'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="odo" class="form-label">Odo:</label>
                        <input type="number" name="odo" id="odo" class="form-control" value="<?= $car['odo'] ?>"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="nhien_lieu" class="form-label">Nhiên liệu:</label>
                        <select id="nhien_lieu" name="nhien_lieu" class="form-select" required>
                            <option value="">Chọn nhiên liệu</option>
                            <option value="Xăng" <?= ($car['nhien_lieu'] == 'Xăng') ? 'selected' : ''; ?>>Xăng</option>
                            <option value="Dầu" <?= ($car['nhien_lieu'] == 'Dầu') ? 'selected' : ''; ?>>Dầu</option>
                            <option value="Điện" <?= ($car['nhien_lieu'] == 'Điện') ? 'selected' : ''; ?>>Điện</option>
                            <option value="Hybrid" <?= ($car['nhien_lieu'] == 'Hybrid') ? 'selected' : ''; ?>>Hybrid
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="hop_so" class="form-label">Hộp số:</label>
                        <select id="hop_so" name="hop_so" class="form-select" required>
                            <option value="">Chọn hộp số</option>
                            <option value="Số tự động" <?= ($car['hop_so'] == 'Số tự động') ? 'selected' : ''; ?>>Số tự
                                động</option>
                            <option value="Số sàn" <?= ($car['hop_so'] == 'Số sàn') ? 'selected' : ''; ?>>Số sàn</option>
                            <option value="Số bán tự động" <?= ($car['hop_so'] == 'Số bán tự động') ? 'selected' : ''; ?>>
                                Số bán tự động</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="gia" class="form-label">Giá:</label>
                        <input type="number" name="gia" id="gia" class="form-control" value="<?= $car['gia'] ?>"
                            required>
                    </div>
                    <div ```php <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả:</label>
                        <textarea name="mo_ta" id="mo_ta" class="form-control" required><?= $car['mo_ta'] ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load dòng xe ban đầu cho hãng xe đã chọn
        loadDongXe(<?= $car['hang_xe_id'] ?>, <?= $car['dong_xe_id'] ?>);

        // Thêm event listener cho việc thay đổi hãng xe
        document.getElementById('hang_xe_id').addEventListener('change', function() {
            loadDongXe(this.value);
        });

        function loadDongXe(hangXeId, selectedDongXeId = null) {
            if (hangXeId) {
                fetch('get_dong_xe.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'hang_xe_id=' + hangXeId
                })
                .then(response => response.text())
                .then(data => {
                    const dongXeSelect = document.getElementById('dong_xe_id');
                    dongXeSelect.innerHTML = data;
                    
                    // Nếu có dòng xe đã chọn trước đó, select lại option đó
                    if (selectedDongXeId) {
                        dongXeSelect.value = selectedDongXeId;
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            } else {
                document.getElementById('dong_xe_id').innerHTML = '<option value="">Chọn dòng xe</option>';
            }
        }
    });
    </script>
</body>

</html>