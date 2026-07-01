<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Lấy tên file ảnh để tiến hành xóa vật lý trước khi xóa bản ghi trong DB
    $stmt = $pdo->prepare("SELECT hinh_anh FROM phong WHERE id = ?");
    $stmt->execute([$id]);
    $room = $stmt->fetch();

    if ($room) {
        if (!empty($room['hinh_anh'])) {
            $file_path = "../uploads/" . $room['hinh_anh'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Thực thi xóa phòng trong DB (Nếu có hóa đơn liên quan, DB sẽ tự ON DELETE CASCADE theo thiết kế khóa ngoại của nhóm bạn)
        $sql = "DELETE FROM phong WHERE id = :id";
        $del_stmt = $pdo->prepare($sql);
        $del_stmt->execute([':id' => $id]);
    }
}

header("Location: list.php?msg=del_success");
exit();
?>