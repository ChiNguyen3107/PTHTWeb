<?php
require_once 'config.php';

if (isset($_POST['hang_xe_id'])) {
    $hang_xe_id = $_POST['hang_xe_id'];

    // Truy vấn để lấy dòng xe theo hãng xe
    $sql = "SELECT * FROM dong_xe WHERE hang_xe_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hang_xe_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Tạo option cho combobox
    echo '<option value="">Chọn dòng xe</option>';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['ten_dong_xe'] . "</option>";
        }
    }

    $stmt->close();
}
$conn->close();
?>