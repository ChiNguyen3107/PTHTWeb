<?php
session_start();
require_once 'config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: homepage.php");
    exit();
}

// Kiểm tra xem có ID người dùng được truyền vào không
if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = $_GET['id'];

// Lấy thông tin người dùng từ CSDL
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Người dùng không tồn tại!";
    exit();
}

$user = $result->fetch_assoc();

// Xử lý khi người dùng cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $dia_chi = $_POST['dia_chi'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role_id = $_POST['role_id'];

    // Kiểm tra mật khẩu và xác nhận mật khẩu
    if ($password !== $confirm_password) {
        $error = "Mật khẩu và xác nhận mật khẩu không khớp!";
    } else {
        // Nếu mật khẩu mới được thay đổi, mã hóa mật khẩu
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            // Nếu mật khẩu không thay đổi, giữ nguyên mật khẩu cũ
            $hashed_password = $user['password'];
        }

        // Cập nhật dữ liệu vào CSDL
        $sql = "UPDATE users SET email = ?, ho_ten = ?, so_dien_thoai = ?, ngay_sinh = ?, dia_chi = ?, password = ?, role_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssii", $email, $ho_ten, $so_dien_thoai, $ngay_sinh, $dia_chi, $hashed_password, $role_id, $user_id);

        if ($stmt->execute()) {
            header("Location: manage_users.php");
            exit();
        } else {
            echo "Có lỗi xảy ra khi cập nhật dữ liệu!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa người dùng - CaR88</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Chỉnh sửa thông tin người dùng</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="ho_ten" class="form-label">Họ Tên</label>
                <input type="text" class="form-control" id="ho_ten" name="ho_ten" value="<?= $user['ho_ten'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="so_dien_thoai" class="form-label">Số Điện Thoại</label>
                <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai" value="<?= $user['so_dien_thoai'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="ngay_sinh" class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh" value="<?= $user['ngay_sinh'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="dia_chi" class="form-label">Địa Chỉ</label>
                <input type="text" class="form-control" id="dia_chi" name="dia_chi" value="<?= $user['dia_chi'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu mới (nếu thay đổi)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>
            <div class="mb-3">
                <label for="role_id" class="form-label">Role ID</label>
                <select class="form-control" id="role_id" name="role_id" required>
                    <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Admin</option>
                    <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>User</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="manage_users.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
