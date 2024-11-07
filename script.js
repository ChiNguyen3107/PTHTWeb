
document.addEventListener('DOMContentLoaded', function() {
    const accountBtn = document.querySelector('.account-btn');
    const dropdownContent = document.querySelector('.dropdown-content');

    accountBtn.addEventListener('click', function(e) {
        e.preventDefault();
        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    });

    // Đóng dropdown khi click bên ngoài
    window.addEventListener('click', function(e) {
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
function goBack() {
    window.history.back(); // Quay lại trang trước
}