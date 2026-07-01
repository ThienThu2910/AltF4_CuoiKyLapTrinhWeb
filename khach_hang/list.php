<?php

include "../config/db.php";

try {

    $sql = "
        SELECT *
        FROM khach_hang
        ORDER BY id DESC
    ";

    $stmt = $pdo->query($sql);

    $data = $stmt->fetchAll();

}

catch(PDOException $e){

    die("Không thể tải dữ liệu!");

}

?>

<!DOCTYPE html>

<html lang="vi">

<head>

<meta charset="UTF-8">

<title>Danh sách khách hàng</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>


<body>

<div class="container mt-4">

<h2>Danh sách khách hàng</h2>

<a
href="add.php"
class="btn btn-success mb-3">

+ Thêm khách hàng

</a>

<table class="table table-bordered table-hover">

<tr>

                <th>ID</th>

                <th>Họ tên</th>

                <th>CCCD</th>

                <th>SĐT</th>

                <th>Email</th>

                <th width="180">
    Thao tác
                </th>

</tr>


<?php if(count($data)>0): ?>


<?php foreach($data as $row): ?>

<tr>

        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['ho_ten']) ?></td>
        <td><?= htmlspecialchars($row['cccd']) ?></td>
        <td><?= htmlspecialchars($row['so_dien_thoai']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>


<td>
<a
        href="edit.php?id=<?= $row['id'] ?>"
        class="btn btn-warning btn-sm">

            Sửa

</a>


<a
        href="delete.php?id=<?= $row['id'] ?>"

        class="btn btn-danger btn-sm"

        onclick="return confirm('Bạn có chắc muốn xóa?')">

            Xóa

</a>

</td>

</tr>

            <?php endforeach; ?>


            <?php else: ?>


<tr>

    <td
        colspan="6"
        class="text-center">

        Chưa có khách hàng

</td>

</tr>


            <?php endif; ?>


        </table>

    </div>

</body>

</html>