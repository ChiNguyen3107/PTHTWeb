<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        // Kiểm tra email đã tồn tại chưa
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email đã tồn tại!";
        } else {
            // Thêm tài khoản vào cơ sở dữ liệu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (email, ho_ten, so_dien_thoai, ngay_sinh, dia_chi, password, role_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $email, $ho_ten, $so_dien_thoai, $ngay_sinh, $dia_chi, $hashed_password, $role_id);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Đăng ký thành công!";
                header("Location: homepage.php");
                exit();
            } else {
                $error = "Lỗi: " . $stmt->error;
            }
        }
    }
}

// Lấy danh sách vai trò
$roles = $conn->query("SELECT * FROM roles WHERE role_name != 'admin'")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .error {
            color: #dc0e0e;
            /* Màu đỏ cho thông báo lỗi */
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="email"],
        input[type="text"],
        input[type="tel"],
        input[type="date"],
        input[type="password"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
            box-sizing: border-box;

        }

        input[type="email"]:focus,
        input[type="text"]:focus,
        input[type="tel"]:focus,
        input[type="date"]:focus,
        input[type="password"]:focus,
        textarea:focus,
        select:focus {
            border-color: #4CAF50;
            /* Màu xanh lá cây khi focus */
            outline: none;
        }

        button {
            background-color: #ff7f00;
            /* Màu nền cho nút */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            /* Chiều rộng nút chiếm toàn bộ */
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #ff9000;
            /* Màu nền khi hover */
        }

        p {
            text-align: center;
            /* Căn giữa các đoạn văn */
            margin-top: 15px;
        }

        a {
            color: #ff7f00;
            /* Màu cho liên kết */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
            /* Gạch chân khi hover */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Đăng ký tài khoản</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="ho_ten">Họ tên:</label>
                <input type="text" id="ho_ten" name="ho_ten" required>
            </div>
            <div class="form-group">
                <label for="so_dien_thoai">Số điện thoại:</label>
                <input type="tel" id="so_dien_thoai" name="so_dien_thoai" required>
            </div>
            <div class="form-group">
                <label for="ngay_sinh">Ngày sinh:</label>
                <input type="date" id="ngay_sinh" name="ngay_sinh" required>
            </div>
            <div class="form-group">
                <label for="dia_chi">Địa chỉ:</label>
                <textarea id="dia_chi" name="dia_chi"></textarea>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="role_id">Vai trò:</label>
                <select id="role_id" name="role_id" required>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo $role['id']; ?>"><?php echo $role['role_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Đăng ký</button>
        </form>
        <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </div>
</body>

</html>