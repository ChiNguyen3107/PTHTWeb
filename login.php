<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, email, password, ho_ten, role_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Đăng nhập thành công
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['ho_ten'];
            $_SESSION['user_role'] = $user['role_id'];

            $_SESSION['success'] = "Đăng nhập thành công!";
            header("Location: homepage.php");
            exit();
        } else {
            $error = "Sai email hoặc mật khẩu";
        }
    } else {
        $error = "Sai email hoặc mật khẩu";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
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
        input[type="password"],
        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            /* Đảm bảo padding và border không làm thay đổi kích thước tổng thể */
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #ff7f00;
            /* Màu cam khi focus */
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
        <h2>Đăng nhập</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
        <p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
    </div>
</body>

</html>