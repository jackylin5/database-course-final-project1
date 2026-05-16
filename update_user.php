<?php
require_once "includes/admin_auth.php";
require_once "includes/db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);

    // 安全檢查：如果 ID 是目前登入者的 ID，強制把 role 設回 admin (或保持不變)
    if ($id == $_SESSION['user_id']) {
        $sql = "UPDATE users SET group_name = '$group_name' WHERE id = $id";
    } else {
        $sql = "UPDATE users SET role = '$role', group_name = '$group_name' WHERE id = $id";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('資料更新成功！'); window.location.href='admin_panel.php';</script>";
    } else {
        echo "更新失敗: " . mysqli_error($conn);
    }
}
