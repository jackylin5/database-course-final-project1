<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>登入 - 揪團趣</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <div class="container mt-5">
        <div class="mt-3 text-center">
    <a href="index.php" class="text-secondary text-decoration-none">
        <i class="bi bi-house-door"></i> 回到首頁
    </a>
</div>

        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">歡迎回來 👋</h3>
                        <form action="login_process.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">帳號</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">密碼</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">登入系統</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="register.php" class="text-decoration-none">還沒有帳號？點我註冊</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>