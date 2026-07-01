<?php
include "../config/db.php";

$id = $_GET['id'];

$sql = "SELECT * FROM khach_hang WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id'=>$id]);

$row = $stmt->fetch();

if(isset($_POST['update'])){

    $ho_ten=$_POST['ho_ten'];
    $cccd=$_POST['cccd'];
    $so_dien_thoai=$_POST['so_dien_thoai'];
    $email=$_POST['email'];

    $sql="UPDATE khach_hang
    SET
    ho_ten=:ho_ten,
    cccd=:cccd,
    so_dien_thoai=:so_dien_thoai,
    email=:email
    WHERE id=:id";

    $stmt=$pdo->prepare($sql);

    $stmt->execute([
        ':ho_ten'=>$ho_ten,
        ':cccd'=>$cccd,
        ':so_dien_thoai'=>$so_dien_thoai,
        ':email'=>$email,
        ':id'=>$id
    ]);

    header("Location:list.php");
    exit();
}
?>

<h2>Sửa khách hàng</h2>

<form method="POST">

<input type="text" name="ho_ten"
value="<?= $row['ho_ten']; ?>" required>

<br><br>

<input type="text" name="cccd"
value="<?= $row['cccd']; ?>" required>

<br><br>

<input type="text" name="so_dien_thoai"
value="<?= $row['so_dien_thoai']; ?>" required>

<br><br>

<input type="email" name="email"
value="<?= $row['email']; ?>">

<br><br>

<button name="update">
Cập nhật
</button>

</form>