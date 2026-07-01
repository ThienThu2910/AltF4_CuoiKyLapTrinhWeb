<?php
require_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Khách Sạn Luxury</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container">
    <header class="header">
        <div class="header-logo">
            <i class="fa-solid fa-hotel"></i>
            <div>
                <h1>HỆ THỐNG QUẢN LÝ KHÁCH SẠN LUXURY</h1>
                <p>Phân hệ khách hàng đặt phòng & Giao diện quản trị Admin trực quan</p>
            </div>
        </div>
    </header>

    <div class="main-layout">
        
        <aside class="sidebar-form">
            <div class="booking-card">
                <h2 class="card-title"><i class="fa-solid fa-calendar-days"></i> ĐẶT PHÒNG TRỰC TUYẾN</h2>
                
                <form id="bookingForm">
                    <div class="form-group">
    <label>Chọn Loại Phòng</label>
    <select name="room_id" class="form-control" required>
        <option value="">-- Vui lòng chọn phòng --</option>
        
        <?php
        // 1. Viết câu lệnh lấy danh sách phòng từ Database
        // (Bạn hãy thay đổi tên bảng 'rooms' và các cột cho đúng với thiết kế SQL của bạn nhé)
        $sql_rooms = "SELECT id, room_number, room_type, price_per_night FROM rooms";
        $result_rooms = $conn->query($sql_rooms);

        // 2. Lặp qua từng phòng và in ra thẻ <option>
        if ($result_rooms && $result_rooms->num_rows > 0) {
            while ($room = $result_rooms->fetch_assoc()) {
                // Định dạng lại giá tiền cho đẹp (VD: 1200000 -> 1.200.000)
                $formatted_price = number_format($room['price_per_night'], 0, ',', '.');
                
                // Hiển thị ra màn hình: Loại phòng (Số phòng - Giá tiền)
                // Nhưng giá trị thực sự gửi đi (value) là ID của phòng
                echo "<option value='{$room['id']}'>";
                echo "{$room['room_type']} (Phòng {$room['room_number']} - {$formatted_price}đ)";
                echo "</option>";
            }
        } else {
            echo "<option value=''>Hiện tại khách sạn đã hết phòng</option>";
        }
        ?>
        
    </select>
</div>
                    
                    <div class="form-group">
                        <label for="checkIn">Ngày Nhận Phòng (Check-in)</label>
                        <input type="date" id="checkIn" class="form-control" value="2026-07-10">
                    </div>
                    
                    <div class="form-group">
                        <label for="checkOut">Ngày Trả Phòng (Check-out)</label>
                        <input type="date" id="checkOut" class="form-control" value="2026-07-15">
                    </div>
                    
                    <div class="form-group">
                        <label for="fullName">Họ và Tên Khách Hàng</label>
                        <input type="text" id="fullName" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Số Điện Thoại Liên Hệ</label>
                        <input type="tel" id="phone" class="form-control" placeholder="Ví dụ: 0901234567" required>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-paper-plane"></i> Gửi Yêu Cầu Đặt Phòng
                    </button>
                </form>
            </div>
        </aside>
        
        <main class="content-admin">
            <div class="toolbar-admin">
                <div class="toolbar-left">
                    <h3><i class="fa-solid fa-list-check"></i> DANH SÁCH ĐƠN ĐẶT PHÒNG</h3>
                </div>
                
                <div class="toolbar-right">
                    <select class="filter-control" id="statusFilter">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="pending" selected>Chưa duyệt</option>
                        <option value="approved">Đã duyệt</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>

                    <select class="filter-control" id="sortBy">
                        <option value="date_desc">Mới nhất lên đầu</option>
                        <option value="date_asc">Cũ nhất lên đầu</option>
                        <option value="price_desc">Giá từ cao đến thấp</option>
                        <option value="price_asc">Giá từ thấp đến cao</option>
                    </select>
                    
                    <div class="export-buttons">
                        <button class="btn-export excel-btn">
                            <i class="fa-solid fa-file-excel"></i> Xuất Excel
                        </button>
                        <button class="btn-export pdf-btn">
                            <i class="fa-solid fa-file-pdf"></i> Xuất PDF
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="data-table">