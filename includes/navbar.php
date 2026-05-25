<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
<ul class="navbar-nav ms-auto align-items-center">
    <li class="nav-item">
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">首頁</a>
    </li>

    <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'my_forms.php') ? 'active' : ''; ?>" href="my_forms.php">我的表單</a>
        </li>

        <li class="nav-item dropdown ms-lg-3">
            <a class="nav-link dropdown-toggle d-flex align-items-center fw-bold text-primary" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-1" style="font-size: 1.2rem;"></i>
                <?php echo htmlspecialchars($_SESSION['nickname']); ?>
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