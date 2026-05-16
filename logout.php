<?php
session_start();
session_destroy(); // 銷毀所有 Session 資料
header("Location: index.php"); // 踢回首頁
exit();
?>