<?php
session_start();
require_once "includes/db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_id = intval($_POST['form_id']);
    // 如果沒登入也可以報名，user_id 就存 NULL (助教要求一般使用者可查，通常也可填)
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';

    // 1. 建立一筆「報名紀錄」
    $sql_res = "INSERT INTO form_responses (form_id, user_id) VALUES ($form_id, $user_id)";

    if (mysqli_query($conn, $sql_res)) {
        $response_id = mysqli_insert_id($conn); // 拿到這份報名的編號

        // 2. 處理每一個問題的答案
        if (isset($_POST['ans'])) {
            foreach ($_POST['ans'] as $qid => $answer) {
                $qid = intval($qid);

                // 如果是陣列 (多選題)，把它串起來
                if (is_array($answer)) {
                    $final_answer = implode(", ", $answer);
                } else {
                    $final_answer = $answer;
                }

                $final_answer = mysqli_real_escape_string($conn, $final_answer);

                $sql_ans = "INSERT INTO form_answers (response_id, question_id, answer_text) 
                            VALUES ($response_id, $qid, '$final_answer')";
                mysqli_query($conn, $sql_ans);
            }
        }

        echo "<script>alert('報名成功！期待與你見面 🎊'); window.location.href='index.php';</script>";
    } else {
        echo "提交失敗: " . mysqli_error($conn);
    }
}
