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
document.addEventListener('DOMContentLoaded', () => {
    // ==========================================================================
    // 1. XỬ LÝ CHỨC NĂNG ĐỔI GIAO DIỆN DARK MODE (ĐÃ CÓ SẴN)
    // ==========================================================================
    const modeToggleBtn = document.getElementById('darkModeToggle');
    const modeIcon = document.getElementById('darkModeIcon');
    const htmlElement = document.documentElement;

    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-bs-theme', savedTheme);
    updateIcon(savedTheme);

    if (modeToggleBtn) {
        modeToggleBtn.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon(newTheme);
        });
    }

    function updateIcon(theme) {
        if (!modeIcon) return;
        if (theme === 'dark') {
            modeIcon.className = 'bi bi-sun-fill fs-5 text-warning';
        } else {
            modeIcon.className = 'bi bi-moon-stars-fill fs-5';
        }
    }

    // ==========================================================================
    // 2. BỔ SUNG: XỬ LÝ NAVBAR DÍNH KHI CUỘN XUỐNG SÂU (STICKY/FIXED ON SCROLL)
    // ==========================================================================
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', () => {
        // Nếu cuộn chuột xuống sâu hơn 120px (qua khỏi phần đầu của banner chính)
        if (window.scrollY > 120) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
});