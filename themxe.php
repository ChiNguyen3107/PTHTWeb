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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hang_xe_id = $_POST['hang_xe']; // Đổi tên biến này thành hang_xe_id
    $dong_xe_id = $_POST['dong_xe']; // Đổi tên biến này thành dong_xe_id
    $phien_ban = $_POST['phien_ban'];
    $nam_san_xuat = $_POST['nam_san_xuat'];
    $kieu_dang = $_POST['kieu_dang'];
    $xuat_xu = $_POST['xuat_xu'];
    $so_ghe_ngoi = $_POST['so_ghe_ngoi'];
    $so_km = $_POST['so_km'];
    $nhien_lieu = $_POST['nhien_lieu'];
    $hop_so = $_POST['hop_so'];
    $gia = $_POST['gia'];
    $mo_ta = $_POST['mota'];
    $thue_xe= $_POST['thue_xe'];



    // Xử lý ảnh
    $anh_xe = $_FILES['anh_xe'];
    $upload_dir = 'uploads/'; // Thay đổi đường dẫn nếu cần

    // Kiểm tra và tạo thư mục uploads nếu không tồn tại
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $uploaded_images = [];

    foreach ($anh_xe['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($anh_xe['name'][$key]);
        $target_file = $upload_dir . $file_name;

        // Đổi tên tệp nếu tệp đã tồn tại
        $i = 1;
        while (file_exists($target_file)) {
            $file_name = pathinfo($anh_xe['name'][$key], PATHINFO_FILENAME) . "_{$i}." . pathinfo($anh_xe['name'][$key], PATHINFO_EXTENSION);
            $target_file = $upload_dir . $file_name;
            $i++;
        }

        // Tải lên ảnh
        if (move_uploaded_file($tmp_name, $target_file)) {
            $uploaded_images[] = $file_name; // Lưu tên ảnh đã tải lên
        } else {
            echo "Có lỗi xảy ra khi tải lên ảnh: " . $file_name;
        }
    }

    // Lưu thông tin xe vào cơ sở dữ liệu
// Lấy giá trị mô tả từ form
    $mo_ta = $_POST['mo_ta'];  // Thêm dòng này

    $sql = "INSERT INTO xe (hang_xe_id, dong_xe_id, phien_ban, nam_san_xuat, kieu_dang, xuat_xu, so_ghe_ngoi, odo, nhien_lieu, hop_so, gia, mo_ta,thue_xe) 
        VALUES ('$hang_xe_id', '$dong_xe_id', '$phien_ban', '$nam_san_xuat', '$kieu_dang', '$xuat_xu', '$so_ghe_ngoi', '$so_km', '$nhien_lieu', '$hop_so', '$gia', '$mo_ta','$thue_xe')";
    if ($conn->query($sql) === TRUE) {
        // Lấy ID của xe vừa thêm
        $xe_id = $conn->insert_id;

        // Lưu thông tin ảnh vào bảng anh_xe
        foreach ($uploaded_images as $image) {
            $sql_image = "INSERT INTO anh_xe (xe_id, url_anh) VALUES ('$xe_id', '$image')";
            $conn->query($sql_image);
        }

        echo "Xe đã được thêm thành công!";
        header("Location: manage_cars.php");
        exit();
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}
?>