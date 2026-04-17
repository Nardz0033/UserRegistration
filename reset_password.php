<?php

require_once __DIR__ . '/includes/config.php';
session_init();

if (is_logged_in()) {
    redirect('index.php');
}

$token  = trim($_GET['token'] ?? '');
$errors = [];
$success = '';
$valid_token = false;

if ($token === '') {
    redirect('forgot_password.php');
}

$dbc  = get_db();
$stmt = $dbc->prepare(
    'SELECT user_id FROM users WHERE password_reset_token = ? AND password_reset_expires > NOW()'
);
$stmt->bind_param('s', $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $valid_token = true;
    $stmt->bind_result($user_id);
    $stmt->fetch();
}
$stmt->close();

if (!$valid_token) {
    $errors[] = 'This reset link is invalid or has expired. Please request a new one.';
}

if ($valid_token && $_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $upd = $dbc->prepare(
            'UPDATE users SET pass = ?, password_reset_token = NULL, password_reset_expires = NULL WHERE user_id = ?'
        );
        $upd->bind_param('si', $hashed, $user_id);
        $upd->execute();
        $upd->close();
        $success = 'Your password has been updated. You can now log in.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password &mdash; <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<main>
    <div class="card card-sm">
        <h1>Reset Password</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><p><?= h($success) ?> <a href="login.php">Login &rarr;</a></p></div>
        <?php elseif ($errors): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?>
            </div>
            <?php if (!$valid_token): ?>
                <p class="form-footer"><a href="forgot_password.php">Request a new link</a></p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($valid_token && !$success): ?>
            <form action="reset_password.php?token=<?= urlencode($token) ?>" method="post" novalidate>
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required autofocus>
                    <small>Minimum 8 characters.</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn">Set New Password</button>
            </form>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
