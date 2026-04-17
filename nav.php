<?php

require_once __DIR__ . '/includes/config.php';
session_init();
?>
<nav>
    <div class="nav-inner">
        <a class="nav-brand" href="<?= BASE_URL ?>index.php"><?= SITE_NAME ?></a>
        <div class="nav-links">
            <?php if (is_logged_in()): ?>
                <span class="nav-welcome">Welcome, <?= h($_SESSION['first_name']) ?>!</span>
                <a href="<?= BASE_URL ?>change_password.php">Change Password</a>
                <?php if (($_SESSION['user_level'] ?? '') === 'admin'): ?>
                    <a href="<?= BASE_URL ?>view_users.php">Manage Users</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>logout.php" class="btn-nav">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>login.php">Login</a>
                <a href="<?= BASE_URL ?>register.php">Register</a>
                <a href="<?= BASE_URL ?>forgot_password.php">Forgot Password</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
