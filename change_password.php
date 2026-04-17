<?php

require_once __DIR__ . '/includes/config.php';
session_init();
require_login();

$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $current  = $_POST['current_password'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($current === '') {
        $errors[] = 'Current password is required.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'New password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'New passwords do not match.';
    }

    if (empty($errors)) {
        $dbc  = get_db();
        $stmt = $dbc->prepare('SELECT pass FROM users WHERE user_id = ?');
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_assoc();
        $stmt->close();

        if (!$row || !password_verify($current, $row['pass'])) {
            $errors[] = 'Your current password is incorrect.';
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $upd    = $dbc->prepare('UPDATE users SET pass = ? WHERE user_id = ?');
            $upd->bind_param('si', $hashed, $_SESSION['user_id']);
            $upd->execute();
            $upd->close();
            $success = 'Your password has been updated successfully.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password &mdash; <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<main>
    <div class="card card-sm">
        <h1>Change Password</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><p><?= h($success) ?></p></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="change_password.php" method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
                <small>Minimum 8 characters.</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn">Update Password</button>
        </form>
    </div>
</main>
</body>
</html>
