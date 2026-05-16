<?php
session_start();
require_once "includes/db_config.php";

// 權限檢查：沒登入不能存表單
if (!isset($_SESSION['user_id'])) {
    die("未授權的存取");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $author_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    
    // --- 步驟 1: 存入表單主體 (forms) ---
    $sql_form = "INSERT INTO forms (title, description, author_id) VALUES ('$title', '$desc', '$author_id')";
    
    if (mysqli_query($conn, $sql_form)) {
        // 核心關鍵：抓取剛剛生成的 form_id
        $form_id = mysqli_insert_id($conn);

        // --- 步驟 2: 處理題目 (form_questions) ---
        // 注意：我們前端是用 q_text[index] 傳過來的
        if (isset($_POST['q_text'])) {
            foreach ($_POST['q_text'] as $index => $q_text) {
                $q_text = mysqli_real_escape_string($conn, $q_text);
                $q_type = mysqli_real_escape_string($conn, $_POST['q_type'][$index]);

                $sql_q = "INSERT INTO form_questions (form_id, question_text, question_type) 
                          VALUES ('$form_id', '$q_text', '$q_type')";
                
                if (mysqli_query($conn, $sql_q)) {
                    // 抓取剛剛生成的 question_id
                    $question_id = mysqli_insert_id($conn);

                    // --- 步驟 3: 處理選項 (form_options) ---
                    // 只有單選(radio)或多選(checkbox)才有選項
                    if (($q_type == 'radio' || $q_type == 'checkbox') && isset($_POST['q_options'][$index])) {
                        foreach ($_POST['q_options'][$index] as $opt_text) {
                            $opt_text = mysqli_real_escape_string($conn, $opt_text);
                            $sql_opt = "INSERT INTO form_options (question_id, option_text) 
                                        VALUES ('$question_id', '$opt_text')";
                            mysqli_query($conn, $sql_opt);
                        }
                    }
                }
            }
        }
        
        echo "<script>alert('表單發布成功！'); window.location.href='index.php';</script>";
    } else {
        echo "資料庫儲存失敗: " . mysqli_error($conn);
    }
}
?>