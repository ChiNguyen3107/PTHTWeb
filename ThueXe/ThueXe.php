<?php

use LDAP\Result;

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
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        CaR88 Vietnam
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tiny-date-picker/3.2.8/tiny-date-picker.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tiny-time-picker/2.3.4/tiny-time-picker.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-date-picker/3.2.8/tiny-date-picker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-time-picker/2.3.4/tiny-time-picker.min.js"></script>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <link rel="stylesheet" href="styles.css">
    <script src="../script.js"></script>
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
                        <span>
                            <?php echo $is_logged_in ? $user_name : 'Tài khoản'; ?>
                        </span>
                    </button>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['user_role'] == 1): ?>
                                <a href="../admin_dashboard.php">Admin Dashboard</a>
                            <?php endif; ?>
                            <a href="../logout.php">Đăng xuất</a>
                        <?php else: ?>
                            <a href="../login.php">Đăng nhập</a>
                            <a href="../register.php">Đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="banner">

        <img src="../image/banner1.jpg" alt="banner1">
        <img src="../image/banner2.jpg" alt="banner2">
        <img src="../image/banner3.jpg" alt="banner3">
        <img src="../image/banner4.jpg" alt="banner4">
    </div>

    <script>
        $(function () {
            // Khởi tạo Flatpickr cho Date Picker
            flatpickr("#pickup-date", {
                dateFormat: "d/m/Y",
                minDate: "today" // Định dạng ngày thành dd/mm/yyyy
            });

            flatpickr("#return-date", {
                dateFormat: "d/m/Y",
                minDate: "today" // Định dạng ngày thành dd/mm/yyyy
            });

            // Khởi tạo Flatpickr cho Time Picker
            flatpickr("#pickup-time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i", // Định dạng thời gian thành HH:mm
                time_24hr: true
            });

            flatpickr("#return-time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i", // Định dạng thời gian thành HH:mm
                time_24hr: true
            });
        });
    </script>
  
   
    <div class="Searching">
        <form class="form_searching" action="" method="GET">
            <div class="section">
                <div class="label">Ngày nhận xe</div>
                <input type="text" id="pickup-date" name="pickup_date" class="input-field" value=".">
            </div>
            <div class="section">
                <div class="label">Giờ nhận xe</div>
                <input type="text" id="pickup-time" name="pickup_time" class="input-field" value="." require>
            </div>
            <div class="section">
                <div class="label">Ngày trả xe</div>
                <input type="text" id="return-date" name="return_date" class="input-field" value=".">
            </div>
            <div class="section">
                <div class="label">Giờ trả xe</div>
                <input type="text" id="return-time" name="return_time" class="input-field" value="." require>
            </div>
            <div class="button-container">
                <button type="submit" class="button">TÌM XE</button>
            </div>
        </form>
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
                    <button type="button" class="btn-filter" onclick="filterCars(1)">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                    <button type="button" class="btn-reset" onclick="resetFilter()">
                        <i class="fas fa-redo"></i> Đặt lại
                    </button>
                </div>
            </form>
        </div>
        <div class="content">
            <h2>
                Mua bán ô tô cũ toàn quốc
            </h2>
            <p>
                Có 1088 tin bán xe giá từ 103 triệu đến 10 tỷ 390 triệu cập nhập mới nhất 10/2024
            </p>
            <div class="search-bar">
                <input placeholder="Tìm kiếm xe theo Hãng xe, Dòng xe hoặc Từ khóa" type="text" />
                <button>
                    Tìm kiếm
                </button>
            </div>
            <div class="sort">
                <div class="sort-by">
                    <span>
                        SẮP XẾP:
                    </span>
                    <select>
                        <option>
                            Liên quan nhất
                        </option>
                        <option>
                            Giá thấp nhất
                        </option>
                        <option>
                            Giá cao nhất
                        </option>
                    </select>
                </div>
            </div>
            <div class="listings">
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['pickup_date'])) {
                    // Lấy giá trị từ các input đã gửi
                    $pickupDate = $_GET['pickup_date'];
                    $pickupTime = $_GET['pickup_time'];
                    $returnDate = $_GET['return_date'];
                    $returnTime = $_GET['return_time'];
                    if ($pickupDate != "." && $pickupTime != "." && $returnDate != "." && $returnTime != ".") {
                        $pickupDateTime = DateTime::createFromFormat('d/m/Y H:i', "$pickupDate $pickupTime")->format('Y-m-d H:i:s');
                        $returnDateTime = DateTime::createFromFormat('d/m/Y H:i', "$returnDate $returnTime")->format('Y-m-d H:i:s');
                    }

                }
                else{
                    $pickupDate = ".";
                    $pickupTime =".";
                    $returnDate = ".";
                    $returnTime =".";
                }
                // Truy vấn để lấy thông tin xe, hãng xe, dòng xe và ảnh
                $sql_trangthai = "select * from trang_thai";
                $stml_tt = $conn->prepare($sql_trangthai);
                $stml_tt->execute();
                $result_tt = $stml_tt->get_result();
                if ($result_tt->num_rows > 0) {
                    if ($pickupDate != "." && $pickupTime != "." && $returnDate != "." && $returnTime != ".") {
                        $sql = "SELECT xe.*, 
                                            hang_xe.ten_hang_xe AS hang_xe, 
                                            dong_xe.ten_dong_xe AS dong_xe, 
                                            GROUP_CONCAT(anh_xe.url_anh) AS all_images
                                        FROM xe
                                        LEFT JOIN anh_xe ON xe.id = anh_xe.xe_id
                                        LEFT JOIN hang_xe ON xe.hang_xe_id = hang_xe.id
                                        LEFT JOIN dong_xe ON xe.dong_xe_id = dong_xe.id
                                        LEFT JOIN trang_thai ON xe.id = trang_thai.XE_ID
                                        WHERE xe.thue_xe = 1
                                        AND (
                                            trang_thai.XE_ID IS NULL -- Trường hợp xe không có trạng thái
                                            OR (
                                                '$pickupDateTime' NOT BETWEEN trang_thai.TT_NGAYBD AND trang_thai.TT_NGAYKT
                                                AND '$returnDateTime' NOT BETWEEN trang_thai.TT_NGAYBD AND trang_thai.TT_NGAYKT
                                            )
                                        )
                                        GROUP BY xe.id
                                        LIMIT 10;
                                        ";
                    } else {
                        $currentDateTime = new DateTime(); // Tạo đối tượng DateTime với thời gian hiện tại
                        $currentDateTimeFormatted = $currentDateTime->format('Y-m-d H:i:s'); // Chuyển đối tượng DateTime thành chuỗi với định dạng 'Y-m-d H:i:s'
                
                        // Câu SQL
                
                        $sql = "  SELECT xe.*, 
                                   hang_xe.ten_hang_xe AS hang_xe, 
                                   dong_xe.ten_dong_xe AS dong_xe, 
                                   GROUP_CONCAT(anh_xe.url_anh) AS all_images
                            FROM xe
                            LEFT JOIN anh_xe ON xe.id = anh_xe.xe_id
                            LEFT JOIN hang_xe ON xe.hang_xe_id = hang_xe.id
                            LEFT JOIN dong_xe ON xe.dong_xe_id = dong_xe.id
                            LEFT JOIN trang_thai ON xe.id = trang_thai.XE_ID
                            WHERE xe.thue_xe = 1
                            AND (
                                trang_thai.XE_ID IS NULL  -- Trường hợp xe không có trạng thái
                                OR (
                                    trang_thai.TT_NGAYBD <= NOW()-- Ngày bắt đầu trạng thái không sau ngày hiện tại
                                )
                            )
                            GROUP BY xe.id
                            LIMIT 10";
                        ;

                    }
                } else {
                    $sql = "SELECT xe.*, hang_xe.ten_hang_xe as hang_xe, dong_xe.ten_dong_xe as dong_xe, GROUP_CONCAT(anh_xe.url_anh) as all_images
                    FROM xe
                    LEFT JOIN anh_xe ON xe.id = anh_xe.xe_id
                    LEFT JOIN hang_xe ON xe.hang_xe_id = hang_xe.id
                    LEFT JOIN dong_xe ON xe.dong_xe_id = dong_xe.id
                    LEFT JOIN trang_thai ON xe.id = trang_thai.XE_ID
                    WHERE xe.thue_xe = 1
                    GROUP BY xe.id
                    LIMIT 10";
                }



                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    // Ràng buộc các tham số với các giá trị ngày và giờ
                
                    // Thực thi truy vấn
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $images = explode(',', $row["all_images"]); // Chuyển các ảnh thành mảng
                
                            echo '<div class="listing">';
                            echo '<div class="image-container">';

                            // Hiển thị các ảnh của xe
                            foreach ($images as $index => $image) {
                                echo '<img src="../uploads/' . $image . '" alt="' . $row["hang_xe"] . ' ' . $row["dong_xe"] . '" ' . ($index == 0 ? 'class="active"' : '') . ' />';
                            }

                            // Nút điều hướng ảnh
                            echo '<button class="prev-btn">&#10094;</button>';
                            echo '<button class="next-btn">&#10095;</button>';
                            echo '</div>';

                            // Hiển thị thông tin xe
                            echo '<div class="details">';
                            echo '<h3>' . $row["hang_xe"] . ' ' . $row["dong_xe"] . ' ' . $row["phien_ban"] . '</h3>';
                            echo '<div class="info-grid">';
                            echo '<div class="info-item"><i class="fas fa-calendar-alt"></i> ' . $row["nam_san_xuat"] . '</div>';
                            echo '<div class="info-item"><i class="fas fa-tachometer-alt"></i> ' . number_format($row["odo"]) . ' km</div>';
                            echo '<div class="info-item"><i class="fas fa-gas-pump"></i> ' . $row["nhien_lieu"] . '</div>';
                            echo '<div class="info-item"><i class="fas fa-cogs"></i> ' . $row["hop_so"] . '</div>';
                            echo '</div>';
                            echo '<div class="price"><i class="fas fa-tag"></i> ' . number_format($row["gia"]) . ' VNĐ</div>';
                            echo '<a href="Thuexe_car_detail.php?id=' . $row['id'] . '">Xem chi tiết</a>'; // Thêm liên kết đến trang chi tiết
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "Không có xe nào được tìm thấy";
                    }
                }
                ?>
            </div>
        </div>
    </div>


    <script>
        function setupImageSlider() {
            const listings = document.querySelectorAll('.listing');

            listings.forEach(listing => {
                const images = listing.querySelectorAll('.image-container img');
                const prevBtn = listing.querySelector('.prev-btn');
                const nextBtn = listing.querySelector('.next-btn');
                let currentImageIndex = 0;

                prevBtn.addEventListener('click', () => {
                    currentImageIndex--;
                    if (currentImageIndex < 0) {
                        currentImageIndex = images.length - 1;
                    }
                    updateImage(images, currentImageIndex);
                });

                nextBtn.addEventListener('click', () => {
                    currentImageIndex++;
                    if (currentImageIndex >= images.length) {
                        currentImageIndex = 0;
                    }
                    updateImage(images, currentImageIndex);
                });

                function updateImage(images, index) {
                    images.forEach((image, i) => {
                        image.classList.toggle('active', i === index);
                    });
                }
            });
        }

        setupImageSlider();

        function updateValue(id, value) {
            if (id === 'priceValue' && value > 1000) {
                document.getElementById(id).innerText = (value / 1000).toFixed(1) + ' tỷ';
            } else {
                document.getElementById(id).innerText = value + ' triệu';
            }
        }

        function toggleDropdown(element) {
            const ul = element.nextElementSibling;
            ul.style.display = ul.style.display === 'none' || ul.style.display === '' ? 'block' : 'none';
        }
    </script>
    <script src="../script.js"></script>
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
<?php
$conn->close();
?>