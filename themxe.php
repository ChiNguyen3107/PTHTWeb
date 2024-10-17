<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Thay đổi nếu cần
$username = "root"; // Tên người dùng CSDL
$password = ""; // Mật khẩu CSDL
$dbname = "pthtweb"; // Tên CSDL

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu từ form
$hang_xe = $_POST['hang_xe'];
$dong_xe = $_POST['dong_xe'];
$phien_ban = $_POST['phien_ban'];
$nam_san_xuat = $_POST['nam_san_xuat'];
$kieu_dang = $_POST['kieu_dang'];
$xuat_xu = $_POST['xuat_xu'];
$so_ghe_ngoi = $_POST['so_ghe_ngoi'];
$nhien_lieu = $_POST['nhien_lieu'];
$hop_so = $_POST['hop_so'];
$gia = $_POST['gia'];
$so_km = isset($_POST['so_km']) ? intval($_POST['so_km']) : 0; // Chuyển đổi sang số nguyên
$mo_ta = $_POST['mo_ta'];

// Chuẩn bị câu lệnh SQL sử dụng prepared statement
$sql = "INSERT INTO xe (hang_xe, dong_xe, phien_ban, nam_san_xuat, kieu_dang, xuat_xu, so_ghe_ngoi, nhien_lieu, hop_so, gia, so_km, mo_ta) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Chuẩn bị statement
$stmt = $conn->prepare($sql);

// Bind các tham số
$stmt->bind_param("sssissssssis", $hang_xe, $dong_xe, $phien_ban, $nam_san_xuat, $kieu_dang, $xuat_xu, $so_ghe_ngoi, $nhien_lieu, $hop_so, $gia, $so_km, $mo_ta);

// Thực thi câu lệnh
if ($stmt->execute()) {
    $xe_id = $stmt->insert_id;
    if (!file_exists('anh_xe')) {
        mkdir('anh_xe', 0777, true);
    }
    // Thêm ảnh xe
    $anh_xe = $_FILES['anh_xe'];

    foreach ($anh_xe['name'] as $key => $value) {
        $url_anh = "anh_xe/" . $anh_xe['name'][$key];
        $sql = "INSERT INTO anh_xe (xe_id, url_anh, la_anh_dai_dien) VALUES (?, ?, 0)";
        $stmt_anh = $conn->prepare($sql);
        $stmt_anh->bind_param("is", $xe_id, $url_anh);
        $stmt_anh->execute();
        move_uploaded_file($anh_xe['tmp_name'][$key], $url_anh);
    }
    echo "Thêm xe thành công!";
} else {
    echo "Lỗi thêm xe: " . $stmt->error;
}

// Đóng statement và kết nối
$stmt->close();
$conn->close();
?>