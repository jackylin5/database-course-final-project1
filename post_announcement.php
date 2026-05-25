<?php
require_once "includes/admin_auth.php";
require_once "includes/db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author_id = $_SESSION['user_id'];

    $sql = "INSERT INTO announcements (title, content, author_id) VALUES ('$title', '$content', '$author_id')";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
    } else {
        echo "公告發布失敗";
    }
}
