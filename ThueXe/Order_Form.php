<?php
session_start();
require_once __DIR__ . '/../config.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
$is_logged_in = isset($_SESSION['user_id']);

// Nếu đã đăng nhập, lấy thông tin người dùng
if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT ho_ten FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $user_name = $user['ho_ten'];
}

// Hiển thị thông báo thành công nếu có
if (isset($_SESSION['success'])) {
    echo "<p class='success'>{$_SESSION['success']}</p>";
    unset($_SESSION['success']);
}
if (isset($_GET['id'])) {
    $xe_id = $_GET['id'];

    // Truy vấn thông tin xe
    $sql = "SELECT xe.*, hang_xe.ten_hang_xe, dong_xe.ten_dong_xe 
            FROM xe 
            JOIN hang_xe ON xe.hang_xe_id = hang_xe.id 
            JOIN dong_xe ON xe.dong_xe_id = dong_xe.id 
            WHERE xe.id = $xe_id";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $car = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy thông tin xe!";
        exit();
    }

    // Truy vấn ảnh xe
    $sql_images = "SELECT url_anh FROM anh_xe WHERE xe_id = $xe_id";
    $result_images = $conn->query($sql_images);
    $images = [];
    while ($row = $result_images->fetch_assoc()) {
        $images[] = $row['url_anh'];
    }
} else {
    echo "Không có ID xe!";
    exit();
}
?>
<html>

<head>
    <title>
        CaR88 Vietnam
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="Css/Order.css">
    <script src="script.js"></script>
</head>

<body>
    <div class="header">
        <div class="logo">
            <a href="../homepage.php" title="CaR88 Vietnam">
                <svg xmlns="http://www.w3.org/2000/svg" width="150" height="40" viewBox="0 0 150 40">
                    <rect x="0" y="0" width="150" height="40" rx="5" ry="5" fill="#ff7f00" />
                    <text x="10" y="28" font-family="Arial, sans-serif" font-size="22" font-weight="bold"
                        fill="#ffffff"> CaR88 </text>
                    <circle cx="130" cy="20" r="8" fill="#ffffff" />
                    <circle cx="130" cy="20" r="3" fill="#ff7f00" />
                </svg>
            </a>
        </div>
        <div class="nav">
            <a href="#">
                Mua xe
            </a>
            <a href="#">
                Bán xe
            </a>
            <a href="ThueXe.php">
                Thuê xe
            </a>
            <a href="#">
                Giới thiệu
            </a>
            <a href="#">
                Tin tức
            </a>
            <a href="#">
                Vay mua xe
            </a>
        </div>
        <div class="actions">
            <button class="btn">
                ĐĂNG TIN
            </button>
            <div class="contact">
                <i class="fas fa-phone-alt">
                </i>
                <span>
                    0835886837
                </span>
            </div>
            <div class="account">
                <div class="account-dropdown">
                    <button class="account-btn">
                        <i class="fas fa-user"></i>
                        <span><?php echo $is_logged_in ? $user_name : 'Tài khoản'; ?></span>
                    </button>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['user_role'] == 1): ?>
                                <a href="admin_dashboard.php">Admin Dashboard</a>
                            <?php endif; ?>
                            <a href="logout.php">Đăng xuất</a>
                        <?php else: ?>
                            <a href="../login.php">Đăng nhập</a>
                            <a href="../register.php">Đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main">
        <div class="left">
            <img alt="Toyota Vios MT" height="200" src="../uploads/<?php echo $images[0]; ?>" width="300" />
            <h2>
                <?php echo $car['ten_hang_xe'] . ' ' . $car['ten_dong_xe']; ?>
            </h2>
            <ul>
                <li>
                    <i class="fas fa-gas-pump"></i>
                    <span><?php echo $car['nhien_lieu']; ?></span>
                </li>
                <!--<li>
                    <i class="fas fa-car" style="color: #FF9000;"></i>
                    <span>1.5L</span>
                </li>*/-->
                <li>
                    <i class="fas fa-cogs"></i>
                    <span>Số sàn</span>
                </li>
                <li>
                    <i class="fas fa-calendar-alt"></i>
                    <span><?php echo $car['nam_san_xuat']; ?></span>
                </li>
                <li>
                    <i class="fas fa-road"></i>
                    <span><?php echo $car['odo']; ?> Km</span>
                </li>
            </ul>
            <div class="price_order">
                <div class="container">
                    <h2>CHI TIẾT GIÁ</h2>
                    <hr>
                    <p>Đơn giá <span><?php echo  number_format($car['gia'])?></span></p>
                    <p>Thời gian thuê <span>× 1 ngày</span></p>
                    <hr>
                    <p>Giá cơ bản <span>600.000 đ</span></p>
                    <p>Dịch vụ tùy chọn</p>
                    <hr>
                    <p>Tổng <span>600.000 đ</span></p>
                </div>
            </div>
        </div>
        <div class="right">
            <h3>
                THÔNG TIN KHÁCH HÀNG
            </h3>
            <form>
                <input placeholder="Nhập họ tên" type="text" />
                <input placeholder="Nhập số điện thoại" type="text" />
                <input placeholder="Nhập email" type="email" />
                <textarea placeholder="Ghi chú của khách hàng"></textarea>
                <div class="payment-method">
                    <label>
                        <input name="payment" type="radio" value="prepay" />
                        Trả trước
                    </label>
                    <label>
                        <input checked="" name="payment" type="radio" value="postpay" />
                        Trả sau
                    </label>
                    <label>
                        <input name="payment" type="radio" value="atm" />
                        Thẻ ATM nội địa
                    </label>
                    <label>
                        <input name="payment" type="radio" value="visa" />
                        VISA/ Master Card (Thẻ phát hành tại Việt Nam)
                    </label>
                    <label>
                        <input name="payment" type="radio" value="vnpay" />
                        VNPAY
                    </label>
                    <label>
                        <input name="payment" type="radio" value="bank" />
                        Chuyển khoản ngân hàng
                    </label>
                    <label>
                        <input name="payment" type="radio" value="later" />
                        Thanh toán sau
                    </label>
                </div>
                <input placeholder="Nhập mã giảm giá" type="text" />
                <div class="buttons">
                    <button type="submit">
                        Hoàn tất đặt xe
                    </button>
                    <button type="button">
                        Quay lại
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section about">
            <h3>Về CaR88</h3>
            <p>CaR88 là nền tảng mua bán xe hơi trực tuyến hàng đầu tại Việt Nam. Chúng tôi cung cấp dịch vụ đáng tin
                cậy và thuận tiện cho người mua và bán xe.</p>
            <div class="contact">
                <span><i class="fas fa-phone"></i> &nbsp; 0835886837</span>
                <span><i class="fas fa-envelope"></i> &nbsp; info@car88.com</span>
            </div>
            <div class="socials">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
        <div class="footer-section links">
            <h3>Liên kết nhanh</h3>
            <ul>
                <li><a href="#">Trang chủ</a></li>
                <li><a href="#">Mua xe</a></li>
                <li><a href="#">Bán xe</a></li>
                <li><a href="#">Tin tức</a></li>
                <li><a href="#">Liên hệ</a></li>
            </ul>
        </div>
        <div class="footer-section contact-form">
            <h3>Liên hệ với chúng tôi</h3>
            <form action="#" method="post">
                <input type="email" name="email" class="text-input contact-input" placeholder="Email của bạn...">
                <textarea name="message" class="text-input contact-input" placeholder="Tin nhắn của bạn..."></textarea>
                <button type="submit" class="btn btn-big contact-btn">
                    <i class="fas fa-envelope"></i>
                    Gửi
                </button>
            </form>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2023 CaR88.com | Thiết kế bởi Nhóm 9
    </div>
</footer>

</html>