<?php
session_start();
require_once "includes/db_config.php";
require_once "includes/auth_check.php";

$form_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// 安全檢查：確保這份表單真的是本人建立的，防止有人改 URL 刪別人的東西
$check_sql = "SELECT id FROM forms WHERE id = $form_id AND author_id = $user_id";
$check_res = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_res) > 0) {
    // 執行刪除 (因為我們 SQL 有設 ON DELETE CASCADE，所以題目、選項、報名紀錄會自動被刪乾淨)
    $del_sql = "DELETE FROM forms WHERE id = $form_id";
    mysqli_query($conn, $del_sql);
    echo "<script>alert('表單已成功刪除'); window.location.href='my_forms.php';</script>";
} else {
    echo "<script>alert('權限不足或表單不存在'); window.location.href='my_forms.php';</script>";
}
