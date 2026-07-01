<?php

include "../config/db.php";

// Kiểm tra có truyền id không
if (!isset($_GET['id'])) {

    header("Location:list.php");
    exit();

}

// Lấy id
$id = $_GET['id'];

// Kiểm tra id hợp lệ
if (!is_numeric($id)) {

    die("ID không hợp lệ!");

}

try {

    // Kiểm tra khách hàng tồn tại
    $check = $pdo->prepare(
        "SELECT id
         FROM khach_hang
         WHERE id=:id"
    );

    $check->execute([
        ':id'=>$id
    ]);

    if (!$check->fetch()) {

        die("Khách hàng không tồn tại!");

    }

    // Xóa
    $sql = "
        DELETE
        FROM khach_hang
        WHERE id=:id
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([

        ':id'=>$id

    ]);

    header("Location:list.php");

    exit();

}

catch(PDOException $e){

    die("Không thể xóa dữ liệu!");

}

?>