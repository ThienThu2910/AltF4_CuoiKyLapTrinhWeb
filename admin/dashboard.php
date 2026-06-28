<?php
session_start();
// Chặn truy cập trái phép
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once '../config/db.php';

// 1. Lấy dữ liệu thống kê cơ bản
$tong_so_phong = $pdo->query("SELECT COUNT(*) FROM phong")->fetchColumn();
$phong_trong = $pdo->query("SELECT COUNT(*) FROM phong WHERE trang_thai = 'trong'")->fetchColumn();
$tong_doanh_thu = $pdo->query("SELECT SUM(tong_tien) FROM dat_phong WHERE trang_thai = 'da_thanh_toan'")->fetchColumn() ?? 0;

// 2. Lấy dữ liệu biểu đồ doanh thu theo tháng
$stmt = $pdo->query("
    SELECT MONTH(ngay_den) as thang, SUM(tong_tien) as doanh_thu 
    FROM dat_phong 
    WHERE trang_thai = 'da_thanh_toan' AND YEAR(ngay_den) = YEAR(CURRENT_DATE())
    GROUP BY MONTH(ngay_den)
    ORDER BY MONTH(ngay_den)
");
$data_chart = $stmt->fetchAll();

$months = [];
$revenues = [];
foreach ($data_chart as $row) {
    $months[] = "Tháng " . $row['thang'];
    $revenues[] = (float)$row['doanh_thu'];
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
            <a class="navbar-brand" href="#">Xin chào, <?php echo $_SESSION['admin_name']; ?></a>
            <a href="logout.php" class="btn btn-danger btn-sm">Đăng xuất</a>
        </div>
    </nav>

    <div class="container">
        <h2>Dashboard Khách Sạn</h2>
        <hr>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white p-3 shadow-sm">
                    <h5>Tổng số phòng: <?php echo $tong_so_phong; ?></h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white p-3 shadow-sm">
                    <h5>Phòng trống: <?php echo $phong_trong; ?></h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark p-3 shadow-sm">
                    <h5>Tổng doanh thu: <?php echo number_format($tong_doanh_thu); ?> VNĐ</h5>
                </div>
            </div>
        </div>

        <div class="card p-4 shadow-sm" style="max-width: 800px; margin: 0 auto;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <script>
        // Truyền dữ liệu từ PHP sang JavaScript
        const labelsMonths = <?php echo json_encode($months); ?>;
        const dataRevenues = <?php echo json_encode($revenues); ?>;

        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsMonths,
                datasets: [{
                    label: 'Doanh thu theo tháng (VNĐ)',
                    data: dataRevenues,
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