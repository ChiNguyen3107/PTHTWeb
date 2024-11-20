<?php
// Kết nối với cơ sở dữ liệu
require_once __DIR__ . '/../config.php';

// Lấy thông tin xe từ URL
$xe_id = isset($_GET['id']) ? $_GET['id'] : null;
$day = isset($_GET['day']) ? $_GET['day'] : 1; // Mặc định là 1 ngày
$ma_giam_gia = isset($_GET['ma_giam_gia']) ? $_GET['ma_giam_gia'] : ''; // Mã giảm giá (nếu có)

// Kiểm tra nếu có ID xe
if ($xe_id) {
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

    // Tính toán giá cơ bản
    $basePrice = (float)$car['gia'];
    $gia_co_ban = $day * $basePrice;
    $tong_tien = $gia_co_ban; // Tổng tiền chưa bao gồm mã giảm giá và dịch vụ tùy chọn
} else {
    echo "Không có ID xe!";
    exit();
}

// Kiểm tra thông tin khách hàng (từ POST hoặc GET nếu có)
$ho_ten = isset($_GET['ho_ten']) ? $_GET['ho_ten'] : 'Chưa có thông tin';
$so_dien_thoai = isset($_GET['so_dien_thoai']) ? $_GET['so_dien_thoai'] : 'Chưa có thông tin';
$email = isset($_GET['email']) ? $_GET['email'] : 'Chưa có thông tin';
$ghi_chu = isset($_GET['ghi_chu']) ? $_GET['ghi_chu'] : 'Không có ghi chú';
$payment_method = isset($_GET['payment']) ? $_GET['payment'] : 'Không xác định';

// Chuyển phương thức thanh toán thành tên phương thức
switch ($payment_method) {
    case 'prepay':
        $payment_method_display = "Trả trước";
        break;
    case 'postpay':
        $payment_method_display = "Trả sau";
        break;
    case 'atm':
        $payment_method_display = "Thẻ ATM nội địa";
        break;
    case 'visa':
        $payment_method_display = "VISA/ Master Card (Thẻ phát hành tại Việt Nam)";
        break;
    case 'vnpay':
        $payment_method_display = "VNPAY";
        break;
    case 'bank':
        $payment_method_display = "Chuyển khoản ngân hàng";
        break;
    case 'later':
        $payment_method_display = "Thanh toán sau";
        break;
    default:
        $payment_method_display = "Không xác định";
}
?>

<html>

<head>
    <title>In Hợp Đồng Thuê Xe - CaR88 Vietnam</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Định dạng cho trang in */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.2;
            color: #333;
            margin: 0;
            padding: 5px 30px 5px 30px;
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
            padding-top: 10px;
            width: 100%;
            border-top: 2px solid #ddd;
        }

        .signature-item {
            text-align: center;
            width: 30%;
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

        @media print {

            /* Thiết lập lề cho trang in */
            body {
                margin: 0;
                padding: 0;
            }

            /* Đảm bảo rằng phần tử chính sẽ chiếm toàn bộ không gian có sẵn */
            .contract-print {
                page-break-before: always;
                width: 100%;
                height: 90%;

                transform: scale(0.9);
            }

            /* Đảm bảo rằng các phần tử không cần in sẽ bị ẩn */
            /* Thiết lập tỷ lệ để vừa với một trang */
            @page {
                size: A4;
                margin: 10mm;
            }

            .signature {
                page-break-after: always;
            }

        }
    </style>
    <script type="text/javascript">
        window.onload = function() {
            window.print();
        };
    </script>
</head>

<body>
    <div class="contract-print">
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
                    <span class="info-value"><?php echo $payment_method_display; ?></span>
                </div>
            </div>
        </div>

        <!-- Điều khoản hợp đồng -->
        <div class="contract-section">
            <h3>Điều Khoản Hợp Đồng</h3>
            <div class="contract-info">
                <div class="info-item">
                    <span class="info-title">Đơn giá: </span>
                    <span class="info-value"><?php echo number_format($basePrice); ?> đ/ngày</span>
                </div>
                <div class="info-item">
                    <span class="info-title">Thời gian thuê: </span>
                    <span class="info-value"><?php echo $day . ' ngày'; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-title">Giá cơ bản: </span>
                    <span class="info-value"><?php echo number_format($gia_co_ban); ?> đ</span>
                </div>
                <div class="info-item">
                    <span class="info-title">Dịch vụ tùy chọn: </span>
                    <span class="info-value">Không có</span>
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

        <!-- Chữ ký -->
        <div class="contract-section">
            <h3>Chữ Ký</h3>
            <div class="signature">
                <div class="signature-item">
                    <p><strong>Đại diện CaR88</strong></p>
                    <p class="space"></p>
                </div>
                <div class="signature-item">
                    <p><strong>Người thuê xe</strong></p>
                    <p class="space"></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>