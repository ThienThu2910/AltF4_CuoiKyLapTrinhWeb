<?php
require_once '../config/db.php';

$error = '';

if (isset($_POST['save'])) {
    $so_phong = trim($_POST['so_phong']);
    $loai_phong = trim($_POST['loai_phong']);
    $gia_phong = trim($_POST['gia_phong']);
    $trang_thai = $_POST['trang_thai'];

    if (empty($so_phong) || empty($loai_phong) || empty($gia_phong)) {
        $error = "Vui lòng nhập đầy đủ các trường bắt buộc!";
    } elseif (!is_numeric($gia_phong) || $gia_phong <= 0) {
        $error = "Giá phòng phải là số lớn hơn 0!";
    } else {
        // Kiểm tra trùng số phòng (UNIQUE KEY so_phong)
        $chk = $pdo->prepare("SELECT COUNT(*) FROM phong WHERE so_phong = ?");
        $chk->execute([$so_phong]);
        if($chk->fetchColumn() > 0) {
            $error = "Số phòng này đã tồn tại, vui lòng chọn số khác!";
        }
    }

    // XỬ LÝ UPLOAD ẢNH
    $hinh_anh = null;
    if (empty($error) && isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES["hinh_anh"]["name"], PATHINFO_EXTENSION);
        $hinh_anh = "room_" . time() . "_" . rand(1000, 9999) . "." . $file_extension;
        
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if(in_array(strtolower($file_extension), $allowed)) {
            move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_dir . $hinh_anh);
        } else {
            $error = "Định dạng file ảnh không hợp lệ!";
            $hinh_anh = null;
        }
    }

    if (empty($error)) {
        $sql = "INSERT INTO phong (so_phong, loai_phong, gia_phong, trang_thai, hinh_anh) 
                VALUES (:so_phong, :loai_phong, :gia_phong, :trang_thai, :hinh_anh)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':so_phong' => $so_phong,
            ':loai_phong' => $loai_phong,
            ':gia_phong' => $gia_phong,
            ':trang_thai' => $trang_thai,
            ':hinh_anh' => $hinh_anh
        ]);

        header("Location: list.php?msg=add_success");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Phòng Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm p-4">
        <h3 class="fw-bold mb-4 text-primary text-center">Thêm Phòng Khách Sạn</h3>

        <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" id="formPhong" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label fw-bold">Số phòng *</label>
                <input type="text" name="so_phong" id="so_phong" class="form-control" placeholder="Ví dụ: 105, VIP03">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Loại phòng *</label>
                <select name="loai_phong" id="loai_phong" class="form-select">
                    <option value="">-- Chọn hạng phòng --</option>
                    <option value="Single">Single Room</option>
                    <option value="Double">Double Room</option>
                    <option value="Family">Family Room</option>
                    <option value="VIP Luxury">VIP Luxury Suite</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Giá phòng (VNĐ/đêm) *</label>
                <input type="number" name="gia_phong" id="gia_phong" class="form-control" placeholder="Ví dụ: 700000">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="trang_thai" class="form-select">
                    <option value="trong">Trống</option>
                    <option value="da_dat">Đang bận</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Hình ảnh phòng</label>
                <input type="file" name="hinh_anh" id="hinh_anh" class="form-control" accept="image/*">
            </div>

            <div class="d-flex justify-content-between">
                <a href="list.php" class="btn btn-outline-secondary px-4">Quay lại</a>
                <button type="submit" name="save" class="btn btn-primary px-5">Lưu Lại</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('formPhong').addEventListener('submit', function(e) {
    let soPhong = document.getElementById('so_phong').value.trim();
    let loaiPhong = document.getElementById('loai_phong').value;
    let giaPhong = document.getElementById('gia_phong').value.trim();

    if (soPhong === "" || loaiPhong === "" || giaPhong === "") {
        alert("Vui lòng không để trống các trường có dấu (*)!");
        e.preventDefault();
        return false;
    }
});
</script>
</body>
</html>