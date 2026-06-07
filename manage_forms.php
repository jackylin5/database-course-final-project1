<?php
require_once "includes/admin_auth.php"; // 先檢查門禁
require_once "includes/db_config.php"; // 連接資料庫

// ==========================================
// 1. 優先處理「連鎖刪除表單」動作（斬草除根版）
// ==========================================
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    // 啟動資料庫事務（Transaction），確保所有資料要嘛一起刪成功，要嘛一起失敗，絕對不留孤兒
    mysqli_begin_transaction($conn);

    try {
        // 步聚 1：刪除 form_options（選項）
        // 條件：選項的 question_id 屬於該表單的題目
        $sql_del_opts = "DELETE FROM form_options WHERE question_id IN (SELECT id FROM form_questions WHERE form_id = $delete_id)";
        mysqli_query($conn, $sql_del_opts);

        // 步驟 2：刪除 form_answers（具體答案）
        // 條件：答案的 response_id 屬於該表單的填答紀錄
        // (註：如果你的 form_answers 裡面直接有 form_id 欄位，可以直接 WHERE form_id = $delete_id)
        $sql_del_ans = "DELETE FROM form_answers WHERE response_id IN (SELECT id FROM form_responses WHERE form_id = $delete_id)";
        mysqli_query($conn, $sql_del_ans);

        // 步驟 3：刪除 form_responses（填答主紀錄）
        $sql_del_res = "DELETE FROM form_responses WHERE form_id = $delete_id";
        mysqli_query($conn, $sql_del_res);

        // 步驟 4：刪除 form_questions（題目）
        $sql_del_qs = "DELETE FROM form_questions WHERE form_id = $delete_id";
        mysqli_query($conn, $sql_del_qs);

        // 步驟 5：最後刪除主表單 forms
        $sql_del_form = "DELETE FROM forms WHERE id = $delete_id";
        mysqli_query($conn, $sql_del_form);

        // 成功，提交事務
        mysqli_commit($conn);

        echo "<script>
                alert('該表單及其所有題目、選項、報名紀錄已完美根除！');
                window.location.href = 'manage_forms.php';
              </script>";
        exit;
    } catch (Exception $e) {
        // 失敗，復原所有刪除動作
        mysqli_rollback($conn);
        echo "<script>alert('連鎖刪除失敗，資料已安全回滾。錯誤原因: " . $e->getMessage() . "');</script>";
    }
}

// ==========================================
// 2. 撈取所有表單的資料 (保持不變)
// ==========================================
$sql_forms = "SELECT forms.*, users.nickname AS creator_name 
              FROM forms 
              LEFT JOIN users ON forms.author_id = users.id 
              ORDER BY forms.created_at DESC";
$res_forms = mysqli_query($conn, $sql_forms);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>表單管理後台 - 揪團趣</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1">🛡️ 系統後台管理</span>
            <a href="index.php" class="btn btn-outline-light btn-sm">返回前台</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group shadow-sm">
                    <a href="admin_panel.php" class="list-group-item list-group-item-action">會員帳號管理</a>
                    <a href="manage_forms.php" class="list-group-item list-group-item-action active">所有表單管理</a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">所有揪團表單列表</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">表單 ID</th>
                                    <th>表單標題</th>
                                    <th>建立者</th>
                                    <th>建立時間</th>
                                    <th class="text-center">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($res_forms) > 0): ?>
                                    <?php while ($form = mysqli_fetch_assoc($res_forms)): ?>
                                        <tr>
                                            <td class="ps-3"><?php echo $form['id']; ?></td>
                                            <td><?php echo htmlspecialchars($form['title']); ?></td>
                                            <td><?php echo htmlspecialchars($form['creator_name'] ?? '未知用戶'); ?></td>
                                            <td><?php echo date('Y/m/d H:i', strtotime($form['created_at'])); ?></td>
                                            <td class="text-center">
                                                <a href="manage_forms.php?delete_id=<?php echo $form['id']; ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('⚠️ 警告：刪除表單將會連同該表單的報名紀錄一起抹除，確定要刪除嗎？')">
                                                    <i class="bi bi-trash"></i> 刪除表单
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">目前系統中沒有任何表單。</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>