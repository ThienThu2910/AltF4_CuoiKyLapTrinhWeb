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

    // ==========================================================================
    // 4. BỔ SUNG: XỬ LÝ NAVBAR DÍNH KHI CUỘN XUỐNG SÂU (STICKY/FIXED ON SCROLL)
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

    // ==========================================================================
    // 5. BỔ SUNG: XỬ LÝ NÚT BACK TO TOP (DI CHUYỂN LÊN ĐẦU TRANG)
    // ==========================================================================
    const backToTopBtn = document.getElementById("btnBackToTop");

    if (backToTopBtn) {
        // Lắng nghe sự kiện cuộn chuột để ẩn/hiện nút bấm
        window.addEventListener('scroll', () => {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                backToTopBtn.style.setProperty('display', 'block', 'important');
            } else {
                backToTopBtn.style.setProperty('display', 'none', 'important');
            }
        });

        // Click để cuộn mượt mà lên đầu trang
        backToTopBtn.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }

    // ==========================================================================
    // 6. BỔ SUNG: HIỆU ỨNG LƯỚT ĐẾN ĐÂU HIỆN CHỮ ĐẾN ĐÓ (SCROLL REVEAL ANIMATION)
    // ==========================================================================
    const revealElements = document.querySelectorAll('.scroll-reveal');
    
    if (revealElements.length > 0) {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                // Khi khối nội dung lọt vào tầm nhìn của người dùng 15%
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    // Gỡ bỏ theo dõi sau khi đã hiện (chữ hiện lên hẳn luôn, lướt qua lại không bị ẩn lại)
                    observer.unobserve(entry.target); 
                }
            });
        }, {
            threshold: 0.15 // Kích hoạt khi nhìn thấy 15% diện tích khối
        });

        revealElements.forEach(element => {
            revealObserver.observe(element);
        });
    }
});