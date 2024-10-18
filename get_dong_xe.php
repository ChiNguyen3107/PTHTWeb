<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Thay đổi nếu cần
$username = "root"; // Tên người dùng CSDL
$password = ""; // Mật khẩu CSDL
$dbname = "pthtweb"; // Tên CSDL

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_POST['hang_xe_id'])) {
    $hang_xe_id = $_POST['hang_xe_id'];

    // Truy vấn để lấy dòng xe theo hãng xe
    $sql = "SELECT * FROM dong_xe WHERE hang_xe_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hang_xe_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Tạo option cho combobox
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['ten_dong_xe'] . "</option>";
        }
    } else {
        echo "<option value=''>Không có dòng xe nào</option>";
    }

    $stmt->close();
}
$conn->close();
?>