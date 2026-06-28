CREATE DATABASE IF NOT EXISTS quan_ly_khach_san CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quan_ly_khach_san;

-- Bảng Quản trị viên / Nhân viên
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    ho_ten VARCHAR(100) NOT NULL,
    quyen VARCHAR(20) DEFAULT 'nhan_vien'
);

-- Bảng Danh sách phòng khách sạn
CREATE TABLE phong (
    id INT AUTO_INCREMENT PRIMARY KEY,
    so_phong VARCHAR(10) NOT NULL UNIQUE,
    loai_phong VARCHAR(50) NOT NULL,
    gia_phong DECIMAL(10,2) NOT NULL,
    trang_thai VARCHAR(20) DEFAULT 'trong',
    hinh_anh VARCHAR(255) NULL
);

-- Bảng Thông tin khách hàng
CREATE TABLE khach_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ho_ten VARCHAR(100) NOT NULL,
    cccd VARCHAR(20) NOT NULL UNIQUE,
    so_dien_thoai VARCHAR(15) NOT NULL,
    email VARCHAR(100) NULL
);

-- Bảng Đặt phòng
CREATE TABLE dat_phong (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_khach_hang INT NOT NULL,
    id_phong INT NOT NULL,
    ngay_den DATETIME NOT NULL,
    ngay_di DATETIME NOT NULL,
    tong_tien DECIMAL(10,2) NOT NULL,
    trang_thai VARCHAR(20) DEFAULT 'chua_thanh_toan',
    FOREIGN KEY (id_khach_hang) REFERENCES khach_hang(id) ON DELETE CASCADE,
    FOREIGN KEY (id_phong) REFERENCES phong(id) ON DELETE CASCADE
);

-- Thêm 1 tài khoản Admin mặc định (Mật khẩu đã băm của chuỗi: admin123)
INSERT INTO users (username, password, ho_ten, quyen) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trưởng Nhóm Admin', 'admin');