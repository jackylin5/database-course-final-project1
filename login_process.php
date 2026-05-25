<?php
session_start(); // 這是使用 Session 的第一步，必寫！
require_once "includes/db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password']; // 專題暫時用明碼比對

    $sql = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // 登入成功！抓取使用者資料
        $row = mysqli_fetch_assoc($result);

        // 把重要資訊存進 Session (就像發給瀏覽器一張通行證)
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['nickname'] = $row['nickname'];
        $_SESSION['role'] = $row['role']; // admin, member, 或 guest

        // 跳轉回首頁
        header("Location: index.php");
        exit();
    } else {
        // 登入失敗
        echo "<script>alert('帳號或密碼錯誤！'); window.location.href='login.php';</script>";
    }
}
?>