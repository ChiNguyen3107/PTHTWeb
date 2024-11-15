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

// Kiểm tra và lấy thông tin từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $xe_id = $_POST['id'];
    $day = isset($_POST['day']) ? $_POST['day'] : 1;
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $email = $_POST['email'];
    $ghi_chu = $_POST['ghi_chu'];
    $payment_method = $_POST['payment'];
    $ma_giam_gia = $_POST['ma_giam_gia'];

    // Xử lý logic và lưu thông tin hợp đồng nếu cần
    // Ví dụ: lưu thông tin vào cơ sở dữ liệu, tính giá tiền, áp dụng mã giảm giá, v.v.
}
if (!empty($xe_id)) {
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
    <div class="contract">
        <h2>Hợp Đồng Thuê Xe</h2>
        <!-- Thông tin xe -->
        <div class="contract-section">
            <h3>Thông Tin Xe</h3>
            <div class="contract-info">
                <div class="info-item">
                    <span class="info-title">Hãng xe: </span>
                    <span class="info-value"><?php echo $car['ten_hang_xe']; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-title">Dòng xe: </span>
                    <span class="info-value"><?php echo $car['ten_dong_xe']; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-title">Biển số: </span>
                    <span class="info-value"><?php echo $car['bien_so']; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-title">Năm sản xuất: </span>
                    <span class="info-value"><?php echo $car['nam_san_xuat']; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-title">Số km đã đi: </span>
                    <span class="info-value"><?php echo $car['odo']; ?> Km</span>
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="contract-section">
            <h3>Thông Tin Khách Hàng</h3>
            <div class="contract-info">
                <div class="info-item">
                    <span class="info-title">Họ tên: </span>
                    <span class="info-value"><?php echo htmlspecialchars($ho_ten); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-title">Email: </span>
                    <span class="info-value"><?php echo htmlspecialchars($email); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-title">Số điện thoại: </span>
                    <span class="info-value"><?php echo htmlspecialchars($so_dien_thoai); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-title">Ghi chú: </span>
                    <span class="info-value"><?php echo htmlspecialchars($ghi_chu); ?></span>
                </div>
            </div>
        </div>

        <!-- Phương thức thanh toán -->
        <div class="contract-section">
            <h3>Phương Thức Thanh Toán</h3>
            <div class="contract-info">
                <div class="info-item">
                    <span class="info-title">Phương thức thanh toán: </span>
                    <span class="info-value">
                        <?php
                        switch ($payment_method) {
                            case 'prepay':
                                echo "Trả trước";
                                break;
                            case 'postpay':
                                echo "Trả sau";
                                break;
                            case 'atm':
                                echo "Thẻ ATM nội địa";
                                break;
                            case 'visa':
                                echo "VISA/ Master Card (Thẻ phát hành tại Việt Nam)";
                                break;
                            case 'vnpay':
                                echo "VNPAY";
                                break;
                            case 'bank':
                                echo "Chuyển khoản ngân hàng";
                                break;
                            case 'later':
                                echo "Thanh toán sau";
                                break;
                            default:
                                echo "Không xác định";
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Điều khoản hợp đồng -->

        <div class="contract-section">
            <h3>Điều Khoản Hợp Đồng</h3>
            <div class="contract-info">
                <?php
                // Kiểm tra và nhận giá trị từ GET
                // Mặc định là 1 ngày nếu không có giá trị
                $basePrice = (float)$car['gia'];
                $ma_giam_gia = isset($_GET['ma_giam_gia']) ? htmlspecialchars($_GET['ma_giam_gia']) : '';

                // Tính giá cơ bản và tổng tiền
                $gia_co_ban = $day * $basePrice;
                $tong_tien = $gia_co_ban; // Thêm các dịch vụ tùy chọn nếu có
                ?>

                <div class="info-item">
                    <span class="info-title">Đơn giá: </span>
                    <span class="info-value"><?php echo number_format($basePrice); ?> đ/ngày</span>
                </div>
                <div class="info-item">
                    <span class="info-title">Thời gian thuê: </span>
                    <span class="info-value"><?php echo number_format($day) . ' ngày'; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-title">Giá cơ bản: </span>
                    <span class="info-value"><?php echo number_format($gia_co_ban); ?> đ</span>
                </div>
                <div class="info-item">
                    <span class="info-title">Dịch vụ tùy chọn: </span>
                    <span class="info-value">Không có</span> <!-- Thay đổi tùy theo dịch vụ tùy chọn -->
                </div>
                <div class="info-item">
                    <span class="info-title">Mã giảm giá: </span>
                    <span class="info-value"><?php echo $ma_giam_gia; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-title">Tổng: </span>
                    <span class="info-value"><?php echo number_format($tong_tien); ?> đ</span>
                </div>
            </div>
        </div>
        <div class="contract-section">
            <h3>Chữ Ký</h3>
            <div class="signature">
                <!-- Khu vực Đại diện CaR88 -->
                <div class="signature-item">
                    <p><strong>Đại diện CaR88</strong></p>
                    <!-- Thêm khoảng trống -->
                    <p class="space"></p> <!-- Thêm khoảng trống -->
                </div>

                <!-- Khu vực Người thuê xe -->
                <div class="signature-item">
                    <p><strong>Người thuê xe</strong></p>
                    <!-- Thêm khoảng trống -->
                    <p class="space"></p> <!-- Thêm khoảng trống -->
                </div>
            </div>
        </div>
        <!-- Chữ ký -->

        <a href="in.php?id=<?= $xe_id ?>&day=<?= $day ?>&ma_giam_gia=<?= $ma_giam_gia ?>&ho_ten=<?= urlencode($ho_ten) ?>&so_dien_thoai=<?= urlencode($so_dien_thoai) ?>&email=<?= urlencode($email) ?>&ghi_chu=<?= urlencode($ghi_chu) ?>&payment=<?= urlencode($payment_method) ?>" class="btn btn-print" target="_blank">
            In hợp đồng
        </a>

        <style>
            .btn {
                display: inline-block;
                padding: 10px 20px;
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                color: #fff;
                background-color: #e67e22;
                border: none;
                border-radius: 5px;
                text-decoration: none;
                transition: all 0.3s ease;
            }

            .btn:hover {
                background-color: #d35400;
                transform: translateY(-3px);
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            .btn:active {
                background-color: #c0392b;
                transform: translateY(0);
                box-shadow: none;
            }

            .btn-print {
                font-size: 18px;
            }

            .btn-print:hover {
                background-color: #e67e22;
            }

            .btn-print:active {
                background-color: #c0392b;
            }
        </style>




        <style>
            /* Định dạng cho trang in */
            body {
                font-family: 'Arial', sans-serif;
                line-height: 1.6;
                color: #333;
                margin: 0;
                padding: 10px 30px 10px 30px;
                background-color: #f4f4f4;
            }

            .contract-print {
                max-width: 800px;
                margin: 0 auto;
                padding: 10px;
                border: 1px solid #ddd;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            h2 {
                text-align: center;
                color: #e67e22;
                font-size: medium;
                margin-bottom: 10px;
            }

            h3 {
                color: #e67e22;
                font-size: medium;
                margin-bottom: 10px;
                border-bottom: 1px solid #e67e22;
                padding-bottom: 5px;
            }

            .contract-section {
                margin-bottom: 10px;
                display: inline;

            }



            .info-item {
                margin-bottom: 12px;
                font-size: 16px;
            }

            .info-title {
                font-weight: bold;
                color: #333;
            }

            .info-value {
                color: #555;
                font-style: italic;
            }

            .contract-info {
                margin-bottom: 10px;
                display: inline;
            }

            .signature {
                display: flex;
                justify-content: space-between;
                padding-top: 30px;
                padding-bottom: 30px;
                width: 100%;
                border-top: 2px solid #ddd;
            }

            .signature-item {
                text-align: center;
                width: 45%;
            }

            .signature-item p {
                margin: 5px 0;
                font-size: 16px;
                color: #333;
            }

            .space {
                height: 20px;
            }

            /* Style cho các phần giá và tổng tiền */
            .price-section {
                margin-top: 20px;
                font-size: 18px;
                color: #2c3e50;
            }

            .price-item {
                margin-bottom: 10px;
            }

            .total-price {
                font-weight: bold;
                font-size: 20px;
                color: #e67e22;
            }

            .contract-info .info-item {
                display: flex;
                justify-content: space-between;
            }

            .contract-info .info-title {
                width: 200px;
            }

            .contract-info .info-value {
                flex-grow: 1;
            }
        </style>
</body>
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section about">
            <h3>Về CaR88</h3>
            <p>CaR88 là nền tảng mua bán xe hơi trực tuyến hàng đầu tại Việt Nam. Chúng tôi cung cấp dịch vụ đáng tin cậy và thuận tiện cho người mua và bán xe.</p>
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