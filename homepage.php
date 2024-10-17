<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pthtweb";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>

<html>

<head>
    <title>
        CaR88 Vietnam
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <script>
        function updateValue(id, value) {
            if (id === 'priceValue' && value > 1000) {
                document.getElementById(id).innerText = (value / 1000).toFixed(1) + ' tỷ';
            } else {
                document.getElementById(id).innerText = value + ' triệu';
            }
        }

        function toggleDropdown(element) {
            const ul = element.nextElementSibling;
            ul.style.display = ul.style.display === 'none' || ul.style.display === '' ? 'block' : 'none';
        }
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
        }

        .header .logo img {
            height: 40px;
        }

        .header .nav {
            display: flex;
            gap: 20px;
        }

        .header .nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .header .nav a:hover {
            color: #ff7f00;
            /* Đổi màu chữ khi hover */
        }

        .header .actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header .actions .btn {
            background-color: #ff7f00;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .header .actions .btn:hover {
            background-color: #ff9000;
            transform: scale(1.05);
            /* Phóng to nút khi hover */
        }

        .header .actions .contact,
        .header .actions .account {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header .actions .contact i,
        .header .actions .account i {
            color: #ff7f00;
        }

        .banner {
            display: flex;
            gap: 10px;
            padding: 20px;
            background-color: #fff;
        }

        .banner img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }

        .banner img:hover {
            transform: scale(1.05);
            /* Phóng to ảnh khi hover */
        }

        .main {
            display: flex;
            padding: 20px;
        }

        .sidebar {
            width: 250px;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-right: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h3 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .sidebar .filter h4 {
            font-size: 16px;
            margin-bottom: 10px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar .filter ul {
            list-style: none;
            padding: 0;
            display: none;
        }

        .sidebar .filter ul li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .filter ul li i {
            color: #333;
        }

        .content {
            flex: 1;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .content p {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .content .search-bar {
            display: flex;
            margin-bottom: 20px;
        }

        .content .search-bar input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .content .search-bar button {
            padding: 10px 20px;
            background-color: #ff7f00;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .content .search-bar button:hover {
            background-color: #ff9000;
            transform: scale(1.05);
            /* Phóng to nút khi hover */
        }

        .content .sort {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .content .sort .sort-by {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .content .sort .sort-by select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .content .listings {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .content .listings .listing {
            width: calc(50% - 10px);
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .content .listings .listing:hover {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
            /* Di chuyển mục lên khi hover */
        }

        .content .listings .listing img {
            width: 100%;
            height: auto;
        }

        .content .listings .listing .details {
            padding: 20px;
        }

        .content .listings .listing .details h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .content .listings .listing .details p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .content .listings .listing .details .info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #666;
        }

        .content .listings .listing .details .info i {
            color: #ff7f00;
        }

        .slider-container {
            margin-bottom: 20px;
        }

        .slider-container input[type="range"] {
            width: 100%;
        }

        .slider-values {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">
            <a href="homepage.php" title="CaR88 Vietnam">
                <svg xmlns="http://www.w3.org/2000/svg" width="150" height="40" viewBox="0 0 150 40">
                    <rect x="0" y="0" width="150" height="40" rx="5" ry="5" fill="#ff7f00" />
                    <text x="10" y="28" font-family="Arial, sans-serif" font-size="22" font-weight="bold"
                        fill="#ffffff"> CaR88 </text>
                    <circle cx="130" cy="20" r="8" fill="#ffffff" />
                    <circle cx="130" cy="20" r="3" fill="#ff7f00" />
                </svg>
            </a>
        </div>
        <div class="nav">
            <a href="#">
                Mua xe
            </a>
            <a href="#">
                Bán xe
            </a>
            <a href="#">
                Giới thiệu
            </a>
            <a href="#">
                Tin tức
            </a>
            <a href="#">
                Vay mua xe
            </a>
        </div>
        <div class="actions">
            <button class="btn">
                ĐĂNG TIN
            </button>
            <div class="contact">
                <i class="fas fa-phone-alt">
                </i>
                <span>
                    0938119439
                </span>
            </div>
            <div class="account">
                <i class="fas fa-user">
                </i>
                <span>
                    Tài khoản
                </span>
            </div>
        </div>
    </div>
    <div class="banner">

        <img src="image/banner1.jpg" alt="banner1">
        <img src="image/banner2.jpg" alt="banner2">
        <img src="image/banner3.jpg" alt="banner3">
        <img src="image/banner4.jpg" alt="banner4">
    </div>
    <div class="main">
        <div class="sidebar">
            <h3>
                BỘ LỌC
            </h3>
            <div class="filter">
                <h4 onclick="toggleDropdown(this)">
                    HÃNG XE
                    <i class="fas fa-chevron-down"></i>
                </h4>
                <ul>
                    <li><input type="checkbox" id="toyota"> <label for="toyota">Toyota</label></li>
                    <li><input type="checkbox" id="honda"> <label for="honda">Honda</label></li>
                    <li><input type="checkbox" id="hyundai"> <label for="hyundai">Hyundai</label></li>
                    <li><input type="checkbox" id="kia"> <label for="kia">KIA</label></li>
                    <li><input type="checkbox" id="mazda"> <label for="mazda">Mazda</label></li>
                    <li><input type="checkbox" id="ford"> <label for="ford">Ford</label></li>
                    <li><input type="checkbox" id="vinfast"> <label for="vinfast">VinFast</label></li>
                    <li><input type="checkbox" id="mitsubishi"> <label for="mitsubishi">Mitsubishi</label></li>
                    <li><input type="checkbox" id="suzuki"> <label for="suzuki">Suzuki</label></li>
                    <li><input type="checkbox" id="nissan"> <label for="nissan">Nissan</label></li>
                    <li><input type="checkbox" id="mercedes"> <label for="mercedes">Mercedes-Benz</label></li>
                    <li><input type="checkbox" id="bmw"> <label for="bmw">BMW</label></li>
                    <li><input type="checkbox" id="audi"> <label for="audi">Audi</label></li>
                    <li><input type="checkbox" id="lexus"> <label for="lexus">Lexus</label></li>
                    <li><input type="checkbox" id="peugeot"> <label for="peugeot">Peugeot</label></li>
                    <li><input type="checkbox" id="other-brand"> <label for="other-brand">Hãng khác</label></li>
                </ul>
            </div>

            <div class="filter">
                <h4 onclick="toggleDropdown(this)">
                    ĐỊA ĐIỂM
                    <i class="fas fa-chevron-down"></i>
                </h4>
                <ul>
                    <li><input type="checkbox" id="hanoi"> <label for="hanoi">Hà Nội</label></li>
                    <li><input type="checkbox" id="hcm"> <label for="hcm">TP. Hồ Chí Minh</label></li>
                    <li><input type="checkbox" id="danang"> <label for="danang">Đà Nẵng</label></li>
                    <li><input type="checkbox" id="cantho"> <label for="cantho">Cần Thơ</label></li>
                </ul>
            </div>
            <div class="filter">
                <h4 onclick="toggleDropdown(this)">
                    GIÁ
                    <i class="fas fa-chevron-down">
                    </i>
                </h4>
                <div class="slider-container">
                    <input id="priceRange" max="5000" min="50" oninput="updateValue('priceValue', this.value)" step="50"
                        type="range" value="50" />
                    <div class="slider-values">
                        <span id="priceValue">
                            50 triệu
                        </span>
                        <span>
                            5 tỷ
                        </span>
                    </div>
                </div>
            </div>
            <div class="filter">
                <h4 onclick="toggleDropdown(this)">
                    NĂM SẢN XUẤT
                    <i class="fas fa-chevron-down">
                    </i>
                </h4>
                <div class="slider-container">
                    <input id="yearRange" max="2024" min="2000" oninput="updateValue('yearValue', this.value)" step="1"
                        type="range" value="2000" />
                    <div class="slider-values">
                        <span id="yearValue">
                            2000
                        </span>
                        <span>
                            2024
                        </span>
                    </div>
                </div>
            </div>
            <div class="filter">
                <h4 onclick="toggleDropdown(this)">
                    SỐ KM
                    <i class="fas fa-chevron-down">
                    </i>
                </h4>
                <div class="slider-container">
                    <input id="kmRange" max="500000" min="0" oninput="updateValue('kmValue', this.value)" step="1000"
                        type="range" value="0" />
                    <div class="slider-values">
                        <span id="kmValue">
                            0 km
                        </span>
                        <span>
                            500000 km
                        </span>
                    </div>
                </div>
            </div>
            <div class="filter">
                <h4 onclick="toggleDropdown(this)">
                    KIỂU DÁNG
                    <i class="fas fa-chevron-down"></i>
                </h4>
                <ul>
                    <li><input type="checkbox" id="sedan"> <label for="sedan">Sedan</label></li>
                    <li><input type="checkbox" id="suv"> <label for="suv">SUV</label></li>
                    <li><input type="checkbox" id="hatchback"> <label for="hatchback">Hatchback</label></li>
                    <li><input type="checkbox" id="mpv"> <label for="mpv">MPV</label></li>
                </ul>
            </div>
            <div class="filter">
                <h4 onclick="toggleDropdown(this)">
                    NHIÊN LIỆU
                    <i class="fas fa-chevron-down"></i>
                </h4>
                <ul>
                    <li><input type="checkbox" id="petrol"> <label for="petrol">Xăng</label></li>
                    <li><input type="checkbox" id="diesel"> <label for="diesel">Dầu</label></li>
                    <li><input type="checkbox" id="hybrid"> <label for="hybrid">Hybrid</label></li>
                    <li><input type="checkbox" id="electric"> <label for="electric">Điện</label></li>
                </ul>
            </div>
            <div class="filter">
                <h4 onclick="toggleDropdown(this)">
                    HỘP SỐ
                    <i class="fas fa-chevron-down"></i>
                </h4>
                <ul>
                    <li><input type="checkbox" id="automatic"> <label for="automatic">Số tự động</label></li>
                    <li><input type="checkbox" id="manual"> <label for="manual">Số sàn</label></li>
                </ul>
            </div>
            <div class="filter">
                <h4 onclick="toggleDropdown(this)">
                    MÀU SẮC
                    <i class="fas fa-chevron-down"></i>
                </h4>
                <ul>
                    <li><input type="checkbox" id="white"> <label for="white">Trắng</label></li>
                    <li><input type="checkbox" id="black"> <label for="black">Đen</label></li>
                    <li><input type="checkbox" id="silver"> <label for="silver">Bạc</label></li>
                    <li><input type="checkbox" id="red"> <label for="red">Đỏ</label></li>
                    <li><input type="checkbox" id="blue"> <label for="blue">Xanh dương</label></li>
                    <li><input type="checkbox" id="gray"> <label for="gray">Xám</label></li>
                    <li><input type="checkbox" id="brown"> <label for="brown">Nâu</label></li>
                    <li><input type="checkbox" id="other-color"> <label for="other-color">Màu khác</label></li>
                </ul>
            </div>
            <div class="filter">
                <h4 onclick="toggleDropdown(this)">
                    SỐ CHỖ NGỒI
                    <i class="fas fa-chevron-down"></i>
                </h4>
                <ul>
                    <li><input type="checkbox" id="2seats"> <label for="2seats">2 chỗ</label></li>
                    <li><input type="checkbox" id="4seats"> <label for="4seats">4 chỗ</label></li>
                    <li><input type="checkbox" id="5seats"> <label for="5seats">5 chỗ</label></li>
                    <li><input type="checkbox" id="7seats"> <label for="7seats">7 chỗ</label></li>
                    <li><input type="checkbox" id="9seats"> <label for="9seats">9 chỗ</label></li>
                    <li><input type="checkbox" id="over9seats"> <label for="over9seats">Trên 9 chỗ</label></li>
                </ul>
            </div>
        </div>
        <div class="content">
            <h2>
                Mua bán oto - Xe ô tô cũ - Xe hơi mới toàn quốc
            </h2>
            <p>
                Có 1088 tin bán xe giá từ 103 triệu đến 10 tỷ 390 triệu cập nhập mới nhất 10/2024
            </p>
            <div class="search-bar">
                <input placeholder="Tìm kiếm xe theo Hãng xe, Dòng xe hoặc Từ khóa" type="text" />
                <button>
                    Tìm kiếm
                </button>
            </div>
            <div class="sort">
                <div class="sort-by">
                    <span>
                        SẮP XẾP:
                    </span>
                    <select>
                        <option>
                            Liên quan nhất
                        </option>
                        <option>
                            Giá thấp nhất
                        </option>
                        <option>
                            Giá cao nhất
                        </option>
                    </select>
                </div>
            </div>
            <div class="listings">
                <?php
                $sql = "SELECT xe.*, GROUP_CONCAT(anh_xe.url_anh) as all_images 
            FROM xe 
            LEFT JOIN anh_xe ON xe.id = anh_xe.xe_id 
            GROUP BY xe.id 
            LIMIT 10";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $images = explode(',', $row["all_images"]);
                        echo '<div class="listing">';
                        echo '<div class="image-container">';
                        foreach ($images as $index => $image) {
                            echo '<img src="' . $image . '" alt="' . $row["hang_xe"] . ' ' . $row["dong_xe"] . '" ' . ($index == 0 ? 'class="active"' : '') . '/>';
                        }
                        echo '<button class="prev-btn">&#10094;</button>';
                        echo '<button class="next-btn">&#10095;</button>';
                        echo '</div>';
                        echo '<div class="details">';
                        echo '<h3>' . $row["hang_xe"] . ' ' . $row["dong_xe"] . ' ' . $row["phien_ban"] . '</h3>';
                        echo '<div class="info-grid">';
                        echo '<div class="info-item"><i class="fas fa-calendar-alt"></i> ' . $row["nam_san_xuat"] . '</div>';
                        echo '<div class="info-item"><i class="fas fa-tachometer-alt"></i> ' . number_format($row["so_km"]) . ' km</div>';
                        echo '<div class="info-item"><i class="fas fa-gas-pump"></i> ' . $row["nhien_lieu"] . '</div>';
                        echo '<div class="info-item"><i class="fas fa-cogs"></i> ' . $row["hop_so"] . '</div>';
                        echo '</div>';
                        echo '<div class="price"><i class="fas fa-tag"></i> ' . number_format($row["gia"]) . ' VNĐ</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "Không có xe nào được tìm thấy";
                }
                ?>
            </div>
        </div>
    </div>
    <script>
        function setupImageSlider() {
            const listings = document.querySelectorAll('.listing');

            listings.forEach(listing => {
                const images = listing.querySelectorAll('.image-container img');
                const prevBtn = listing.querySelector('.prev-btn');
                const nextBtn = listing.querySelector('.next-btn');
                let currentImageIndex = 0;

                prevBtn.addEventListener('click', () => {
                    currentImageIndex--;
                    if (currentImageIndex < 0) {
                        currentImageIndex = images.length - 1;
                    }
                    updateImage(images, currentImageIndex);
                });

                nextBtn.addEventListener('click', () => {
                    currentImageIndex++;
                    if (currentImageIndex >= images.length) {
                        currentImageIndex = 0;
                    }
                    updateImage(images, currentImageIndex);
                });

                function updateImage(images, index) {
                    images.forEach((image, i) => {
                        image.classList.toggle('active', i === index);
                    });
                }
            });
        }

        setupImageSlider();
    </script>
</body>

</html>

<?php
$conn->close();
?>