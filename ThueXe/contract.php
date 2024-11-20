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
$sql_DH = "SELECT MAX(DH_MADON) AS max_order_id
FROM don_hang;
";
$result = $conn->query($sql_DH);
if ($result->num_rows > 0) {
    $row_DH = $result->fetch_assoc();
}
$id_DH = $row_DH['max_order_id'] + 1;

// Kiểm tra và lấy thông tin từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pickdate = $_POST['pickdate'];
    $returndate = $_POST['returndate'];
    $xe_id = $_POST['id'];
    $day = isset($_POST['day']) ? $_POST['day'] : 1;
    $cccd = $_POST['CCCD'];
    $gplx = $_POST['GPLX'];
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $diachi = $_POST['dia_chi'];
    $email = $_POST['email'];
    $ghi_chu = $_POST['ghi_chu'];
    $payment = $_POST['payment_1'];
    $payment_method = $_POST['payment']; // Xử lý logic và lưu thông tin hợp đồng nếu cầ   // Ví dụ: lưu thông tin vào cơ sở dữ liệu, tính giá tiền, áp dụng mã giảm giá, v.v.
}

// Kiểm tra nếu các giá trị không rỗng
if (!empty($pickdate) && !empty($returndate) && !empty($xe_id)) {
    // Chuẩn bị câu lệnh SQL với các tham số
    $sql_tt = "INSERT INTO trang_thai (XE_ID, TT_NGAYBD,TT_NGAYKT, TT_TRANGTHAI) VALUES (?, ?, ?, 'Đang thuê')";

    // Sử dụng prepared statement
    $stmt_att = $conn->prepare($sql_tt);

    // Kiểm tra nếu prepared statement thành công
    if ($stmt_att === false) {
        // Nếu không thành công, in ra lỗi
        echo "Error: " . $conn->error;
    } else {
        // Gắn giá trị vào các tham số trong câu lệnh SQL
        $stmt_att->bind_param("iss", $xe_id, $pickdate, $returndate);

        // Thực thi câu lệnh
        if ($stmt_att->execute()) {
            // Kiểm tra nếu câu lệnh thực thi thành công
            echo "Cập nhật trạng thái thành công!";
        } else {
            // Nếu không thực thi được, hiển thị lỗi
            echo "Lỗi khi thực hiện câu lệnh SQL: " . $stmt_att->error;
        }

        // Đóng statement sau khi thực thi
        $stmt_att->close();
    }
} else {
    echo "Dữ liệu không hợp lệ!";
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

$ngay_gio_hom_nay = date('Y-m-d H:i:s');

// Kiểm tra thông tin khách hàng không rỗng
if (!empty($cccd) && !empty($gplx) && !empty($ho_ten) && !empty($so_dien_thoai) && !empty($diachi) && !empty($email)) {

    // Thêm khách hàng vào bảng KHACH
    $sql_kh = $conn->prepare("INSERT INTO KHACH (KH_CCCD, KH_HOTEN, KH_GPLX, KH_DIACHI, KH_SDT, KH_EMAIL) 
                              VALUES (?, ?, ?, ?, ?, ?)");

    $sql_kh->bind_param("ssssss", $cccd, $ho_ten, $gplx, $diachi, $so_dien_thoai, $email);

    if ($sql_kh->execute()) {
        // Kiểm tra nếu câu lệnh INSERT khách hàng thành công


        // Kiểm tra thông tin đơn hàng
        if (!empty($xe_id) && !empty($id_DH) && !empty($day) && !empty($pickdate) && !empty($returndate)) {

            // Tính toán tổng tiền và tiền cọc
            $tongtien = $day * $car['gia'];
            $coc = $tongtien * 0.3;

            // Thêm đơn hàng vào bảng DON_HANG
            $sql_dh = $conn->prepare("INSERT INTO DON_HANG (KH_CCCD, XE_ID, DH_MADON, DH_NGAYLAP, DH_SONGAYTHUE, DH_NGAYBD, DH_NGAYKT, DH_TONGTIEN, DH_TIENCOC)
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $sql_dh->bind_param("siissssii", $cccd, $xe_id, $id_DH, $ngay_gio_hom_nay, $day, $pickdate, $returndate, $tongtien, $coc);

            if ($sql_dh->execute()) {
               
            } else {
                echo "Lỗi tạo đơn hàng: " . $sql_dh->error;
            }
        } else {
            echo "Thông tin đơn hàng không đầy đủ!";
        }
    } else {
        // Nếu câu lệnh INSERT khách hàng không thành công
        echo "Có lỗi khi thêm khách hàng: " . $sql_kh->error;
    }

} else {
    // Nếu thông tin khách hàng không đầy đủ
    echo "Không đủ tham số khách hàng!";
}


?>
<html>

<head>
    <title>
        CaR88 Vietnam
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles.css">
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
                        <span>
                            <?php echo $is_logged_in ? $user_name : 'Tài khoản'; ?>
                        </span>
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


    <div class="container_order ">
        <div class="image">
            <img alt="Toyota Vios MT" height="200" src="../uploads/<?php echo $images[0]; ?>" width="300" />
        </div>
        <div class="content">
            <h1>
                ĐẶT HÀNG THÀNH CÔNG
            </h1>
            <p>
                Kính gửi Quý khách hàng
                <span class="highlight">
                    <?php echo $ho_ten ?>
                </span>
                ,
            </p>
            <p>
                Cảm ơn Quý khách đã đặt thuê xe tại Car88.com. Chúng tôi xin thông báo đơn hàng đặt thuê xe của Quý
                khách:
            </p>

            <p class="bold">
                Đơn hàng:
                <span class="highlight">
                    <?php echo $id_DH ?>
                </span>
            </p>
            <p class="bold">
                Mẫu xe:
                <?php echo $car['ten_hang_xe'] . ' ' . $car['ten_dong_xe'] . ' ' . $car['phien_ban']; ?>
            </p>
            <p class="bold">
                Trạng thái đơn hàng:
                <span class="status">
                    Thành công
                </span>
            </p>
            <p class="bold">
                <?php
                if ($payment == "postpay") {
                    $coc = $day * $car['gia'] * 0.3;
                } else {
                    $coc = 0;
                } ?>
                Số tiền cọc:
                <?php echo number_format($coc) ?> VND
            </p>
            <p class="bold">
                <?php
                if ($payment_method == "bank") {
                    $p_method = "Chuyển khoản ngân hàng";
                } else if ($payment_method == "atm") {
                    $p_method = "Thẻ ATM nội địa";
                } ?>
                Phương thức thanh toán:
                <?php echo $p_method ?>
            </p>
            <p>
                Trân trọng!
            </p>
            <div class="homeback">
                <a href="#">
                    Trở về trang chủ
                </a>
            </div>
        </div>
    </div>


    <!-- Chữ ký -->

    <a href="in.php?id=<?= $xe_id ?>&day=<?= $day ?>&ma_giam_gia=<?= $ma_giam_gia ?>&ho_ten=<?= urlencode($ho_ten) ?>&so_dien_thoai=<?= urlencode($so_dien_thoai) ?>&email=<?= urlencode($email) ?>&ghi_chu=<?= urlencode($ghi_chu) ?>&payment=<?= urlencode($payment_method) ?>"
        class="btn btn-print" target="_blank">
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
        .container_order {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;

        }

        .image {
            margin-right: 50px;
        }



        .container_order h1 {
            color: #0056b3;
            font-size: 24px;
        }

        .container_order p {
            margin: 15px 0;
        }

        .container_order .highlight {
            color: red;
        }

        .container_order .status {
            color: green;
        }

        .container_order .bold {
            font-weight: bold;
        }

        .container_order .homeback {
            margin-top: 20px;
        }

        .container_order .homeback a {
            text-decoration: none;
            color: #0056b3;
            border: 1px solid #0056b3;
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
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