<?php
require_once "includes/db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. 接收資料並進行基本防禦
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
    $pass = $_POST['password']; // 專題建議先用明碼，如果要加分可以用 password_hash()

    // 2. 再次檢查帳號是否重複 (防止有人跳過前端 Ajax 直接送資料)
    $check_sql = "SELECT id FROM users WHERE username = '$user'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('帳號已存在！'); history.back();</script>";
        exit();
    }

    // 3. 執行插入 (預設群組 ID 為 2，也就是我們 SQL 預設的「一般會員」)
    $sql = "INSERT INTO users (username, password, nickname, role, group_id) 
            VALUES ('$user', '$pass', '$nickname', 'member', 2)";

    if (mysqli_query($conn, $sql)) {
        // 註冊成功，導向登入頁面
        echo "<script>alert('註冊成功！快去登入吧 🚀'); window.location.href='login.php';</script>";
    } else {
        // 報錯給工程師看
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>