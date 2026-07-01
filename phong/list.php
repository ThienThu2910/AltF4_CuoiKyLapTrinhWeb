<?php
// Đi lùi 1 bậc ra thư mục gốc rồi vào thư mục config
require_once '../config/db.php'; 

// ---- CHỨC NĂNG 1: XỬ LÝ PHÂN TRANG ----
$limit = 5; 
$page = isset($_GET['p']) && is_numeric($_GET['p']) ? intval($_GET['p']) : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// ---- CHỨC NĂNG 2: XỬ LÝ TÌM KIẾM ----
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$params = [];

$sql_count = "SELECT COUNT(*) FROM phong WHERE 1=1";
$sql_list = "SELECT * FROM phong WHERE 1=1";

if ($search !== '') {
    $sql_count .= " AND (so_phong LIKE ? OR loai_phong LIKE ?)";
    $sql_list .= " AND (so_phong LIKE ? OR loai_phong LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute($params);
$total_rows = $stmt_count->fetchColumn();
$total_pages = ceil($total_rows / $limit);

$sql_list .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
$stmt_list = $pdo->prepare($sql_list);
$stmt_list->execute($params);
$rooms = $stmt_list->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Phòng - ALTF4 Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light py-4">
<div class="container bg-white p-4 rounded shadow-sm">
    
    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'add_success'): ?>
            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle-fill me-2"></i> Thêm phòng mới thành công! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php elseif($_GET['msg'] == 'edit_success'): ?>
            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle-fill me-2"></i> Cập nhật thông tin phòng thành công! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php elseif($_GET['msg'] == 'del_success'): ?>
            <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-trash-fill me-2"></i> Đã xóa phòng thành công! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark m-0"><i class="bi bi-door-open-fill text-primary me-2"></i>Danh Sách Phòng</h2>
        <a href="add.php" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Thêm Phòng Mới</a>
    </div>

    <form method="GET" class="row g-2 mb-3 justify-content-end">
        <div class="col-md-4 d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Tìm số phòng, hạng phòng..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-sm btn-secondary px-3"><i class="bi bi-search"></i></button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th>Hình ảnh</th>
                    <th>Số phòng</th>
                    <th>Loại phòng</th>
                    <th>Giá phòng</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($rooms) > 0): ?>
                    <?php foreach($rooms as $row): ?>
                        <tr>
                            <td>
                                <?php 
                                $img_path = !empty($row['hinh_anh']) ? '../uploads/' . $row['hinh_anh'] : 'https://images.unsplash.com/photo-1611892440504-42a792e24d02?q=80&w=150';
                                ?>
                                <img src="<?= htmlspecialchars($img_path) ?>" class="rounded shadow-sm border" style="width: 80px; height: 50px; object-fit: cover;">
                            </td>
                            <td class="fw-bold text-primary"><?= htmlspecialchars($row['so_phong']) ?></td>
                            <td><span class="badge bg-info text-dark text-uppercase"><?= htmlspecialchars($row['loai_phong']) ?></span></td>
                            <td class="fw-bold text-danger"><?= number_format($row['gia_phong'], 0, ',', '.') ?> đ</td>
                            <td>
                                <?= $row['trang_thai'] == 'trong' 
                                    ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Trống</span>' 
                                    : '<span class="badge bg-secondary"><i class="bi bi-lock me-1"></i>Đang bận</span>' 
                                ?>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil-square"></i> Sửa</a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng <?= $row['so_phong'] ?> không?')"><i class="bi bi-trash"></i> Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Không tìm thấy phòng nào phù hợp.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($total_pages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination pagination-sm justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?p=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Trước</a>
                </li>
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                        <a class="page-link" href="?p=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?p=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Sau</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>