<?php
include "../config/db.php";

$error = "";

if (isset($_POST['save'])) {

    $ho_ten = trim($_POST['ho_ten']);
    $cccd = trim($_POST['cccd']);
    $so_dien_thoai = trim($_POST['so_dien_thoai']);
    $email = trim($_POST['email']);

    // Kiểm tra rỗng
    if (
        empty($ho_ten) ||
        empty($cccd) ||
        empty($so_dien_thoai)
    ) {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }

    // Kiểm tra CCCD
    elseif (!preg_match('/^[0-9]{12}$/', $cccd)) {
        $error = "CCCD phải gồm đúng 12 số!";
    }

    // Kiểm tra số điện thoại
    elseif (!preg_match('/^[0-9]{10,11}$/', $so_dien_thoai)) {
        $error = "Số điện thoại không hợp lệ!";
    }

    // Kiểm tra email
    elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không đúng định dạng!";
    }

    else {

        try {

            // Kiểm tra CCCD trùng
            $check = $pdo->prepare(
                "SELECT id
                 FROM khach_hang
                 WHERE cccd=:cccd"
            );

            $check->execute([
                ':cccd'=>$cccd
            ]);

            if ($check->fetch()) {

                $error = "CCCD đã tồn tại!";

            } else {

                $sql = "
                    INSERT INTO khach_hang
                    (
                        ho_ten,
                        cccd,
                        so_dien_thoai,
                        email
                    )
                    VALUES
                    (
                        :ho_ten,
                        :cccd,
                        :so_dien_thoai,
                        :email
                    )
                ";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([

                    ':ho_ten'=>$ho_ten,

                    ':cccd'=>$cccd,

                    ':so_dien_thoai'=>$so_dien_thoai,

                    ':email'=>$email
                ]);

                header("Location: list.php");

                exit();
            }

        }

        catch(PDOException $e){

            $error="Có lỗi khi thêm dữ liệu!";

        }

    }
}
?>

         <h2>Thêm khách hàng</h2>

    <?php if(!empty($error)): ?>

            <p style="color:red;">
             <?= htmlspecialchars($error) ?>
            </p>

    <?php endif; ?>

        <form method="POST">

            <label>Họ tên</label>
        <br>

            <input
                type="text"
                name="ho_ten"
                value="<?= htmlspecialchars($_POST['ho_ten'] ?? '') ?>"
                required>

    <br><br>
            <label>CCCD</label>
    <br>

        <input
                 type="text"
                 name="cccd"
                 maxlength="12"
                 value="<?= htmlspecialchars($_POST['cccd'] ?? '') ?>"
                 required>

    <br><br>


            <label>Số điện thoại</label>
    <br>

         <input
                 type="text"
                 name="so_dien_thoai"
                 maxlength="11"
                 value="<?= htmlspecialchars($_POST['so_dien_thoai'] ?? '') ?>"
                 required>

    <br><br>


            <label>Email</label>
    <br>

        <input
                 type="email"
                 name="email"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

    <br><br>


    <button
                type="submit"
                 name="save">

        Thêm khách hàng

    </button>

</form>