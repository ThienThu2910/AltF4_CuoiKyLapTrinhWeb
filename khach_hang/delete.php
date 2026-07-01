<?php

include "../config/db.php";

$id=$_GET['id'];

$sql="DELETE FROM khach_hang WHERE id=:id";

$stmt=$pdo->prepare($sql);

$stmt->execute([
':id'=>$id
]);

header("Location:list.php");
exit();

?>