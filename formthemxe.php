<?php
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
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Xe Mới</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="themxe.php" method="POST" enctype="multipart/form-data">
        <h2>Thêm Xe Mới</h2>

        <label for="hang_xe">Hãng xe:</label>
        <select id="hang_xe" name="hang_xe" required>
            <option value="">Chọn hãng xe</option>
            <?php
            $sql = "SELECT * FROM hang_xe";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['ten_hang_xe'] . "</option>";
            }
            ?>
        </select>

        <label for="dong_xe">Dòng xe:</label>
        <select id="dong_xe" name="dong_xe" required>
            <option value="">Chọn dòng xe</option>
        </select>

        <label for="phien_ban">Phiên bản:</label>
        <input type="text" id="phien_ban" name="phien_ban">

        <label for="nam_san_xuat">Năm sản xuất:</label>
        <input type="number" id="nam_san_xuat" name="nam_san_xuat" required min="1900" max="2099">

        <label for="kieu_dang">Kiểu dáng:</label>
        <select id="kieu_dang" name="kieu_dang">
            <option value="">Chọn kiểu dáng</option>
            <option value="Sedan">Sedan</option>
            <option value="SUV">SUV</option>
            <option value="Hatchback">Hatchback</option>
            <option value="MPV">MPV</option>
            <option value="Crossover">Crossover</option>
        </select>

        <label for="xuat_xu">Xuất xứ:</label>
        <input type="text" id="xuat_xu" name="xuat_xu">

        <label for="so_ghe_ngoi">Số ghế ngồi:</label>
        <input type="number" id="so_ghe_ngoi" name="so_ghe_ngoi" min="2" max="50">

        <label for="so_km">Số km đã đi:</label>
        <input type="number" id="so_km" name="so_km" min="0">

        <label for="nhien_lieu">Nhiên liệu:</label>
        <select id="nhien_lieu" name="nhien_lieu">
            <option value="">Chọn nhiên liệu</option>
            <option value="Xăng">Xăng</option>
            <option value="Dầu">Dầu</option>
            <option value="Điện">Điện</option>
            <option value="Hybrid">Hybrid</option>
        </select>

        <label for="hop_so">Hộp số:</label>
        <select id="hop_so" name="hop_so">
            <option value="">Chọn hộp số</option>
            <option value="Số tự động">Số tự động</option>
            <option value="Số sàn">Số sàn</ option>
            <option value="Số bán tự động">Số bán tự động</option>
        </select>

        <label for="gia_ban">Giá bán:</label>
        <input type="number" id="gia_ban" name="gia_ban" min="0">

        <label for="mota">Mô Tả Xe:</label>
        <textarea id="mota" name="mota" required></textarea>

        <label for="anh_xe">Ảnh xe:</label>
        <input type="file" id="anh_xe" name="anh_xe[]" multiple required>


        <input type="submit" value="Thêm xe mới">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('hang_xe').addEventListener('change', function () {
                var hang_xe_id = this.value;
                if (hang_xe_id != '') {
                    fetch('get_dong_xe.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'hang_xe_id=' + hang_xe_id
                    })
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById('dong_xe').innerHTML = data;
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                } else {
                    document.getElementById('dong_xe').innerHTML = '<option value="">Chọn dòng xe</option>';
                }
            });
        });

    </script>
</body>

</html>