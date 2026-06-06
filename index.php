<?php
session_start();
require_once "includes/db_config.php";

$is_logged_in = isset($_SESSION['user_id']);

// 1. 基本的權限過濾（承襲上一次的成果：訪客只能看公開的）
if ($is_logged_in) {
    $where_clauses = ["1=1"]; // 登入了，基本條件為真（撈全部）
} else {
    $where_clauses = ["is_public = 1"]; // 沒登入，只能看公開的
}

// 2. 【關鍵點】檢查有沒有「關鍵字搜尋」
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    // 利用 SQL 的 LIKE 語法，標題或描述符合關鍵字都可以
    $where_clauses[] = "(title LIKE '%$search%' OR description LIKE '%$search%')";
}

// 3. 【關鍵點】檢查有沒有「分類標籤過濾」
if (isset($_GET['category']) && $_GET['category'] !== '') {
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $where_clauses[] = "category = '$category'";
}

// 4. 把所有的條件用 "AND" 串接起來
$where_str = implode(" AND ", $where_clauses);

// 5. 組裝成最終的動態 SQL
$sql_recent = "SELECT * FROM forms WHERE $where_str ORDER BY created_at DESC LIMIT 6";
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
                    <h2 id="search-result">
                        <?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
                            🔍 「<?php echo htmlspecialchars($_GET['search']); ?>」的搜尋結果
                        <?php elseif (isset($_GET['category']) && $_GET['category'] !== ''): ?>
                            🏷️ 分類：【<?php echo htmlspecialchars($_GET['category']); ?>】的揪團
                        <?php else: ?>
                            🔥 熱門揪團中
                        <?php endif; ?>
                    </h2>
                    <!-- 👈 修改點：用 form 包裹輸入組，改用 GET 提交 -->
                    <form action="index.php" method="GET" class="input-group w-25">
                        <input type="text" name="search" class="form-control" placeholder="搜尋活動..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        <button class="btn btn-outline-primary" type="submit">搜尋</button>
                        <!-- 如果目前有搜尋或過濾，顯示一個清除按鈕 -->
                        <?php if (isset($_GET['search']) || isset($_GET['category'])): ?>
                            <a href="index.php" class="btn btn-outline-secondary" title="清除條件">✕</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php if (mysqli_num_rows($res_recent) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($res_recent)): ?>
                            <?php
                            // 預先抓出這一張卡片的分類文字
                            $current_cat = ($row['category'] == '未分類' || empty($row['category'])) ? '未分類' : $row['category'];
                            $display_cat = $current_cat == '未分類' ? '揪團' : $current_cat;
                            ?>
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary"><?php echo htmlspecialchars($row['title']); ?></h5>
                                        <p class="card-text text-muted">
                                            <?php
                                            echo mb_strimwidth(htmlspecialchars($row['description']), 0, 60, "...");
                                            ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">

                                            <!-- 👈 修改點：把 Badge 變成可點擊的超連結，點了直接篩選分類！ -->
                                            <a href="index.php?category=<?php echo urlencode($current_cat); ?>" class="badge bg-info text-dark text-decoration-none">
                                                <?php echo htmlspecialchars($display_cat); ?>
                                            </a>

                                            <small class="text-muted">發布日: <?php echo date('Y-m-d', strtotime($row['created_at'])); ?></small>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 pb-3">
                                        <a href="view_form.php?id=<?php echo $row['id']; ?>" class="btn btn-primary w-100">立即報名</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <!-- 搜尋不到東西時的保底畫面 -->
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="bi bi-search" style="font-size: 3rem;"></i>
                            <p class="mt-3">找不到符合條件的揪團表單，換個關鍵字試試看吧！</p>
                            <a href="index.php" class="btn btn-sm btn-secondary">返回全部活動</a>
                        </div>
                    <?php endif; ?>
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
    <script>
        if ('history' in window && 'scrollRestoration' in window) {
            history.scrollRestoration = 'manual';
        }
        // 1. 【核心大招】在網頁結構剛生出來、還沒渲染畫面時，就先檢查印章
        document.addEventListener("DOMContentLoaded", function() {
            if (sessionStorage.getItem("isSearchTriggered") === "true") {
                // 如果是搜尋觸發的，先把整個網頁藏起來，防止眼睛看到它從最上面跳下去
                document.body.style.opacity = "0";

                // 立即執行精準定位
                const searchSection = document.getElementById("search-result");
                if (searchSection) {
                    searchSection.scrollIntoView({
                        behavior: "instant"
                    });
                }

                // 定位完成後，立刻把網頁亮出來！這樣畫面看起來就像是本來就在那裡一樣
                document.body.style.opacity = "1";
                sessionStorage.removeItem("isSearchTriggered");
            }
        });

        // 2. 當使用者提交搜尋表單時，蓋上印章
        document.querySelector('form').onsubmit = function() {
            sessionStorage.setItem("isSearchTriggered", "true");
        };

        // 3. 幫卡片分類標籤與清除按鈕手動加上蓋章
        function setScrollTag() {
            sessionStorage.setItem("isSearchTriggered", "true");
        }

        // 4. 自動把網頁裡的分類標籤和清除按鈕綁定這個蓋章功能
        document.querySelectorAll('.badge, [title="清除條件"]').forEach(function(element) {
            element.addEventListener('click', setScrollTag);
        });
    </script>

</body>

</html>