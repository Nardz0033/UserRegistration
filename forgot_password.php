<?php

require_once __DIR__ . '/includes/config.php';
session_init();

if (is_logged_in()) {
    redirect('index.php');
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $dbc  = get_db();
        $stmt = $dbc->prepare('SELECT user_id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();

            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600);

            $upd = $dbc->prepare(
                'UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE user_id = ?'
            );
            $upd->bind_param('ssi', $token, $expires, $user_id);
            $upd->execute();
            $upd->close();

            $reset_link = BASE_URL . 'reset_password.php?token=' . urlencode($token);
            $success = 'A password reset link has been generated. In a live environment this would be emailed to you.';

            if (($_SESSION['user_level'] ?? '') === 'admin') {
                $success .= '<br><strong>Reset link (dev only):</strong> <a href="' . h($reset_link) . '">' . h($reset_link) . '</a>';
            }
        } else {
            $stmt->close();
            $success = 'If that email is registered, a reset link will be sent.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password &mdash; <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<main>
    <div class="card card-sm">
        <h1>Forgot Password</h1>

        <?php if ($error): ?>
            <div class="alert alert-error"><p><?= h($error) ?></p></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><p><?= $success ?></p></div>
            <p class="form-footer"><a href="login.php">&larr; Back to Login</a></p>
        <?php else: ?>
            <p class="form-description">Enter your email and we will send you a link to reset your password.</p>

            <form action="forgot_password.php" method="post" novalidate>
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>

                <button type="submit" class="btn">Send Reset Link</button>
            </form>

            <p class="form-footer"><a href="login.php">&larr; Back to Login</a></p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
