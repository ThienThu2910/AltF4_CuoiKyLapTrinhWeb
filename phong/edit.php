<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $pdo->prepare("SELECT * FROM phong WHERE id = ?");
$stmt->execute([$id]);
$room = $stmt->fetch();

if (!$room) {
    die("Phòng không tồn tại trên hệ thống!");
}

$error = '';

if (isset($_POST['update'])) {
    $so_phong = trim($_POST['so_phong']);
    $loai_phong = trim($_POST['loai_phong']);
    $gia_phong = trim($_POST['gia_phong']);
    $trang_thai = $_POST['trang_thai'];

    if (empty($so_phong) || empty($loai_phong) || empty($gia_phong)) {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }

    $hinh_anh = $room['hinh_anh']; 

    if (empty($error) && isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $target_dir = "../uploads/";
        $file_extension = pathinfo($_FILES["hinh_anh"]["name"], PATHINFO_EXTENSION);
        $new_filename = "room_" . time() . "_" . rand(1000, 9999) . "." . $file_extension;

        if (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'webp'])) {
            if (!empty($room['hinh_anh']) && file_exists($target_dir . $room['hinh_anh'])) {
                unlink($target_dir . $room['hinh_anh']);
            }
            move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_dir . $new_filename);
            $hinh_anh = $new_filename;
        } else {
            $error = "Định dạng file ảnh mới không hợp lệ!";
        }
    }

    if (empty($error)) {
        $sql = "UPDATE phong SET so_phong = :so_phong, loai_phong = :loai_phong, 
                gia_phong = :gia_phong, trang_thai = :trang_thai, hinh_anh = :hinh_anh WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':so_phong' => $so_phong,
            ':loai_phong' => $loai_phong,
            ':gia_phong' => $gia_phong,
            ':trang_thai' => $trang_thai,
            ':hinh_anh' => $hinh_anh,
            ':id' => $id
        ]);

        header("Location: list.php?msg=edit_success");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập Nhật Thông Tin Phòng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm p-4">
        <h3 class="fw-bold mb-4 text-warning text-center">Chỉnh Sửa Phòng: <?= htmlspecialchars($room['so_phong']) ?></h3>

        <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label fw-bold">Số phòng</label>
                <input type="text" name="so_phong" class="form-control" value="<?= htmlspecialchars($room['so_phong']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Loại phòng</label>
                <select name="loai_phong" class="form-select" required>
                    <option value="Single" <?= $room['loai_phong'] == 'Single' ? 'selected' : '' ?>>Single Room</option>
                    <option value="Double" <?= $room['loai_phong'] == 'Double' ? 'selected' : '' ?>>Double Room</option>
                    <option value="Family" <?= $room['loai_phong'] == 'Family' ? 'selected' : '' ?>>Family Room</option>
                    <option value="VIP Luxury" <?= $room['loai_phong'] == 'VIP Luxury' ? 'selected' : '' ?>>VIP Luxury Suite</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Giá phòng (VNĐ/đêm)</label>
                <input type="number" name="gia_phong" class="form-control" value="<?= intval($room['gia_phong']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="trang_thai" class="form-select">
                    <option value="trong" <?= $room['trang_thai'] == 'trong' ? 'selected' : '' ?>>Trống</option>
                    <option value="da_dat" <?= $room['trang_thai'] == 'da_dat' ? 'selected' : '' ?>>Đang bận</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Hình ảnh phòng</label>
                <div class="mb-2">
                    <?php if(!empty($room['hinh_anh'])): ?>
                        <img src="../uploads/<?= $room['hinh_anh'] ?>" class="img-thumbnail" style="max-height: 120px;">
                    <?php else: ?>
                        <span class="text-muted small d-block">Chưa có ảnh đại diện</span>
                    <?php endif; ?>
                </div>
                <input type="file" name="hinh_anh" class="form-control" accept="image/*">
            </div>

            <div class="d-flex justify-content-between">
                <a href="list.php" class="btn btn-outline-secondary px-4">Hủy bỏ</a>
                <button type="submit" name="update" class="btn btn-warning px-5 fw-bold">Cập Nhật</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>