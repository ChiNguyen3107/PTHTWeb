<?php
session_start();
require_once 'config.php';

// Kiểm tra quyền truy cập (nếu cần)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: login.php");
    exit();
}

// Lấy danh sách hãng xe
$sql_hang_xe = "SELECT * FROM hang_xe ORDER BY ten_hang_xe";
$result_hang_xe = $conn->query($sql_hang_xe);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Xe Mới - Car88</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }

        .preview-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .preview-images img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="text-center mb-0">Thêm Xe Mới</h2>
                    </div>
                    <div class="card-body">
                        <form action="themxe.php" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <!-- Hãng xe và Dòng xe -->
                                <div class="col-md-6 mb-3">
                                    <label for="hang_xe" class="form-label required">Hãng xe</label>
                                    <select class="form-select" id="hang_xe" name="hang_xe" required>
                                        <option value="">Chọn hãng xe</option>
                                        <?php while ($hang_xe = $result_hang_xe->fetch_assoc()): ?>
                                            <option value="<?php echo $hang_xe['id']; ?>">
                                                <?php echo $hang_xe['ten_hang_xe']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="dong_xe" class="form-label required">Dòng xe</label>
                                    <select class="form-select" id="dong_xe" name="dong_xe" required>
                                        <option value="">Chọn dòng xe</option>
                                    </select>
                                </div>

                                <!-- Phiên bản -->
                                <div class="col-md-6 mb-3">
                                    <label for="phien_ban" class="form-label">Phiên bản</label>
                                    <input type="text" class="form-control" id="phien_ban" name="phien_ban">
                                </div>

                                <!-- Năm sản xuất -->
                                <div class="col-md-6 mb-3">
                                    <label for="nam_san_xuat" class="form-label required">Năm sản xuất</label>
                                    <input type="number" class="form-control" id="nam_san_xuat" name="nam_san_xuat"
                                        min="1900" max="<?php echo date('Y'); ?>" required>
                                </div>

                                <!-- Kiểu dáng -->
                                <div class="col-md-6 mb-3">
                                    <label for="kieu_dang" class="form-label required">Kiểu dáng</label>
                                    <select class="form-select" id="kieu_dang" name="kieu_dang" required>
                                        <option value="">Chọn kiểu dáng</option>
                                        <option value="Sedan">Sedan</option>
                                        <option value="SUV">SUV</option>
                                        <option value="Hatchback">Hatchback</option>
                                        <option value="MPV">MPV</option>
                                        <option value="Crossover">Crossover</option>
                                    </select>
                                </div>

                                <!-- Xuất xứ -->
                                <div class="col-md-6 mb-3">
                                    <label for="xuat_xu" class="form-label required">Xuất xứ</label>
                                    <select class="form-select" id="xuat_xu" name="xuat_xu" required>
                                        <option value="">Chọn xuất xứ</option>
                                        <option value="Trong nước">Trong nước</option>
                                        <option value="Nhập khẩu">Nhập khẩu</option>
                                    </select>
                                </div>

                                <!-- Số ghế -->
                                <div class="col-md-6 mb-3">
                                    <label for="so_ghe_ngoi" class="form-label required">Số ghế ngồi</label>
                                    <select class="form-select" id="so_ghe_ngoi" name="so_ghe_ngoi" required>
                                        <option value="">Chọn số ghế</option>
                                        <option value="4">4 chỗ</option>
                                        <option value="5">5 chỗ</option>
                                        <option value="7">7 chỗ</option>
                                        <option value="9">9 chỗ</option>
                                    </select>
                                </div>

                                <!-- Số km -->
                                <div class="col-md-6 mb-3">
                                    <label for="so_km" class="form-label required">Số KM đã đi</label>
                                    <input type="number" class="form-control" id="so_km" name="so_km" min="0" required>
                                </div>

                                <!-- Nhiên liệu -->
                                <div class="col-md-6 mb-3">
                                    <label for="nhien_lieu" class="form-label required">Nhiên liệu</label>
                                    <select class="form-select" id="nhien_lieu" name="nhien_lieu" required>
                                        <option value="">Chọn nhiên liệu</option>
                                        <option value="Xăng">Xăng</option>
                                        <option value="Dầu">Dầu</option>
                                        <option value="Điện">Điện</option>
                                        <option value="Hybrid">Hybrid</option>
                                    </select>
                                </div>

                                <!-- Hộp số -->
                                <div class="col-md-6 mb-3">
                                    <label for="hop_so" class="form-label required">Hộp số</label>
                                    <select class="form-select" id="hop_so" name=" hop_so" required>
                                        <option value="">Chọn hộp số</option>
                                        <option value="Số sàn">Số sàn</option>
                                        <option value="Số tự động">Số tự động</option>
                                    </select>
                                </div>

                                <!-- Giá bán -->
                                <div class="col-md-6 mb-3">
                                    <label for="gia_ban" class="form-label required">Giá bán</label>
                                    <input type="number" class="form-control" id="gia_ban" name="gia_ban" min="0"
                                        required>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="mo_ta" class="form-label">Mô tả chi tiết</label>
                                    <textarea class="form-control" id="mo_ta" name="mo_ta" rows="5"
                                        placeholder="Nhập mô tả chi tiết về xe (tình trạng, tính năng đặc biệt, lịch sử sử dụng...)"></textarea>
                                    <small class="form-text text-muted">
                                        Gợi ý: Mô tả càng chi tiết sẽ giúp xe dễ bán hơn
                                    </small>
                                </div>

                                <!-- Ảnh xe -->
                                <div class="col-md-12 mb-3">
                                    <label for="anh_xe" class="form-label required">Ảnh xe</label>
                                    <input type="file" class="form-control" id="anh_xe" name="anh_xe[]" multiple
                                        required>
                                    <div class="preview-images" id="preview-images"></div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm Xe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Xử lý hiển thị ảnh xem trước
        document.getElementById('anh_xe').addEventListener('change', function (event) {
            const previewContainer = document.getElementById('preview-images');
            previewContainer.innerHTML = ''; // Xóa ảnh cũ
            const files = event.target.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                }

                reader.readAsDataURL(file);
            }
        });

        // Xử lý xác thực form
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();



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