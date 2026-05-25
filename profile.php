<?php
session_start();
require_once "includes/db_config.php";

// 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$res = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>個人設定 - 揪團趣</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">


    <div class="container mt-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">首頁</a></li>
                <li class="breadcrumb-item active" aria-current="page">個人設定</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-person-gear"></i> 個人資料設定</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="update_profile.php" method="POST">

                            <div class="text-center mb-4">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                                    <?php echo mb_substr($user['nickname'], 0, 1); ?>
                                </div>
                                <p class="mt-2 text-muted mb-0">@<?php echo htmlspecialchars($user['username']); ?></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">我的暱稱</label>
                                <input type="text" name="nickname" class="form-control" value="<?php echo htmlspecialchars($user['nickname']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">所屬群組 (唯讀)</label>
                                <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['group_name'] ?? '資工系'); ?>" readonly>
                                <div class="form-text text-danger">※ 如需修改群組，請聯繫管理員。</div>
                            </div>

                            <hr class="my-4">

                            <div class="mb-3">
                                <label class="form-label fw-bold">變更密碼</label>
                                <input type="password" name="new_password" class="form-control" placeholder="若不修改請留空">
                            </div>

                            <div class="row g-2 mt-4">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary w-100">儲存變更</button>
                                </div>
                                <div class="col-6">
                                    <a href="index.php" class="btn btn-outline-secondary w-100">取消返回</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>