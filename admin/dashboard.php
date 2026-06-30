<?php
session_start();
// 1. Chặn truy cập trái phép nếu chưa đăng nhập
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// 2. Gọi file kết nối cơ sở dữ liệu PDO
require_once '../config/db.php';

try {
    // 3. Truy vấn lấy Tổng số phòng
    $stmt_tong_phong = $pdo->query("SELECT COUNT(*) FROM phong");
    $tong_so_phong = $stmt_tong_phong->fetchColumn();

    // 4. Truy vấn lấy Số phòng trống
    $stmt_phong_trong = $pdo->query("SELECT COUNT(*) FROM phong WHERE trang_thai = 'trong'");
    $phong_trong = $stmt_phong_trong->fetchColumn();

    // 5. Truy vấn tính Tổng doanh thu
    $stmt_doanh_thu = $pdo->query("SELECT SUM(tong_tien) FROM dat_phong WHERE trang_thai = 'da_thanh_toan'");
    $tong_doanh_thu = $stmt_doanh_thu->fetchColumn();
    if (!$tong_doanh_thu) $tong_doanh_thu = 0; // Đặt bằng 0 nếu chưa có đơn nào

    // 6. Truy vấn dữ liệu cho Biểu đồ doanh thu theo tháng
    $stmt_chart = $pdo->query("
        SELECT MONTH(ngay_den) as thang, SUM(tong_tien) as doanh_thu 
        FROM dat_phong 
        WHERE trang_thai = 'da_thanh_toan' AND YEAR(ngay_den) = YEAR(CURRENT_DATE())
        GROUP BY MONTH(ngay_den)
        ORDER BY MONTH(ngay_den)
    ");
    $data_chart = $stmt_chart->fetchAll();

    $months = [];
    $revenues = [];
    foreach ($data_chart as $row) {
        $months[] = "Tháng " . $row['thang'];
        $revenues[] = (float)$row['doanh_thu'];
    }

} catch (PDOException $e) {
    die("Lỗi truy vấn CSDL: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Thống Kê</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Xin chào, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></a>
            <a href="logout.php" class="btn btn-danger btn-sm">Đăng xuất</a>
        </div>
    </nav>

    <div class="container">
        <h2>Dashboard Khách Sạn</h2>
        <hr>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white p-3 shadow-sm text-center">
                    <h5>Tổng số phòng</h5>
                    <h3><?php echo $tong_so_phong; ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white p-3 shadow-sm text-center">
                    <h5>Phòng trống hiện tại</h5>
                    <h3><?php echo $phong_trong; ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark p-3 shadow-sm text-center">
                    <h5>Tổng doanh thu</h5>
                    <h3><?php echo number_format($tong_doanh_thu, 0, ',', '.'); ?> VNĐ</h3>
                </div>
            </div>
        </div>

        <div class="card p-4 shadow-sm" style="max-width: 800px; margin: 0 auto;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <script>
        const labelsMonths = <?php echo json_encode($months); ?>;
        const dataRevenues = <?php echo json_encode($revenues); ?>;

        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsMonths.length > 0 ? labelsMonths : ['Chưa có dữ liệu'],
                datasets: [{
                    label: 'Doanh thu theo tháng (VNĐ)',
                    data: dataRevenues.length > 0 ? dataRevenues : [0],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>