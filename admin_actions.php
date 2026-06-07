<?php
session_start();
require_once "includes/db_config.php";

// 🛑 核心資安防線：檢查是不是管理員，沒登入或不是 admin 直接踢走！
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("未授權的非法存取！");
}

$action = $_GET['action'] ?? '';

// 1. 處理【新增會員】
if ($action === 'add_user' && $_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // 密碼加密 (資工系專案必備安全防禦)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('管理員成功建立帳號！'); window.location.href='admin.php?page=users';</script>";
    } else {
        echo "建立失敗: " . mysqli_error($conn);
    }
}

// 2. 處理【刪除違規表單】
if ($action === 'delete_form' && isset($_GET['id'])) {
    $form_id = intval($_GET['id']);

    // 刪除表單（註：如果資料庫有設外鍵級聯刪除 ON DELETE CASCADE，相關題目跟選項會自動一起被刪掉）
    $sql = "DELETE FROM forms WHERE id = $form_id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('表單已成功強制刪除！'); window.location.href='admin.php?page=forms';</script>";
    } else {
        echo "刪除失敗: " . mysqli_error($conn);
    }
}
