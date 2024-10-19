<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pthtweb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$is_logged_in = isset($_SESSION['user_id']);
// Lấy ID xe từ URL
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



<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Xe - <?php echo $car['ten_hang_xe'] . ' ' . $car['ten_dong_xe']; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <style>
        .car-detail {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .car-images {
            flex: 1;
            min-width: 300px;
            margin-right: 20px;
        }

        .car-images img {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
            cursor: pointer;
        }

        #main-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .thumbnails {
            display: flex;
            gap: 10px;
            overflow-x: auto;
        }

        .thumbnails img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .thumbnails img:hover {
            opacity: 0.7;
        }

        .car-info {
            flex: 1;
            min-width: 300px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .car-info h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .car-info h2 {
            color: #ff7f00;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .car-info table {
            width: 100%;
            margin-bottom: 20px;
        }

        .car-info table td {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .car-info table td:first-child {
            font-weight: bold;
            color: #666;
            width: 40%;
        }

        /* Khung với đổ bóng cho car-container và car-description */
        .car-container,
        .car-description {
            flex: 1;
            margin: 10px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            /* Thêm thuộc tính flex */
        }

        /* Đặt car-container và car-description nằm ngang */
        .car-container {
            flex-direction: column;
            /* Có thể thay đổi tùy theo nội dung */
        }

        .car-details,
        .car-description {
            flex: 1;
        }

        /* Các thành phần bên trong car-container và car-description */
        .car-details h3,
        .car-description h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .car-details table {
            width: 100%;
        }

        .car-details table th,
        .car-details table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .car-details table th {
            text-align: left;
            color: #666;
            width: 30%;
        }

        .car-description p {
            line-height: 1.6;
            color: #666;
        }

        /* Nút gọi và nhắn tin */
        .button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #ff7f00;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #e67300;
        }

        /* Biểu tượng cho các thông tin xe */
        .car-info table td:first-child::before {
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-right: 10px;
            color: #ff7f00;
        }

        .car-info table tr:nth-child(1) td:first-child::before {
            content: "\f1b9";
            /* Biểu tượng xe */
        }

        .car-info table tr:nth-child(2) td:first-child::before {
            content: "\f52f";
            /* Biểu tượng động cơ */
        }

        .car-info table tr:nth-child(3) td:first-child::before {
            content: "\f018";
            /* Biểu tượng nhiên liệu */
        }

        .car-info table tr:nth-child(4) td:first-child::before {
            content: "\f0c0";
            /* Biểu tượng số ghế */
        }

        .car-info table tr:nth-child(5) td:first-child::before {
            content: "\f013";
            /* Biểu tượng năm sản xuất */
        }

        .car-info table tr:nth-child(6) td:first-child::before {
            content: "\f3c5";
            /* Biểu tượng kiểu dáng */
        }

        /* Hiệu ứng hover cho các phần tử */
        .car-details table tr:hover {
            background-color: #f5f5f5;
            transition: background-color 0.3s ease;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .car-detail {
                flex-direction: column;
            }

            .car-info {
                margin-bottom: 20px;
                /* khoảng cách dưới cho car-info */
            }

            .car-container,
            .car-description {
                flex-direction: column;
                /* Đặt dọc trên thiết bị di động */
            }
        }

        /* Thêm animation cho các phần tử khi trang tải */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .car-detail,
        .car-container,
        .car-description {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">
            <a href="homepage.php" title="CaR88 Vietnam">
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
                            <a href="login.php">Đăng nhập</a>
                            <a href="register.php">Đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="car-detail">
        <div class="car-images">
            <?php if (!empty($images)): ?>
                <img id="main-image" src="uploads/<?php echo $images[0]; ?>" alt="Ảnh xe lớn">
                <div class="thumbnails">
                    <?php foreach ($images as $image): ?>
                        <img src="uploads/<?php echo $image; ?>"
                            onclick="document.getElementById('main-image').src = 'uploads/<?php echo $image; ?>'">
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Không có ảnh nào cho xe này.</p>
            <?php endif; ?>
        </div>

        <div class="car-info">
            <h1><?php echo $car['ten_hang_xe'] . ' ' . $car['ten_dong_xe']; ?></h1>
            <h2><i class="fas fa-tag"></i> <?php echo number_format($car['gia'], 0, ',', '.'); ?> VND</h2>

            <table>
                <tr>
                    <td>Năm sản xuất</td>
                    <td><?php echo $car['nam_san_xuat']; ?></td>
                </tr>
                <tr>
                    <td>Nhiên liệu</td>
                    <td><?php echo $car['nhien_lieu']; ?></td>
                </tr>
                <tr>
                    <td>Số KM</td>
                    <td><?php echo number_format($car['odo'], 0, ',', '.'); ?> km</td>
                </tr>
                <tr>
                    <td>Số chỗ ngồi</td>
                    <td><?php echo $car['so_ghe_ngoi']; ?></td>
                </tr>
                <tr>
                    <td>Hộp số</td>
                    <td><?php echo $car['hop_so']; ?></td>
                </tr>
                <tr>
                    <td>Kiểu dáng</td>
                    <td><?php echo $car['kieu_dang']; ?></td>
                </tr>
                <tr>
                    <td>Xuất xứ</td>
                    <td><?php echo $car['xuat_xu']; ?></td>
                </tr>
            </table>

            <a href="tel:0835886837" class="button">Gọi người bán</a>
            <a href="sms:0835886837" class="button">Nhắn người bán</a>
        </div>
    </div>
    <div class="car-container">
        <div class="car-details">
            <h3><i class="fas fa-cogs"></i> Thông số kỹ thuật</h3>
            <table>
                <tr>
                    <th>Hãng xe</th>
                    <td><?php echo $car['ten_hang_xe']; ?></td>
                </tr>
                <tr>
                    <th>Dòng xe</th>
                    <td><?php echo $car['ten_dong_xe']; ?></td>
                </tr>
                <tr>
                    <th>Phiên bản</th>
                    <td><?php echo $car['phien_ban']; ?></td>
                </tr>
                <tr>
                    <th>Năm sản xuất</th>
                    <td><?php echo $car['nam_san_xuat']; ?></td>
                </tr>
                <tr>
                    <th>Kiểu dáng</th>
                    <td><?php echo $car['kieu_dang']; ?></td>
                </tr>
                <tr>
                    <th>Xuất xứ</th>
                    <td><?php echo $car['xuat_xu']; ?></td>
                </tr>
                <tr>
                    <th>Số ghế ngồi</th>
                    <td><?php echo $car['so_ghe_ngoi']; ?></td>
                </tr>
            </table>
        </div>
        <div class="car-description">
        <h3><i class="fas fa-info-circle"></i> Mô tả</h3>
        <p><?php echo nl2br($car['mo_ta']); ?></p>
    </div>
    </div>

    

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section about">
                <h3>Về CaR88</h3>
                <p>CaR88 là nền tảng mua bán xe hơi trực tuyến hàng đầu tại Việt Nam. Chúng tôi cung cấp dịch vụ đáng
                    tin
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
                    <textarea name="message" class="text-input contact-input"
                        placeholder="Tin nhắn của bạn..."></textarea>
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


</body>

</html>