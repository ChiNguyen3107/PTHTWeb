
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
