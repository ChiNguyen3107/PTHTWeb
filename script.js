
document.addEventListener('DOMContentLoaded', function () {
    const accountBtn = document.querySelector('.account-btn');
    const dropdownContent = document.querySelector('.dropdown-content');

    accountBtn.addEventListener('click', function (e) {
        e.preventDefault();
        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    });

    // Đóng dropdown khi click bên ngoài
    window.addEventListener('click', function (e) {
        if (!e.target.matches('.account-btn') && !e.target.closest('.dropdown-content')) {
            dropdownContent.style.display = 'none';
        }
    });
});

function toggleDropdown(element) {
    const ul = element.nextElementSibling;
    ul.classList.toggle('show');
    const icon = element.querySelector('i');
    icon.classList.toggle('fa-chevron-down');
    icon.classList.toggle('fa-chevron-up');
}




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



let isFiltering = false;

function filterCars(id) {
    if (isFiltering) return;
    isFiltering = true;

    // Hiển thị loading
    const listings = document.querySelector('.listings');
    listings.innerHTML = `
        <div class="loading">
            <div class="loading-spinner"></div>
            <p>Đang tải kết quả...</p>
        </div>
    `;

    // Lấy tất cả dữ liệu từ form
    const formData = new FormData(document.getElementById('filterForm'));
    if (id == 0) {

        formData.append('id', id);
        fetch('filter_ajax.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                // Cập nhật phần hiển thị kết quả
                document.querySelector('.listings').innerHTML = data;
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                isFiltering = false; // Reset trạng thái lọc
            });
    }
    else {
        // Gửi request Ajax
        formData.append('id', id);
        fetch('../filter_ajax.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                // Cập nhật phần hiển thị kết quả
                document.querySelector('.listings').innerHTML = data;
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                isFiltering = false; // Reset trạng thái lọc
            });
    }

}

function resetFilter() {
    // Reset tất cả các trường trong form
    document.getElementById('filterForm').reset();
    // Tải lại danh sách xe mặc định
    filterCars();
}


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

function searchCars() {
    var query = document.getElementById('searchInput').value;

    fetch('search.php?query=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            var listingsContainer = document.querySelector('.listings');
            listingsContainer.innerHTML = ''; // Xóa kết quả cũ

            data.forEach(car => {
                var listingHtml = `
                    <div class="listing">
                        <div class="image-container">
                            <img src="uploads/${car.anh_dai_dien}" alt="${car.ten_hang_xe} ${car.ten_dong_xe}" class="active" />
                        </div>
                        <div class="details">
                            <h3>${car.ten_hang_xe} ${car.ten_dong_xe} ${car.phien_ban}</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <i class="fas fa-calendar-alt"></i> ${car.nam_san_xuat}
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-tachometer-alt"></i> ${Number(car.odo).toLocaleString()} km
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-gas-pump"></i> ${car.nhien_lieu}
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-cogs"></i> ${car.hop_so}
                                </div>
                            </div>
                            <div class="price">
                                <i class="fas fa-tag"></i> ${Number(car.gia).toLocaleString()} VNĐ
                            </div>
                            <a href="car_detail.php?id=${car.id}">Xem chi tiết</a>
                        </div>
                    </div>
                `;
                listingsContainer.innerHTML += listingHtml;
            });

            if (data.length === 0) {
                listingsContainer.innerHTML = '<p>Không tìm thấy kết quả nào.</p>';
            }
        })
        .catch(error => console.error('Error:', error));
}

// Thêm sự kiện lắng nghe cho phím Enter trong ô tìm kiếm
document.getElementById('searchInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        searchCars();
    }
});


document.getElementById('sort-select').addEventListener('change', function () {
    const sortValue = this.value;
    const listings = document.querySelector('.listings');

    // Hiển thị loading
    listings.innerHTML = `
        <div class="loading">
            <div class="loading-spinner"></div>
            <p>Đang sắp xếp...</p>
        </div>
    `;

    // Gửi request Ajax đến sort.php
    fetch('sort.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'sort_type=' + sortValue
    })
        .then(response => response.text())
        .then(data => {
            // Cập nhật nội dung với kết quả đã sắp xếp
            listings.innerHTML = data;
            // Khởi tạo lại image slider cho các listing mới
            setupImageSlider();
        })
        .catch(error => {
            console.error('Error:', error);
            listings.innerHTML = '<p>Đã xảy ra lỗi trong quá trình sắp xếp.</p>';
        });
});

// Hàm khởi tạo slider cho hình ảnh
function setupImageSlider() {
    const sliders = document.querySelectorAll('.image-container');
    sliders.forEach(slider => {
        const images = slider.querySelectorAll('img');
        let currentIndex = 0;

        // Hiển thị hình ảnh đầu tiên
        images[currentIndex].classList.add('active');

        // Thêm sự kiện cho nút trước
        slider.querySelector('.prev-btn').addEventListener('click', () => {
            images[currentIndex].classList.remove('active');
            currentIndex = (currentIndex === 0) ? images.length - 1 : currentIndex - 1;
            images[currentIndex].classList.add('active');
        });

        // Thêm sự kiện cho nút tiếp theo
        slider.querySelector('.next-btn').addEventListener('click', () => {
            images[currentIndex].classList.remove('active');
            currentIndex = (currentIndex === images.length - 1) ? 0 : currentIndex + 1;
            images[currentIndex].classList.add('active');
        });
    });
}

function calculateAndRedirect(event) {
    event.preventDefault();

    var pickupDateStr = $("#pickup-date").val();
    var returnDateStr = $("#return-date").val();

    // Chuyển đổi chuỗi ngày thành đối tượng Date
    var pickupDate = $.datepicker.parseDate("dd/mm/yy", pickupDateStr);
    var returnDate = $.datepicker.parseDate("dd/mm/yy", returnDateStr);

    // Tính số ngày
    if (pickupDate && returnDate) {
        var timeDiff = returnDate - pickupDate; // Tính chênh lệch thời gian
        var daysDiff = timeDiff / (1000 * 3600 * 24); // Chuyển đổi từ milliseconds sang ngày

        // Gửi giá trị 'daysDiff' tới trang khác qua API fetch
        fetch('Order_Form.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
            // Truyền giá trị daysDiff qua URL
            body: JSON.stringify({ days: daysDiff })
        })
        .then(response => response.json())  // Nếu backend trả về JSON
        .then(data => {
            // Xử lý dữ liệu nhận được từ backend
            console.log(data);
            // Chuyển hướng sang trang mới với tham số 'day' trong URL
            window.location.href = `Order_Form.php?day=${daysDiff}`;
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert("Vui lòng chọn cả ngày nhận và ngày trả.");
    }
}

