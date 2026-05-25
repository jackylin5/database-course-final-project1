<?php
require_once "includes/auth_check.php";
require_once "includes/db_config.php";

$form_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// 1. 安全檢查：確保你是這份表單的主人
$sql_check = "SELECT title FROM forms WHERE id = $form_id AND author_id = $user_id";
$res_check = mysqli_query($conn, $sql_check);
$form_data = mysqli_fetch_assoc($res_check);
if (!$form_data) die("權限不足或表單不存在");

// 2. 抓取這份表單的所有題目 (當作表格標題)
$sql_qs = "SELECT id, question_text FROM form_questions WHERE form_id = $form_id ORDER BY id ASC";
$res_qs = mysqli_query($conn, $sql_qs);
$questions = [];
while ($q = mysqli_fetch_assoc($res_qs)) {
    $questions[$q['id']] = $q['question_text'];
}

// 3. 抓取所有報名紀錄與其對應的答案
$sql_res = "SELECT r.id as res_id, r.submitted_at, u.nickname, a.question_id, a.answer_text 
            FROM form_responses r
            LEFT JOIN users u ON r.user_id = u.id
            LEFT JOIN form_answers a ON r.id = a.response_id
            WHERE r.form_id = $form_id
            ORDER BY r.submitted_at DESC, a.question_id ASC";
$res_data = mysqli_query($conn, $sql_res);

// 4. 重整資料：把答案按 response_id 分組
$responses = [];
while ($row = mysqli_fetch_assoc($res_data)) {
    $rid = $row['res_id'];
    if (!isset($responses[$rid])) {
        $responses[$rid] = [
            'nickname' => $row['nickname'] ?? '訪客',
            'time' => $row['submitted_at'],
            'answers' => []
        ];
    }
    $responses[$rid]['answers'][$row['question_id']] = $row['answer_text'];
}

// --- 5. 統計單選題與多選題的選項分佈 ---
$stats = [];
$sql_stats = "SELECT question_id, answer_text, COUNT(*) as count 
              FROM form_answers 
              WHERE response_id IN (SELECT id FROM form_responses WHERE form_id = $form_id)
              GROUP BY question_id, answer_text";
$res_stats = mysqli_query($conn, $sql_stats);

while ($s = mysqli_fetch_assoc($res_stats)) {
    $qid = $s['question_id'];
    $ans = $s['answer_text'];
    // 如果是多選題，答案可能長這樣 "A, B"，需要拆開統計
    $parts = explode(", ", $ans);
    foreach ($parts as $p) {
        if (!isset($stats[$qid][$p])) $stats[$qid][$p] = 0;
        $stats[$qid][$p] += $s['count'];
    }
}

// 計算總報名人數
$total_responses = count($responses);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>報名結果 - <?php echo $form_data['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container-fluid mt-5 px-4">

        <div class="card shadow">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0 text-primary">📊 <?php echo $form_data['title']; ?> - 報名結果統計</h4>
                <a href="my_forms.php" class="btn btn-outline-secondary btn-sm">返回我的表單</a>
            </div>
            <div class="card-body border-bottom bg-light">
                <h5 class="mb-4"><i class="bi bi-pie-chart"></i> 選項數據總覽 (總人數: <?php echo $total_responses; ?>)</h5>
                <div class="row">
                    <?php foreach ($questions as $qid => $q_text): ?>
                        <?php if (isset($stats[$qid])): // 只針對有選項的題目做統計 
                        ?>
                            <div class="col-md-6 mb-4">
                                <div class="p-3 bg-white shadow-sm rounded">
                                    <strong class="text-secondary"><?php echo htmlspecialchars($q_text); ?></strong>
                                    <div class="mt-3">
                                        <?php foreach ($stats[$qid] as $opt_text => $count):
                                            $percent = ($total_responses > 0) ? round(($count / $total_responses) * 100) : 0;
                                        ?>
                                            <div class="d-flex justify-content-between mb-1 small">
                                                <span><?php echo htmlspecialchars($opt_text); ?></span>
                                                <span><?php echo $count; ?> 票 (<?php echo $percent; ?>%)</span>
                                            </div>
                                            <div class="progress mb-3" style="height: 10px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $percent; ?>%"></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>報名者</th>
                                <th>提交時間</th>
                                <?php foreach ($questions as $q_text): ?>
                                    <th><?php echo htmlspecialchars($q_text); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($responses)): ?>
                                <tr>
                                    <td colspan="<?php echo count($questions) + 2; ?>" class="text-center py-5">目前還沒有人報名喔 😢</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($responses as $res): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo htmlspecialchars($res['nickname']); ?></td>
                                        <td class="small text-muted"><?php echo $res['time']; ?></td>
                                        <?php foreach ($questions as $qid => $q_text): ?>
                                            <td><?php echo htmlspecialchars($res['answers'][$qid] ?? '-'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>