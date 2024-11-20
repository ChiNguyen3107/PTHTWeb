<?php
session_start();
require_once 'config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: homepage.php");
    exit();
}

// Xử lý khi người dùng gửi form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $dia_chi = $_POST['dia_chi'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password']; // Xác nhận mật khẩu
    $role_id = $_POST['role_id'];

    // Kiểm tra mật khẩu và xác nhận mật khẩu
    if ($password !== $confirm_password) {
        $error = "Mật khẩu và xác nhận mật khẩu không khớp!";
    } else {
        // Kiểm tra email đã tồn tại chưa
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email đã tồn tại!";
        } else {
            // Mã hóa mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Thêm người dùng vào cơ sở dữ liệu
            $stmt = $conn->prepare("INSERT INTO users (email, ho_ten, so_dien_thoai, ngay_sinh, dia_chi, password, role_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $email, $ho_ten, $so_dien_thoai, $ngay_sinh, $dia_chi, $hashed_password, $role_id);

            if ($stmt->execute()) {
                header("Location: manage_users.php");
                exit();
            } else {
                $error = "Có lỗi xảy ra khi thêm người dùng!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm người dùng - CaR88</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Thêm Người Dùng Mới</h1>

        <?php if (isset($error)): ?>
            <p class="text-danger"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="ho_ten" class="form-label">Họ Tên</label>
                <input type="text" class="form-control" id="ho_ten" name="ho_ten" required>
            </div>
            <div class="mb-3">
                <label for="so_dien_thoai" class="form-label">Số Điện Thoại</label>
                <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai" required>
            </div>
            <div class="mb-3">
                <label for="ngay_sinh" class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh" required>
            </div>
            <div class="mb-3">
                <label for="dia_chi" class="form-label">Địa Chỉ</label>
                <input type="text" class="form-control" id="dia_chi" name="dia_chi" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="mb-3">
                <label for="role_id" class="form-label">Vai trò</label>
                <select class="form-control" id="role_id" name="role_id" required>
                    <option value="1">Admin</option>
                    <option value="2">User</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Thêm Người Dùng</button>
            <a href="manage_users.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
