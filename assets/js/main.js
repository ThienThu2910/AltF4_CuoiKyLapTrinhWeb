// assets/js/main.js

document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('darkModeToggle');
    const toggleIcon = document.getElementById('darkModeIcon');
    const htmlTag = document.documentElement;

    // 1. Kiểm tra trạng thái giao diện đã lưu từ trước
    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlTag.setAttribute('data-bs-theme', savedTheme);
    updateIcon(savedTheme);

    // 2. Lắng nghe sự kiện click nút đổi giao diện
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const currentTheme = htmlTag.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            // Áp dụng theme mới
            htmlTag.setAttribute('data-bs-theme', newTheme);
            // Lưu vào localStorage
            localStorage.setItem('theme', newTheme);
            // Cập nhật icon tương ứng
            updateIcon(newTheme);
        });
    }

    // 3. Hàm cập nhật Icon Mặt Trăng / Mặt Trời
    function updateIcon(theme) {
        if (!toggleIcon) return;
        if (theme === 'dark') {
            toggleIcon.className = 'bi bi-sun-fill text-warning';
        } else {
            toggleIcon.className = 'bi bi-moon-fill';
        }
    }
});