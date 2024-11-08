<?php
require_once 'config.php';

if(isset($_GET['query'])) {
    $search = $_GET['query'];
    
    $sql = "SELECT xe.*, hang_xe.ten_hang_xe, dong_xe.ten_dong_xe, 
            (SELECT url_anh FROM anh_xe WHERE xe_id = xe.id LIMIT 1) as anh_dai_dien
            FROM xe
            LEFT JOIN hang_xe ON xe.hang_xe_id = hang_xe.id
            LEFT JOIN dong_xe ON xe.dong_xe_id = dong_xe.id
            WHERE hang_xe.ten_hang_xe LIKE ? 
            OR dong_xe.ten_dong_xe LIKE ? 
            OR xe.phien_ban LIKE ?
            OR xe.mo_ta LIKE ?
            LIMIT 10";
    
    $stmt = $conn->prepare($sql);
    $searchParam = "%$search%";
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cars = [];
    while($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
    
    echo json_encode($cars);
} else {
    echo json_encode([]);
}