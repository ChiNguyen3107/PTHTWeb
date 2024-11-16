<?php
// Kết nối đến cơ sở dữ liệu
session_start();
require_once __DIR__ . '/../config.php';

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
    <title>Chi Tiết Xe -
        <?php echo $car['ten_hang_xe'] . ' ' . $car['ten_dong_xe']; ?>
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="Css/_detail_car.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Thêm jQuery -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-timepicker-addon/dist/jquery-ui-timepicker-addon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <script>
        function changeMainImage(src) {
            document.getElementById('mainImage').src = src;
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var currentDate = new Date(); // Lấy ngày hiện tại (không có giờ)

            flatpickr(".datetime-picker", {
                enableTime: true, // Cho phép chọn giờ
                dateFormat: "d/m/Y H:i", // Định dạng ngày giờ
                minDate: currentDate, // Đặt ngày thấp nhất là ngày hôm nay
                time_24hr: true
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#pickup-date, #return-date").datetimepicker({
                dateFormat: "dd/mm/yy", // Định dạng ngày
                timeFormat: "HH:mm",
            });
        });

        function calculateAndRedirect(id) {
            var pickupDateTimeStr = $("#pickup-date").val();
            var returnDateTimeStr = $("#return-date").val();

            try {
                // Tách ngày và giờ
                var pickupDateStr = pickupDateTimeStr.split(" ")[0]; // Phần ngày
                var pickupTimeStr = pickupDateTimeStr.split(" ")[1]; // Phần giờ
                var returnDateStr = returnDateTimeStr.split(" ")[0]; // Phần ngày
                var returnTimeStr = returnDateTimeStr.split(" ")[1]; // Phần giờ

                // Chuyển đổi thành Date với múi giờ cục bộ
                var pickupDateParts = pickupDateStr.split("/"); // dd/mm/yyyy
                var pickupDateTime = new Date(
                    pickupDateParts[2], // Năm
                    pickupDateParts[1] - 1, // Tháng (bắt đầu từ 0)
                    pickupDateParts[0], // Ngày
                    pickupTimeStr.split(":")[0], // Giờ
                    pickupTimeStr.split(":")[1] // Phút
                );

                var returnDateParts = returnDateStr.split("/");
                var returnDateTime = new Date(
                    returnDateParts[2],
                    returnDateParts[1] - 1,
                    returnDateParts[0],
                    returnTimeStr.split(":")[0],
                    returnTimeStr.split(":")[1]
                );

                // Tính số ngày chênh lệch
                if (pickupDateTime && returnDateTime) {
                    var timeDiff = returnDateTime - pickupDateTime; // Tính chênh lệch thời gian
                    var daysDiff = Math.floor(timeDiff / (1000 * 3600 * 24)); // Số ngày chênh lệch

                    // Nếu giờ trả trễ hơn giờ nhận, cộng thêm 1 ngày
                    if (
                        returnDateTime.getHours() > pickupDateTime.getHours() ||
                        (returnDateTime.getHours() === pickupDateTime.getHours() &&
                            returnDateTime.getMinutes() >= pickupDateTime.getMinutes())
                    ) {
                        daysDiff += 1;
                    }

                    // Truyền giá trị qua URL
                    var formattedPickupDateTime = `${pickupDateParts[2]}-${pickupDateParts[1]}-${pickupDateParts[0]}T${pickupTimeStr}:00`;
                    var formattedReturnDateTime = `${returnDateParts[2]}-${returnDateParts[1]}-${returnDateParts[0]}T${returnTimeStr}:00`;

                    window.location.href = `Order_Form.php?day=${daysDiff}&id=${id}&pickdate=${encodeURIComponent(formattedPickupDateTime)}&returndate=${encodeURIComponent(formattedReturnDateTime)}`;
                } else {
                    alert("Vui lòng chọn cả ngày và giờ nhận và trả.");
                }
            } catch (error) {
                alert("Định dạng ngày/giờ không hợp lệ. Vui lòng nhập đúng định dạng (dd/mm/yyyy HH:mm).");
            }
        }
    </script>

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
            <a href="../ThueXe/ThueXe.php">
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

    <div class="container my-5">
        <div class="row">
            <!-- Gallery Section -->
            <div class="col-lg-7">
                <div class="col_left">
                    <div class="car-gallery">
                        <?php if (!empty($images)): ?>
                            <img id="mainImage" src="../uploads/<?php echo $images[0]; ?>" class="main-image"
                                alt="<?php echo $car['ten_hang_xe']; ?>">
                            <div class="thumbnail-container">
                                <?php foreach ($images as $index => $image): ?>
                                    <img src="../uploads/<?php echo $image; ?>" class="thumbnail"
                                        onclick="changeMainImage('../uploads/<?php echo $image; ?>')"
                                        alt="Thumbnail <?php echo $index + 1; ?>">
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">Không có ảnh nào cho xe này.</div>
                        <?php endif; ?>
                    </div>
                    <div class="card car-info-card">
                        <div class="card-body">
                            <h2 class="card-title mb-3">
                                <?php echo $car['ten_hang_xe'] . ' ' . $car['ten_dong_xe'] . ' ' . $car['phien_ban']; ?>
                                <p class="price mb-4">
                                    <i class="fas fa-tag"></i>
                                    <?php echo number_format($car['gia'], 0, ',', '.'); ?> VND / 1 NGÀY
                                </p>
                            </h2>

                            <div class="card car-des">
                                <div class="card-body">
                                    <h3 class="card-title mb-4">
                                        <i class="fas fa-info-circle"></i> Mô tả
                                    </h3>
                                    <p class="card-text">
                                        <?php echo nl2br($car['mo_ta']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Car Info Section -->
            <div class="col-lg-5 car-info">
                <div class="card servic_info">
                    <div class="rental_card">
                        <h2>GIAO XE</h2>
                        <hr>
                        <label>
                            Giao xe tại đại lý
                        </label>
                    </div>
                    <div class="rental_card">
                        <h2>THỜI GIAN THUÊ</h2>
                        <hr>
                        <div class="date-container">
                            <div>
                                <label for="pickup-date">Ngày nhận xe</label>
                                <div class="input-container">
                                    <input type="text" id="pickup-date" class="datetime-picker">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <div>
                                <label for="return-date">Ngày trả xe</label>
                                <div class="input-container">
                                    <input type="text" id="return-date" class="datetime-picker">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="rental_card">
                        <h2>THỦ TỤC CẦN CÓ</h2>
                        <hr>
                        <label>Căn cước công dân</label>
                        <br>
                        <label>Giấy phép lái xe</label>

                    </div>

                    <div class="rental_card">
                        <h2>GIỚI HẠN QUÃNG ĐƯỜNG</h2>
                        <hr>
                        <label>2000 Km (Nếu vượt qua phụ thu 5000 VND/Km)</label>
                    </div>
                    <div class="rental_card">
                        <h2>GIỚI HẠN THỜI GIAN</h2>
                        <hr>

                        <label>Ghi trên hợp đồng (Nếu vượt qua phụ thu 5000 VND/h)</label>
                    </div>
                    <div class="rental_card ">
                        <div class="order_card">
                            <a href="tel:0835886837" class="btn btn-primary">
                                <i class="fas fa-phone"></i> Gọi chủ xe
                            </a>
                            <button onclick="calculateAndRedirect(<?php echo $car['id']; ?>)" class="btn btn-outline-primary">
                                Đặt xe
                            </button>
                        </div>

                    </div>
                </div>


            </div>
        </div>

        <!-- Technical Specs & Description -->
        <div class="row mt-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-cogs"></i> Thông số kỹ thuật
                    </h3>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row"><i class="fas fa-car"></i> Hãng xe</th>
                                <td>
                                    <?php echo $car['ten_hang_xe']; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-car-side"></i> Dòng xe</th>
                                <td>
                                    <?php echo $car['ten_dong_xe']; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-code-branch"></i> Phiên bản</th>
                                <td>
                                    <?php echo $car['phien_ban']; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-calendar-alt"></i> Năm sản xuất</th>
                                <td>
                                    <?php echo $car['nam_san_xuat']; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-car-alt"></i> Kiểu dáng</th>
                                <td>
                                    <?php echo $car['kieu_dang']; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-globe-americas"></i> Xuất xứ</th>
                                <td>
                                    <?php echo $car['xuat_xu']; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-users"></i> Số ghế ngồi</th>
                                <td>
                                    <?php echo $car['so_ghe_ngoi']; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-tachometer-alt"></i> Số KM đã đi</th>
                                <td>
                                    <?php echo number_format($car['odo'], 0, ',', '.'); ?> km
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-gas-pump"></i> Nhiên liệu</th>
                                <td>
                                    <?php echo $car['nhien_lieu']; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><i class="fas fa-cog"></i> Hộp số</th>
                                <td>
                                    <?php echo $car['hop_so']; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
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
                <textarea name="message" class="text-input contact-input " placeholder="Tin nhắn của bạn..."></textarea>
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