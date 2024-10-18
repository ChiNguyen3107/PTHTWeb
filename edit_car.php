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
        header("Location: manage_cars.php");
        exit();
    } else {
        $error = "Có lỗi xảy ra khi cập nhật thông tin xe.";
    }
}

// Lấy danh sách hãng xe và dòng xe
$hang_xe_list = $conn->query("SELECT * FROM hang_xe ");
$dong_xe_list = $conn->query("SELECT * FROM dong_xe");
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật thông tin xe - CaR88</title>
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
            height: 100vh;
        }

        .sidebar {
            width: 200px;
            background-color: #333;
            color: #fff;
            height: 100%;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px 15px;
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
            padding: 15px;
        }

        h1 {
            color: #333;
            font-size: 24px;
        }

        form {
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            /* Đảm bảo padding và border không làm thay đổi kích thước tổng thể */
        }




        input[type="submit"] {
            background-color: #ff7f00;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #e66f00;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <h2>Cập nhật thông tin xe</h2>
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_cars.php"><i class="fas fa-car"></i> Quản lý xe</a></li>
                <li><a href="manage_staff.php"><i class="fas fa-user-tie"></i> Quản lý nhân viên</a></li>
                <li><a href="manage_users.php"><i class="fas fa-users"></i> Quản lý người dùng</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Cập nhật thông tin xe</h1>
            <?php if (isset($error)) { ?>
                <p style="color: red;"><?= $error ?></p>
            <?php } ?>
            <form action="" method="post">
                <label for="hang_xe_id">Hãng xe:</label>
                <select name="hang_xe_id" id="hang_xe_id">
                    <?php while ($row = $hang_xe_list->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>" <?= $car['hang_xe_id'] == $row['id'] ? 'selected' : '' ?>>
                            <?= $row['ten_hang_xe'] ?>
                        </option>
                    <?php } ?>
                </select>
                <br><br>
                <label for="dong_xe_id">Dòng xe:</label>
                <select name="dong_xe_id" id="dong_xe_id">
                    <?php while ($row = $dong_xe_list->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>" <?= $car['dong_xe_id'] == $row['id'] ? 'selected' : '' ?>>
                            <?= $row['ten_dong_xe'] ?>
                        </option>
                    <?php } ?>
                </select>
                <br><br>
                <label for="phien_ban">Phiên bản:</label>
                <input type="text" name="phien_ban" id="phien_ban" value="<?= $car['phien_ban'] ?>">
                <br><br>
                <label for="nam_san_xuat">Năm sản xuất:</label>
                <input type="number" name="nam_san_xuat" id="nam_san_xuat" value="<?= $car['nam_san_xuat'] ?>">
                <br><br>
                <label for="kieu_dang">Kiểu dáng:</label>
                <input type="text" name="kieu_dang" id="kieu_dang" value="<?= $car['kieu_dang'] ?>">
                <br><br>
                <label for="xuat_xu">Xuất xứ:</label>
                <input type="text" name="xuat_xu" id="xuat_xu" value="<?= $car['xuat_xu'] ?>">
                <br><br>
                <label for="so_ghe_ngoi">Số ghế ngồi:</label>
                <input type="number" name="so_ghe_ngoi" id="so_ghe_ngoi" value="<?= $car['so_ghe_ngoi'] ?>">
                <br><br>
                <label for="odo">Odo:</label>
                <input type="number" name="odo" id="odo" value="<?= $car['odo'] ?>">
                <br><br>
                <label for="nhien_lieu">Nhiên liệu:</label>
                <select id="nhien_lieu" name="nhien_lieu">
                    <option value="">Chọn nhiên liệu</option>
                    <option value="Xăng" <?php echo ($car['nhien_lieu'] == 'Xăng') ? 'selected' : ''; ?>>Xăng</option>
                    <option value="Dầu" <?php echo ($car['nhien_lieu'] == 'Dầu') ? 'selected' : ''; ?>>Dầu</option>
                    <option value="Điện" <?php echo ($car['nhien_lieu'] == 'Điện') ? 'selected' : ''; ?>>Điện</option>
                    <option value="Hybrid" <?php echo ($car['nhien_lieu'] == 'Hybrid') ? 'selected' : ''; ?>>Hybrid
                    </option>
                </select>

                <label for="hop_so">Hộp số:</label>
                <select id="hop_so" name="hop_so">
                    <option value="">Chọn hộp số</option>
                    <option value="Số tự động" <?= ($car['hop_so'] == 'Số tự động') ? 'selected' : ''; ?>>Số tự động
                    </option>
                    <option value="Số sàn" <?= ($car['hop_so'] == 'Số sàn') ? 'selected' : ''; ?>>Số sàn</option>
                    <option value="Số bán tự động" <?= ($car['hop_so'] == 'Số bán tự động') ? 'selected' : ''; ?>>Số bán tự
                        động</option>
                </select>

                <label for="gia">Giá:</label>
                <input type="number" name="gia" id="gia" value="<?= $car['gia'] ?>">
                <br><br>
                <label for="mo_ta">Mô tả:</label>
                <textarea name="mo_ta" id="mo_ta"><?= $car['mo_ta'] ?></textarea>
                <br><br>
                <input type="submit" value="Cập nhật">
            </form>
        </div>
    </div>
</body>

</html>