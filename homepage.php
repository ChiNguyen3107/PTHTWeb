<?php
session_start();
require_once 'config.php';

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
    echo "
<p class='success'>{$_SESSION['success']}</p>";
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title> CaR88 Vietnam </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
            <a href="#"> Mua xe </a>
            <a href="#"> Bán xe </a>
            <a href="#"> Giới thiệu </a>
            <a href="#"> Tin tức </a>
            <a href="#"> Vay mua xe </a>
        </div>
        <div class="actions">
            <button class="btn"> ĐĂNG TIN </button>
            <div class="contact">
                <i class="fas fa-phone-alt"></i>
                <span> 0835886837 </span>
            </div>
            <div class="account">
                <div class="account-dropdown">
                    <button class="account-btn">
                        <i class="fas fa-user"></i>
                        <span> <?php echo $is_logged_in ? $user_name : 'Tài khoản'; ?> </span>
                    </button>
                    <div class="dropdown-content"> <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['user_role'] == 1): ?> <a href="admin_dashboard.php">Admin Dashboard</a>
                            <?php endif; ?> <a href="logout.php">Đăng xuất</a> <?php else: ?> <a href="login.php">Đăng
                                nhập</a>
                            <a href="register.php">Đăng ký</a> <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="banner">
        <img src="image/banner1.jpg" alt="banner1">
        <img src="image/banner2.jpg" alt="banner2">
        <img src="image/banner3.jpg" alt="banner3">
        <img src="image/banner4.jpg" alt="banner4">
    </div>
    <div class="main">
        <div class="sidebar">
            <form id="filterForm" method="POST" onsubmit="return false;">
                <h3>BỘ LỌC</h3>

                <div class="filter-section">
                    <h4>HÃNG XE</h4>
                    <select id="hang_xe" name="hang_xe" class="filter-select">
                        <option value="">Tất cả hãng xe</option>
                        <?php
                        $sql_hang_xe = "SELECT * FROM hang_xe ORDER BY ten_hang_xe";
                        $result_hang_xe = $conn->query($sql_hang_xe);
                        while ($row_hang_xe = $result_hang_xe->fetch_assoc()) {
                            echo '<option value="' . $row_hang_xe['id'] . '">' . $row_hang_xe['ten_hang_xe'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="filter-section">
                    <h4>GIÁ (TRIỆU ĐỒNG)</h4>
                    <div class="price-inputs">
                        <input type="number" name="gia_min" placeholder="Từ" min="0">
                        <span>-</span>
                        <input type="number" name="gia_max" placeholder="Đến" min="0">
                    </div>
                </div>

                <div class="filter-section">
                    <h4>NĂM SẢN XUẤT</h4>
                    <div class="year-inputs">
                        <input type="number" name="nam_san_xuat_min" placeholder="Từ" min="1900" max="2024">
                        <span>-</span>
                        <input type="number" name="nam_san_xuat_max" placeholder="Đến" min="1900" max="2024">
                    </div>
                </div>

                <div class="filter-section">
                    <h4>SỐ KM ĐÃ ĐI</h4>
                    <input type="number" name="km_max" placeholder="Tối đa" min="0">
                </div>

                <div class="filter-section">
                    <h4>KIỂU DÁNG</h4>
                    <select name="kieu_dang">
                        <option value="">Tất cả</option>
                        <option value="Sedan">Sedan</option>
                        <option value="SUV">SUV</option>
                        <option value="Hatchback">Hatchback</option>
                        <option value="MPV">MPV</option>
                        <option value="Crossover">Crossover</option>
                    </select>
                </div>

                <div class="filter-section">
                    <h4>NHIÊN LIỆU</h4>
                    <select name="nhien_lieu">
                        <option value="">Tất cả</option>
                        <option value="Xăng">Xăng</option>
                        <option value="Dầu">Dầu</option>
                        <option value="Điện">Điện</option>
                        <option value="Hybrid">Hybrid</option>
                    </select>
                </div>

                <div class="filter-section">
                    <h4>HỘP SỐ</h4>
                    <select name="hop_so">
                        <option value="">Tất cả</option>
                        <option value="Số tự động">Số tự động</option>
                        <option value="Số sàn">Số sàn</option>
                        <option value="Số bán tự động">Số bán tự động</option>
                    </select>
                </div>

                <div class="filter-section">
                    <h4>SỐ CHỖ NGỒI</h4>
                    <select name="so_ghe_ngoi">
                        <option value="">Tất cả</option>
                        <option value="4">4 chỗ</option>
                        <option value="5">5 chỗ</option>
                        <option value="7">7 chỗ</option>
                        <option value="9">9 chỗ</option>
                    </select>
                </div>

                <div class="filter-buttons">
                    <button type="button" class="btn-filter" onclick="filterCars()">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                    <button type="button" class="btn-reset" onclick="resetFilter()">
                        <i class="fas fa-redo"></i> Đặt lại
                    </button>
                </div>
            </form>
        </div>
        <div class="content">
            <h2> Mua bán ô tô cũ toàn quốc </h2>
            <p> Có 1088 tin bán xe giá từ 103 triệu đến 10 tỷ 390 triệu cập nhập mới nhất 10/2024 </p>
            <div class="search-bar">
                <form id="searchForm" onsubmit="return false;">
                    <input id="searchInput" placeholder="Tìm kiếm xe theo Hãng xe, Dòng xe hoặc Từ khóa" type="text" />
                    <button onclick="searchCars()">Tìm kiếm</button>
                </form>
            </div>
            <div class="sort">
                <div class="sort-by">
                    <span> SẮP XẾP: </span>
                    <select id="sort-select">
                        <option value="default">Mặc định</option>
                        <option value="price-asc">Giá: Thấp đến Cao</option>
                        <option value="price-desc">Giá: Cao đến Thấp</option>
                        <option value="km-asc">ODO: Thấp đến Cao</option>
                        <option value="km-desc">ODO: Cao đến Thấp</option>
                    </select>
                </div>
            </div>
            <div class="listings">
                <?php
                // Truy vấn để lấy thông tin xe, hãng xe, dòng xe và ảnh
                $sql = "SELECT xe.*, hang_xe.ten_hang_xe as hang_xe, dong_xe.ten_dong_xe as dong_xe, GROUP_CONCAT(anh_xe.url_anh) as all_images
            FROM xe
            LEFT JOIN anh_xe ON xe.id = anh_xe.xe_id
            LEFT JOIN hang_xe ON xe.hang_xe_id = hang_xe.id
            LEFT JOIN dong_xe ON xe.dong_xe_id = dong_xe.id
            GROUP BY xe.id
            LIMIT 10";

                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $images = explode(',', $row["all_images"]); // Chuyển các ảnh thành mảng
                
                        echo '<div class="listing">';
                        echo '<div class="image-container">';

                        // Hiển thị các ảnh của xe
                        foreach ($images as $index => $image) {
                            echo '<img src="uploads/' . $image . '" alt="' . $row["hang_xe"] . ' ' . $row["dong_xe"] . ' " ' . ($index == 0 ? 'class="active"' : '') . ' />';
                        }

                        // Nút điều hướng ảnh
                        echo '<button class="prev-btn">&#10094;</button>';
                        echo '<button class="next-btn">&#10095;</button>';
                        echo '</div>'; // Kết thúc image-container
                
                        // Hiển thị thông tin xe
                        echo '<div class="details">';
                        echo '<h3>' . $row["hang_xe"] . ' ' . $row["dong_xe"] . ' ' . $row["phien_ban"] . '</h3>';
                        echo '<div class="info-grid">';

                        echo '<div class="info-item">
                    <i class="fas fa-calendar-alt"></i> ' . $row["nam_san_xuat"] . '
                  </div>';
                        echo '<div class="info-item">
                    <i class="fas fa-tachometer-alt"></i> ' . number_format($row["odo"]) . ' km
                  </div>';
                        echo '<div class="info-item">
                    <i class="fas fa-gas-pump"></i> ' . $row["nhien_lieu"] . '
                  </div>';
                        echo '<div class="info-item">
                    <i class="fas fa-cogs"></i> ' . $row["hop_so"] . '
                  </div>';

                        echo '</div>'; // Kết thúc info-grid
                        echo '<div class="price">
                    <i class="fas fa-tag"></i> ' . number_format($row["gia"]) . ' VNĐ
                  </div>';
                        echo '<a href="car_detail.php?id=' . $row['id'] . '">Xem chi tiết</a>'; // Thêm liên kết đến trang chi tiết
                        echo '</div>'; // Kết thúc details
                        echo '</div>'; // Kết thúc listing
                    }
                } else {
                    echo "Không có xe nào được tìm thấy";
                }
                ?>
            </div>

        </div>
    </div>

    <script src="script.js"></script>

</body>
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section about">
            <h3>Về CaR88</h3>
            <p>CaR88 là nền tảng mua bán xe hơi trực tuyến hàng đầu tại Việt Nam. Chúng tôi cung cấp dịch vụ đáng tin
                cậy và thuận tiện cho người mua và bán xe.</p>
            <div class="contact">
                <span>
                    <i class="fas fa-phone"></i> &nbsp; 0835886837 </span>
                <span>
                    <i class="fas fa-envelope"></i> &nbsp; info@car88.com </span>
            </div>
            <div class="socials">
                <a href="#">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
        <div class="footer-section links">
            <h3>Liên kết nhanh</h3>
            <ul>
                <li>
                    <a href="#">Trang chủ</a>
                </li>
                <li>
                    <a href="#">Mua xe</a>
                </li>
                <li>
                    <a href="#">Bán xe</a>
                </li>
                <li>
                    <a href="#">Tin tức</a>
                </li>
                <li>
                    <a href="#">Liên hệ</a>
                </li>
            </ul>
        </div>
        <div class="footer-section contact-form">
            <h3>Liên hệ với chúng tôi</h3>
            <form action="#" method="post">
                <input type="email" name="email" class="text-input contact-input" placeholder="Email của bạn...">
                <textarea name="message" class="text-input contact-input" placeholder="Tin nhắn của bạn..."></textarea>
                <button type="submit" class="btn btn-big contact-btn">
                    <i class="fas fa-envelope"></i> Gửi </button>
            </form>
        </div>
    </div>
    <div class="footer-bottom"> &copy; 2024 CaR88 | Thiết kế bởi Nhóm </div>
</footer>

</html>
<?php
$conn->close();
?>