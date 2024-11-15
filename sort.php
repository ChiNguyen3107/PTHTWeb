<?php
require_once 'config.php';

if (isset($_POST['sort_type'])) {
    $sort_type = $_POST['sort_type'];
    
    // Xây dựng câu truy vấn SQL dựa trên loại sắp xếp
    $sql = "SELECT xe.*, hang_xe.ten_hang_xe, dong_xe.ten_dong_xe, GROUP_CONCAT(anh_xe.url_anh) as all_images 
            FROM xe 
            LEFT JOIN anh_xe ON xe.id = anh_xe.xe_id 
            LEFT JOIN hang_xe ON xe.hang_xe_id = hang_xe.id 
            LEFT JOIN dong_xe ON xe.dong_xe_id = dong_xe.id 
            GROUP BY xe.id ";

    switch ($sort_type) {
        case 'price-asc':
            $sql .= "ORDER BY xe.gia ASC";
            break;
        case 'price-desc':
            $sql .= "ORDER BY xe.gia DESC";
            break;
        case 'km-asc':
            $sql .= "ORDER BY xe.odo ASC";
            break;
        case 'km-desc':
            $sql .= "ORDER BY xe.odo DESC";
            break;
        default:
            $sql .= "ORDER BY xe.ngay_dang DESC";
    }

    $result = $conn->query($sql);
    $output = '';

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $images = explode(',', $row["all_images"]);
            
            $output .= '<div class="listing">';
            $output .= '<div class="image-container">';
            
            foreach ($images as $index => $image) {
                $output .= '<img src="uploads/' . $image . '" alt="' . $row["ten_hang_xe"] . ' ' . $row["ten_dong_xe"] . '" ' . 
                          ($index == 0 ? 'class="active"' : '') . ' />';
            }
            
            $output .= '<button class="prev-btn">&#10094;</button>';
            $output .= '<button class="next-btn">&#10095;</button>';
            $output .= '</div>';
            
            $output .= '<div class="details">';
            $output .= '<h3>' . $row["ten_hang_xe"] . ' ' . $row["ten_dong_xe"] . ' ' . $row["phien_ban"] . '</h3>';
            $output .= '<div class="info-grid">';
            $output .= '<div class="info-item"><i class="fas fa-calendar-alt"></i> ' . $row["nam_san_xuat"] . '</div>';
            $output .= '<div class="info-item"><i class="fas fa-tachometer-alt"></i> ' . number_format($row["odo"]) . ' km</div>';
            $output .= '<div class="info-item"><i class="fas fa-gas-pump"></i> ' . $row["nhien_lieu"] . '</div>';
            $output .= '<div class="info-item"><i class="fas fa-cogs"></i> ' . $row["hop_so"] . '</div>';
            $output .= '</div>';
            $output .= '<div class="price"><i class="fas fa-tag"></i> ' . number_format($row["gia"]) . ' VNĐ</div>';
            $output .= '<a href="car_detail.php?id=' . $row['id'] . '">Xem chi tiết</a>';
            $output .= '</div>';
            $output .= '</div>';
        }
    } else {
        $output = '<p>Không tìm thấy xe nào.</p>';
    }

    echo $output;
    $conn->close();
}
?>