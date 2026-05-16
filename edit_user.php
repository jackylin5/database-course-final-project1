<?php
require_once "includes/admin_auth.php";
require_once "includes/db_config.php";

$id = intval($_GET['id']);
$sql = "SELECT * FROM users WHERE id = $id";
$res = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);

if (!$user) die("找不到該會員");
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>編輯會員 - 管理後台</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">編輯會員：<?php echo htmlspecialchars($user['nickname']); ?></h5>
                    </div>
                    <div class="card-body">
                        <form action="update_user.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                            <div class="mb-3">
                                <label class="form-label">帳號 (不可修改)</label>
                                <input type="text" class="form-control" value="<?php echo $user['username']; ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">權限角色</label>
                                <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                    <div class="alert alert-info py-2 small">
                                        <i class="bi bi-info-circle"></i> 為了系統安全，您不能修改自己的權限。
                                    </div>
                                    <input type="hidden" name="role" value="<?php echo $user['role']; ?>">
                                    <select class="form-select" disabled>
                                        <option selected><?php echo $user['role'] == 'admin' ? '管理員 (Admin)' : '一般會員'; ?></option>
                                    </select>
                                <?php else: ?>
                                    <select name="role" class="form-select">
                                        <option value="member" <?php if ($user['role'] == 'member') echo 'selected'; ?>>一般會員 (Member)</option>
                                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>管理員 (Admin)</option>
                                    </select>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">所屬群組 (班級/組別)</label>
                                <input type="text" name="group_name" class="form-control" value="<?php echo htmlspecialchars($user['group_name'] ?? ''); ?>" placeholder="例如：27組, 資工一甲">
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="admin_panel.php" class="btn btn-outline-secondary">取消返回</a>
                                <button type="submit" class="btn btn-primary">儲存修改</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>