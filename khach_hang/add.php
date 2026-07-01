```php
<?php
include "../config/db.php";

if (isset($_POST['save'])) {

    $ho_ten = $_POST['ho_ten'];
    $cccd = $_POST['cccd'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $email = $_POST['email'];

    $sql = "INSERT INTO khach_hang (ho_ten, cccd, so_dien_thoai, email)
            VALUES (:ho_ten, :cccd, :so_dien_thoai, :email)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':ho_ten' => $ho_ten,
        ':cccd' => $cccd,
        ':so_dien_thoai' => $so_dien_thoai,
        ':email' => $email
    ]);

    header("Location: list.php");
    exit();
}
?>

<h2>Thêm khách hàng</h2>

<form method="POST">

    <input type="text" name="ho_ten" placeholder="Họ tên" required>
    <br><br>

    <input type="text" name="cccd" placeholder="CCCD" required>
    <br><br>

    <input type="text" name="so_dien_thoai" placeholder="Số điện thoại" required>
    <br><br>

    <input type="email" name="email" placeholder="Email">
    <br><br>

    <button type="submit" name="save">Thêm</button>

</form>
```
