<?php

include "../config/db.php";

$error = "";

// Kiểm tra id
if (!isset($_GET['id'])) {

    header("Location:list.php");
    exit();

}

$id = $_GET['id'];

if (!is_numeric($id)) {

    die("ID không hợp lệ!");

}

try {

    // Lấy dữ liệu khách
    $sql = "
        SELECT *
        FROM khach_hang
        WHERE id=:id
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id'=>$id
    ]);

    $row = $stmt->fetch();

    if (!$row) {

        die("Khách hàng không tồn tại!");

    }

}
catch(PDOException $e){

    die("Lỗi hệ thống!");

}


// Cập nhật
if (isset($_POST['update'])) {

    $ho_ten = trim($_POST['ho_ten']);

    $cccd = trim($_POST['cccd']);

    $so_dien_thoai = trim($_POST['so_dien_thoai']);

    $email = trim($_POST['email']);

    // Validate
    if (
        empty($ho_ten) ||
        empty($cccd) ||
        empty($so_dien_thoai)
    ) {

        $error = "Vui lòng nhập đầy đủ!";

    }

    elseif (
        !preg_match('/^[0-9]{12}$/',$cccd)
    ) {

        $error = "CCCD phải gồm 12 số!";

    }

    elseif (
        !preg_match('/^[0-9]{10,11}$/',$so_dien_thoai)
    ) {

        $error = "Số điện thoại không hợp lệ!";

    }

    elseif (
        !empty($email)
        &&
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $error = "Email không hợp lệ!";

    }

    else {

        try {

            // Kiểm tra CCCD trùng
            $check = $pdo->prepare(
                "
                SELECT id
                FROM khach_hang
                WHERE cccd=:cccd
                AND id!=:id
                "
            );

            $check->execute([

                ':cccd'=>$cccd,

                ':id'=>$id

            ]);

            if ($check->fetch()) {

                $error = "CCCD đã tồn tại!";

            }

            else {

                $sql = "

                UPDATE khach_hang

                SET

                ho_ten=:ho_ten,

                cccd=:cccd,

                so_dien_thoai=:so_dien_thoai,

                email=:email

                WHERE id=:id

                ";

                $stmt = $pdo->prepare($sql);

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

        }

        catch(PDOException $e){

            $error="Không thể cập nhật!";

        }

    }

}

?>

<h2>Sửa khách hàng</h2>

<?php if($error): ?>

<p style="color:red;">

<?= htmlspecialchars($error) ?>

</p>

<?php endif; ?>


<form method="POST">

<input
type="text"
name="ho_ten"
required
value="<?= htmlspecialchars($row['ho_ten']) ?>">

<br><br>


<input
type="text"
name="cccd"
maxlength="12"
required
value="<?= htmlspecialchars($row['cccd']) ?>">

<br><br>


<input
type="text"
name="so_dien_thoai"
maxlength="11"
required
value="<?= htmlspecialchars($row['so_dien_thoai']) ?>">

<br><br>


<input
type="email"
name="email"
value="<?= htmlspecialchars($row['email']) ?>">

<br><br>


<button name="update">

Cập nhật

</button>

</form>