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
        <select id="hang_xe" name="hang_xe" required onchange="updateDongXe()">
            <option value="">Chọn hãng xe</option>
            <option value="Toyota">Toyota</option>
            <option value="Honda">Honda</option>
            <option value="Hyundai">Hyundai</option>
            <option value="KIA">KIA</option>
            <option value="Mazda">Mazda</option>
            <option value="Ford">Ford</option>
            <option value="VinFast">VinFast</option>
            <option value="Mitsubishi">Mitsubishi</option>
            <option value="Suzuki">Suzuki</option>
            <option value="Nissan">Nissan</option>
            <option value="Mercedes-Benz">Mercedes-Benz</option>
            <option value="BMW">BMW</option>
            <option value="Audi">Audi</option>
            <option value="Lexus">Lexus</option>
            <option value="Peugeot">Peugeot</option>
            <option value="Volkswagen">Volkswagen</option>
            <option value="Chevrolet">Chevrolet</option>
            <option value="Subaru">Subaru</option>
            <option value="Porsche">Porsche</option>
            <option value="Volvo">Volvo</option>
            <option value="Land Rover">Land Rover</option>
            <option value="Jaguar">Jaguar</option>
            <option value="Mini">Mini</option>
            <option value="Jeep">Jeep</option>
            <option value="Isuzu">Isuzu</option>
            <option value="Baic">Baic</option>
            <option value="Khác">Hãng khác</option>
        </select>

        <label for="dong_xe">Dòng xe:</label>
        <select id="dong_xe" name="dong_xe" required>
            <option value="">Chọn dòng xe</option>
        </select>

        <label for="phien_ban">Phiên bản:</label>
        <input type="text" id="phien_ban" name="phien_ban">

        <label for="nam_san_xuat">Năm sản xuất:</label>
        <input type="number" id="nam_san_xuat" name="nam_san_xuat" required min="2000" max="2024">

        <label for="kieu_dang">Kiểu dáng:</label>
        <select id="kieu_dang" name="kieu_dang">
            <option value="">Chọn kiểu dáng</option>
            <option value="Sedan">Sedan</option>
            <option value="SUV">SUV</option>
            <option value="Hatchback">Hatchback</option>
            <option value="MPV">MPV</option>
            <option value="Crossover">Crossover</option>
        </select>

        <label for="xuat_xu">Xuất xứ :</label>
        <select id="xuat_xu" name="xuat_xu">
            <option value="">Chọn xuất xứ</option>
            <option value="Việt Nam">Việt Nam</option>
            <option value="Nhật Bản">Nhật Bản</option>
            <option value="Hàn Quốc">Hàn Quốc</option>
            <option value="Mỹ">Mỹ</option>
            <option value="Đức">Đức</option>
            <option value="Anh">Anh</option>
            <option value="Pháp">Pháp</option>
            <option value="Khác">Xuất xứ khác</option>
        </select>

        <label for="so_ghe_ngoi">Số ghế ngồi:</label>
        <select id="so_ghe_ngoi" name="so_ghe_ngoi">
            <option value="">Chọn số ghế ngồi</option>
            <option value="2">2</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select>

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
            <option value="Số sàn">Số sàn</option>
            <option value="Số tự động"> Số tự động</option>
            <option value="Số bán tự động">Số bán tự động</option>
        </select>

        <label for="gia">Giá:</label>
        <input type="number" id="gia" name="gia" min="0">

        <label for="so_km">Số km đã đi:</label>
        <input type="number" id="so_km" name="so_km" min="0">

        <label for="mo_ta">Mô tả:</label>
        <textarea id="mo_ta" name="mo_ta"></textarea>

        <label for="anh_xe">Ảnh xe:</label>
        <input type="file" id="anh_xe" name="anh_xe[]" multiple>

        <input type="submit" value="Thêm Xe Mới">
    </form>
    <script>
        const carModels = {
            "Toyota": ["Camry", "Corolla", "RAV4", "Vios", "Innova", "Land Cruiser"],
            "Honda": ["Civic", "Accord", "CR-V", "City", "HR-V", "Pilot"],
            "Hyundai": ["Elantra", "Tucson", "Santa Fe", "Kona", "Accent", "i10"],
            "KIA": ["Seltos", "Sportage", "Sorento", "Cerato", "Morning", "Carnival"],
            "Mazda": ["Mazda3", "CX-5", "CX-9", "Mazda6", "MX-5"],
            "Ford": ["Focus", "Fiesta", "Mondeo", "Explorer", "Ranger"],
            "VinFast": ["Fadil", "Klara", "Impes", "President", "Lux A2.0"],
            "Mitsubishi": ["Outlander", "Pajero", "Lancer", "Xpander", "Attrage"],
            "Suzuki": ["Swift", "Ertiga", "Ciaz", "Vitara", "Jimny"],
            "Nissan": ["Altima", "Teana", "X-Trail", "Navara", "Sunny"],
            "Mercedes-Benz": ["C-Class", "E-Class", "S-Class", "GLC", "GLE"],
            "BMW": ["3 Series", "5 Series", "7 Series", "X1", "X5"],
            "Audi": ["A4", "A6", "A8", "Q2", "Q5"],
            "Lexus": ["ES", "GS", "LS", "NX", "RX"],
            "Peugeot": ["208", "308", "508", "2008", "3008"],
            "Volkswagen": ["Golf", "Jetta", "Passat", "Tiguan", "Polo"],
            "Chevrolet": ["Cruze", "Malibu", "Captiva", "Trailblazer", "Colorado"],
            "Subaru": ["Impreza", "Forester", "Outback", "BRZ", "WRX"],
            "Porsche": ["911", "Cayman", "Boxster", "Macan", "Cayenne"],
            "Volvo": ["S60", "S90", "V60", "V90", "XC60"],
            "Land Rover": ["Range Rover", "Discovery", "Defender", "Freelander", "Evoque"],
            "Jaguar": ["XE", "XF", "XJ", "F-Pace", "E-Pace"],
            "Mini": ["Cooper", "Countryman", "Clubman", "Paceman", "Roadster"],
            "Jeep": ["Wrangler", "Grand Cherokee", "Renegade", "Compass", "Patriot"],
            "Isuzu": ["D-Max", "MU-X", "Elf", "Forward", "Gigamax"],
            "Baic": ["Senova", "Huansu", "Weiwang", "BJ40", "BJ20"]
        };

        function updateDongXe() {
            const hangXe = document.getElementById("hang_xe").value;
            const dongXeSelect = document.getElementById("dong_xe");
            dongXeSelect.innerHTML = "<option value=''>Chọn dòng xe</option>";
            if (hangXe in carModels) {
                carModels[hangXe].forEach(model => {
                    const option = document.createElement("option");
                    option.value = model;
                    option.text = model;
                    dongXeSelect.appendChild(option);
                });
            }
        }
    </script>
</body>

</html>