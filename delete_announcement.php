<?php
require_once "includes/admin_auth.php";
require_once "includes/db_config.php";

if (isset($_GET['id'])) {
    $ann_id = intval($_GET['id']);

    // 執行刪除指令
    $sql = "DELETE FROM announcements WHERE id = $ann_id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('公告已移除'); window.location.href='admin_panel.php';</script>";
    } else {
        echo "刪除失敗: " . mysqli_error($conn);
    }
} else {
    header("Location: admin_panel.php");
}
