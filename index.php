<?php
session_start();
require_once "includes/db_config.php";

// 抓取最新的 6 份表單 (放在這裡，下方 HTML 就能直接用 $res_recent)
$sql_recent = "SELECT * FROM forms ORDER BY created_at DESC LIMIT 6";
$res_recent = mysqli_query($conn, $sql_recent);
// 抓取最新 3 則公告
$sql_ann = "SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3";
$res_ann = mysqli_query($conn, $sql_ann);
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>揪團趣 - 線上表單系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">📋 揪團趣 Form</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">首頁</a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'my_forms.php') ? 'active' : ''; ?>" href="my_forms.php">我的表單</a>
                        </li>

                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle d-flex align-items-center fw-bold text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1" style="font-size: 1.2rem;"></i>
                                <?php echo htmlspecialchars($_SESSION['nickname'] ?? '預設會員'); ?>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item py-2" href="profile.php">
                                        <i class="bi bi-gear me-2 text-secondary"></i>個人設定
                                    </a>
                                </li>

                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 text-warning fw-bold" href="admin_panel.php">
                                            <i class="bi bi-shield-lock me-2"></i>管理員後台
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item py-2 text-danger" href="logout.php">
                                        <i class="bi bi-box-arrow-right me-2"></i>登出帳號
                                    </a>
                                </li>
                            </ul>
                        </li>

                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">登入</a></li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary btn-sm text-white px-3 ms-lg-2" href="register.php">註冊</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <header class="py-5 bg-white mb-4 border-bottom">
        <div class="container">
            <div class="container mt-4">
                <?php if (mysqli_num_rows($res_ann) > 0): ?>
                    <div class="alert alert-warning border-0 shadow-sm mb-5">
                        <h5 class="alert-heading"><i class="bi bi-megaphone"></i> 系統公告</h5>
                        <hr>
                        <?php while ($ann = mysqli_fetch_assoc($res_ann)): ?>
                            <div class="mb-2">
                                <span class="badge bg-danger">重要</span>
                                <strong><?php echo htmlspecialchars($ann['title']); ?></strong>
                                <small class="text-muted ms-2"><?php echo date('m/d', strtotime($ann['created_at'])); ?></small>
                                <p class="mb-1 mt-1 small"><?php echo nl2br(htmlspecialchars($ann['content'])); ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center my-5">
                <h1 class="fw-bolder">尋找你的興趣圈！</h1>
                <p class="lead mb-0">快速建立揪團表單，輕鬆統計活動人數</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="mt-4">
                        <a href="create_form.php" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
                            立即建立你的專屬表單
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>🔥 熱門揪團中</h2>
                    <div class="input-group w-25">
                        <input type="text" class="form-control" placeholder="搜尋活動...">
                        <button class="btn btn-outline-primary" type="button">搜尋</button>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php while ($row = mysqli_fetch_assoc($res_recent)): ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-primary"><?php echo htmlspecialchars($row['title']); ?></h5>
                                    <p class="card-text text-muted">
                                        <?php
                                        // 只顯示前 30 個字，避免內容太長破壞版型
                                        echo mb_strimwidth(htmlspecialchars($row['description']), 0, 60, "...");
                                        ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-info text-dark">揪團</span>
                                        <small class="text-muted">發布日: <?php echo date('Y-m-d', strtotime($row['created_at'])); ?></small>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 pb-3">
                                    <a href="view_form.php?id=<?php echo $row['id']; ?>" class="btn btn-primary w-100">立即報名</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-4 bg-dark mt-5">
        <div class="container text-center text-white">
            <p>Group 27 - 線上表單系統專題 &copy; 2026</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>