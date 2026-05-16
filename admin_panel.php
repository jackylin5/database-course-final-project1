<?php
require_once "includes/admin_auth.php"; // 先檢查門禁
require_once "includes/db_config.php"; // 連接資料庫


// 最簡單、最保險的寫法：直接抓 users 表的所有欄位
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

// 1. 統計總會員數
$sql_user_count = "SELECT COUNT(*) as total FROM users";
$res_user_count = mysqli_query($conn, $sql_user_count);
$user_data = mysqli_fetch_assoc($res_user_count);
$total_users_count = $user_data['total'];

// 2. 統計總表單數 (這就是你報錯缺少的變數)
$sql_form_count = "SELECT COUNT(*) as total FROM forms";
$res_form_count = mysqli_query($conn, $sql_form_count);
$form_data = mysqli_fetch_assoc($res_form_count);
$total_forms_count = $form_data['total'];

// 3. 統計總報名人數
$sql_res_count = "SELECT COUNT(*) as total FROM form_responses";
$res_res_count = mysqli_query($conn, $sql_res_count);
$res_data = mysqli_fetch_assoc($res_res_count);
$total_responses_count = $res_data['total'];
// 抓取所有公告內容，方便管理員管理
$sql_all_ann = "SELECT * FROM announcements ORDER BY created_at DESC";
$res_all_ann = mysqli_query($conn, $sql_all_ann);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>系統管理後台 - 揪團趣</title>
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
                    <a href="#" class="list-group-item list-group-item-action active">會員帳號管理</a>
                    <a href="#" class="list-group-item list-group-item-action">所有表單管理</a>
                    <a href="#" class="list-group-item list-group-item-action text-danger">系統設置</a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">會員列表</h5>
                        <button class="btn btn-primary btn-sm">新增會員</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>帳號</th>
                                    <th>暱稱</th>
                                    <th>群組</th>
                                    <th>角色</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['nickname']; ?></td>
                                        <td><span class="badge bg-secondary"><?php echo $row['group_name']; ?></span></td>
                                        <td>
                                            <?php if ($row['role'] == 'admin'): ?>
                                                <span class="badge bg-danger">管理員</span>
                                            <?php else: ?>
                                                <span class="badge bg-info text-dark">一般會員</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-success btn-sm">修改</a>
                                            <button class="btn btn-sm btn-outline-danger">刪除</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-dark text-white d-flex align-items-center">
                                <i class="bi bi-megaphone me-2"></i> 發布新系統公告
                            </div>
                            <div class="card-body">
                                <form action="post_announcement.php" method="POST">
                                    <div class="mb-3">
                                        <input type="text" name="title" class="form-control form-control-sm" placeholder="公告標題" required>
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="content" class="form-control form-control-sm" rows="3" placeholder="公告內容..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-warning w-100 btn-sm fw-bold">發布公告</button>
                                </form>
                            </div>
                            <div class="card shadow-sm mt-4">
                                <div class="card-header bg-secondary text-white">📋 已發布公告管理</div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">標題</th>
                                                <th>發布日期</th>
                                                <th class="text-center">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($ann_row = mysqli_fetch_assoc($res_all_ann)): ?>
                                                <tr>
                                                    <td class="ps-3 align-middle"><?php echo htmlspecialchars($ann_row['title']); ?></td>
                                                    <td class="align-middle small"><?php echo date('m/d H:i', strtotime($ann_row['created_at'])); ?></td>
                                                    <td class="text-center">
                                                        <a href="delete_announcement.php?id=<?php echo $ann_row['id']; ?>"
                                                            class="btn btn-link text-danger p-0"
                                                            onclick="return confirm('確定要移除這條公告嗎？')">
                                                            <i class="bi bi-x-circle"></i> 刪除
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 bg-primary text-white h-5">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-graph-up"></i> 系統即時概況</h6>
                                <hr class="bg-white">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>註冊會員：</span>
                                    <span class="fw-bold"><?php echo $total_users_count; ?> 人</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>發布表單：</span>
                                    <span class="fw-bold"><?php echo $total_forms_count; ?> 份</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>累計報名：</span>
                                    <span class="fw-bold"><?php echo $total_responses_count; ?> 筆</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-9">

            </div>


        </div>
    </div>

</body>

</html>