<?php
require_once 'config.php';

// Xây dựng câu truy vấn SQL cơ bản
$sql = "SELECT xe.*, hang_xe.ten_hang_xe as hang_xe, dong_xe.ten_dong_xe as dong_xe, GROUP_CONCAT(anh_xe.url_anh) as all_images
        FROM xe
        LEFT JOIN anh_xe ON xe.id = anh_xe.xe_id
        LEFT JOIN hang_xe ON xe.hang_xe_id = hang_xe.id
        LEFT JOIN dong_xe ON xe.dong_xe_id = dong_xe.id";

$conditions = array();
$params = array();
$types = "";

// Lọc theo hãng xe
if (!empty($_POST['hang_xe'])) {
    $conditions[] = "xe.hang_xe_id = ?";
    $params[] = $_POST['hang_xe'];
    $types .= "i";
}

// Lọc theo giá
if (!empty($_POST['gia_min'])) {
    $conditions[] = "xe.gia >= ?";
    $params[] = $_POST['gia_min'] * 1000000;
    $types .= "d";
}
if (!empty($_POST['gia_max'])) {
    $conditions[] = "xe.gia <= ?";
    $params[] = $_POST['gia_max'] * 1000000;
    $types .= "d";
}

// Lọc theo năm sản xuất
if (!empty($_POST['nam_san_xuat_min'])) {
    $conditions[] = "xe.nam_san_xuat >= ?";
    $params[] = $_POST['nam_san_xuat_min'];
    $types .= "i";
}
if (!empty($_POST['nam_san_xuat_max'])) {
    $conditions[] = "xe.nam_san_xuat <= ?";
    $params[] = $_POST['nam_san_xuat_max'];
    $types .= "i";
}

// Các điều kiện lọc khác
if (!empty($_POST['km_max'])) {
    $conditions[] = "xe.odo <= ?";
    $params[] = $_POST['km_max'];
    $types .= "i";
}

if (!empty($_POST['kieu_dang'])) {
    $conditions[] = "xe.kieu_dang = ?";
    $params[] = $_POST['kieu_dang'];
    $types .= "s";
}

if (!empty($_POST['nhien_lieu'])) {
    $conditions[] = "xe.nhien_lieu = ?";
    $params[] = $_POST['nhien_lieu'];
    $types .= "s";
}

if (!empty($_POST['hop_so'])) {
    $conditions[] = "xe.hop_so = ?";
    $params[] = $_POST['hop_so'];
    $types .= "s";
}

if (!empty($_POST['so_ghe_ngoi'])) {
    $conditions[] = "xe.so_ghe_ngoi = ?";
    $params[] = $_POST['so_ghe_ngoi'];
    $types .= "i";
}

// Thêm điều kiện WHERE nếu có
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Thêm GROUP BY để tránh trùng lặp xe
$sql .= " GROUP BY xe.id";

// Chuẩn bị và thực thi câu truy vấn
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Hiển thị kết quả
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images = explode(',', $row["all_images"]);
        echo '<div class="listing">';
        echo '<div class="image-container">';

        foreach ($images as $index => $image) {
            echo '<img src="uploads/' . $image . '" alt="' . $row["hang_xe"] . ' ' . $row["dong_xe"] . '" ' .
                ($index == 0 ? 'class="active"' : '') . ' />';
        }

        echo '<button class="prev-btn">&#10094;</button>';
        echo '<button class="next-btn">&#10095;</button>';
        echo '</div>';

        echo '<div class="details">';
        echo '<h3>' . $row["hang_xe"] . ' ' . $row["dong_xe"] . ' ' . $row["phien_ban"] . '</h3>';
        echo '<div class="info-grid">';
        echo '<div class="info-item"><i class="fas fa-calendar-alt"></i> ' . $row["nam_san_xuat"] . '</div>';
        echo '<div class="info-item"><i class="fas fa-tachometer-alt"></i> ' . number_format($row["odo"]) . ' km</div>';
        echo '<div class="info-item"><i class="fas fa-gas-pump"></i> ' . $row["nhien_lieu"] . '</div>';
        echo '<div class="info-item"><i class="fas fa-cogs"></i> ' . $row["hop_so"] . '</div>';
        echo '<div class="info-item"><i class="fas fa-users"></i> ' . $row["so_ghe_ngoi"] . ' chỗ</div>';
        echo '</div>';
        echo '<div class="price">' . number_format($row["gia"]) . ' VNĐ</div>';
        echo '</div>'; // .details
        echo '</div>'; // .listing
    }
} else {
    echo '<p>Không có kết quả nào phù hợp.</p>';
}
?>