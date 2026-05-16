<?php
session_start();
require_once "includes/db_config.php";

$form_id = intval($_GET['id']);

// 1. 抓取表單基本資訊
$sql_form = "SELECT * FROM forms WHERE id = $form_id";
$res_form = mysqli_query($conn, $sql_form);
$form = mysqli_fetch_assoc($res_form);

if (!$form) die("表單不存在！");

// 2. 抓取所有題目
$sql_qs = "SELECT * FROM form_questions WHERE form_id = $form_id ORDER BY id ASC";
$res_qs = mysqli_query($conn, $sql_qs);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title><?php echo $form['title']; ?> - 報名</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <h2 class="text-primary"><?php echo $form['title']; ?></h2>
                <p class="lead text-muted"><?php echo nl2br($form['description']); ?></p>
                <hr>

                <form action="submit_response.php" method="POST">
                    <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">

                    <?php while ($q = mysqli_fetch_assoc($res_qs)): ?>
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <?php echo $q['question_text']; ?>
                                <?php if ($q['is_required']) echo '<span class="text-danger">*</span>'; ?>
                            </label>

                            <?php
                            $qid = $q['id'];
                            // 如果是單選或多選，去抓選項
                            if ($q['question_type'] == 'radio' || $q['question_type'] == 'checkbox') {
                                $sql_opts = "SELECT * FROM form_options WHERE question_id = $qid";
                                $res_opts = mysqli_query($conn, $sql_opts);
                                while ($opt = mysqli_fetch_assoc($res_opts)) {
                                    $type = $q['question_type'];
                                    // 注意 name 的寫法，checkbox 要用 []
                                    $name = ($type == 'checkbox') ? "ans[$qid][]" : "ans[$qid]";
                                    echo "
                                    <div class='form-check'>
                                        <input class='form-check-input' type='$type' name='$name' value='{$opt['option_text']}' id='opt_{$opt['id']}'>
                                        <label class='form-check-label' for='opt_{$opt['id']}'>{$opt['option_text']}</label>
                                    </div>";
                                }
                            } elseif ($q['question_type'] == 'textarea') {
                                echo "<textarea name='ans[$qid]' class='form-control' rows='3'></textarea>";
                            } else {
                                echo "<input type='text' name='ans[$qid]' class='form-control'>";
                            }
                            ?>
                        </div>
                    <?php endwhile; ?>

                    <div class="text-center mt-5">
                        <a href="index.php" class="btn btn-secondary px-4">取消</a>
                        <button type="submit" class="btn btn-primary px-5">提交報名</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>