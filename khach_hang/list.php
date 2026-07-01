<?php
include "../config/db.php";

$sql = "SELECT * FROM khach_hang ORDER BY id DESC";
$stmt = $pdo->query($sql);
?>

<h2>Danh sách khách hàng</h2>

<a href="add.php">+ Thêm khách hàng</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Họ tên</th>
        <th>CCCD</th>
        <th>SĐT</th>
        <th>Email</th>
        <th>Thao tác</th>
    </tr>

<?php foreach($stmt as $row){ ?>

<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['ho_ten']; ?></td>
    <td><?= $row['cccd']; ?></td>
    <td><?= $row['so_dien_thoai']; ?></td>
    <td><?= $row['email']; ?></td>

    <td>
        <a href="edit.php?id=<?= $row['id']; ?>">Sửa</a>

        |

        <a href="delete.php?id=<?= $row['id']; ?>"
        onclick="return confirm('Bạn có chắc muốn xóa?')">
        Xóa
        </a>
    </td>

</tr>

<?php } ?>

</table>