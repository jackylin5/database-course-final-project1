<?php
session_start();
require_once "includes/db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
    $new_password = $_POST['new_password'];

    // 更新暱稱
    $sql = "UPDATE users SET nickname = '$nickname' WHERE id = $user_id";
    mysqli_query($conn, $sql);

    // 更新 Session 裡的暱稱，讓 Navbar 立即同步
    $_SESSION['nickname'] = $nickname;

    // 如果有輸入新密碼才更新密碼
    if (!empty($new_password)) {
        // 建議助教環境用明文，但如果是資工系專題建議用 password_hash
        $sql_pw = "UPDATE users SET password = '$new_password' WHERE id = $user_id";
        mysqli_query($conn, $sql_pw);
    }

    echo "<script>alert('個人資料已更新！'); window.location.href='profile.php';</script>";
}
