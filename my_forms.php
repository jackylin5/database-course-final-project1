<?php
session_start();
require_once "includes/db_config.php";
require_once "includes/auth_check.php"; // 沒登入不能進來

$user_id = $_SESSION['user_id'];

// 抓取我建立的所有表單，並順便算一下有多少人報名 (COUNT)
$sql = "SELECT f.*, COUNT(r.id) as response_count 
        FROM forms f 
        LEFT JOIN form_responses r ON f.id = r.form_id 
        WHERE f.author_id = $user_id 
        GROUP BY f.id 
        ORDER BY f.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>我的表單管理 - 揪團趣</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">首頁</a></li>
                <li class="breadcrumb-item active" aria-current="page">我的表單</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-collection"></i> 我發起的揪團</h2>
            <a href="create_form.php" class="btn btn-primary">+ 建立新表單</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">表單名稱</th>
                            <th>建立時間</th>
                            <th>報名人數</th>
                            <th class="text-center">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="ps-4 align-middle fw-bold"><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td class="align-middle"><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                                    <td class="align-middle">
                                        <span class="badge rounded-pill bg-info text-dark">
                                            <?php echo $row['response_count']; ?> 人已報名
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="view_results.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-eye"></i> 查看結果
                                        </a>
                                        <a href="delete_form.php?id=<?php echo $row['id']; ?>"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('確定要刪除這份表單嗎？相關的報名資料也會一起消失喔！')">
                                            <i class="bi bi-trash"></i> 刪除
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">目前還沒有建立任何表單喔，快去發起一個吧！</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>