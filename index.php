<?php

require_once __DIR__ . '/includes/config.php';
session_init();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<main>
    <div class="hero">
        <?php if (is_logged_in()): ?>
            <h1>Welcome back, <?= h($_SESSION['first_name']) ?>!</h1>
            <p>You are logged in. Use the navigation above to manage your account.</p>
            <div class="hero-actions">
                <a href="change_password.php" class="btn">Change Password</a>
                <a href="logout.php" class="btn btn-outline">Logout</a>
            </div>
        <?php else: ?>
            <h1>User Registration System</h1>
            <p>Create an account or log in to get started.</p>
            <div class="hero-actions">
                <a href="register.php" class="btn">Create Account</a>
                <a href="login.php" class="btn btn-outline">Login</a>
            </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
