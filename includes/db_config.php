<?php
// 資料庫設定值
$host = "localhost:3307";
$user = "root";
$password = "root123456"; // 照助教要求的密碼
$dbname = "group_27";

// 建立連線
$conn = mysqli_connect($host, $user, $password, $dbname);

// 檢查連線是否成功
if (!$conn) {
    // 如果失敗，輸出錯誤訊息並停止執行
    die("❌ 資料庫連線失敗: " . mysqli_connect_error());
}

// 設定編碼為 utf8mb4 (支援中文與 Emoji 🤪)
mysqli_set_charset($conn, "utf8mb4");

// 註解：這行之後可以刪掉，現在留著確認有連上
//echo "✅ 資料庫連線成功！"; 
