<?php
require_once "includes/db_config.php";

if (isset($_POST['user_name'])) {
    $user = $_POST['user_name'];
    $user = mysqli_real_escape_string($conn, $user);
    
    // 加上偵錯邏輯
    $sql = "SELECT id FROM users WHERE username = '$user'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        // 如果 SQL 語法錯了，會直接顯示原因
        die("SQL 錯誤: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        echo "exists";
    } else {
        echo "available";
    }
} else {
    echo "未接收到資料";
}
?>