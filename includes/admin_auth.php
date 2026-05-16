<?php
session_start();

// 1. 檢查有沒有登入
// 2. 檢查角色是不是 admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // 權限不足，顯示訊息並踢回首頁
    echo "<script>alert('權限不足！請以管理員身分登入。'); window.location.href='login.php';</script>";
    exit();
}
?>