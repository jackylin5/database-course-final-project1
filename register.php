<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>註冊 - 揪團趣</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">

        <div class="mt-3 text-center border-top pt-3">
    <a href="index.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> 放棄註冊，回首頁
    </a>
</div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>加入我們 🚀</h3>
                    </div>
                    <div class="card-body">
                        <form id="regForm" action="register_process.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">帳號 (Username)</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                                <div id="user-check-msg" class="form-text"></div> </div>
                            
                            <div class="mb-3">
                                <label class="form-label">暱稱</label>
                                <input type="text" name="nickname" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">密碼</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <button type="submit" id="submitBtn" class="btn btn-primary w-100">立即註冊</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // 當帳號欄位失去焦點時觸發 Ajax
        $('#username').on('blur', function() {
            var username = $(this).val();
            if(username == "") return;

            $.ajax({
                url: 'check_user.php', // 後端小密探
                type: 'POST',
                data: { user_name: username },
                success: function(response) {
                    if(response == "exists") {
                        $('#user-check-msg').html('<span class="text-danger">❌ 這個帳號已經有人用了喔！</span>');
                        $('#submitBtn').prop('disabled', true); // 禁用註冊按鈕
                    } else {
                        $('#user-check-msg').html('<span class="text-success">✅ 太棒了，這帳號可以使用！</span>');
                        $('#submitBtn').prop('disabled', false);
                    }
                }
            });
        });
    });
    </script>
</body>
</html>