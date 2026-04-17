<?php

require_once __DIR__ . '/includes/config.php';
session_init();

if (is_logged_in()) {
    redirect('index.php');
}

$error = '';
$email_val = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $email_val = $email;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Please enter a valid email and password.';
    } else {
        $dbc  = get_db();
        $stmt = $dbc->prepare(
            'SELECT user_id, first_name, last_name, user_level, pass
             FROM users WHERE email = ? AND active = 1'
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['pass'])) {
                session_regenerate_id(true);
                $_SESSION['user_id']    = $row['user_id'];
                $_SESSION['first_name'] = $row['first_name'];
                $_SESSION['last_name']  = $row['last_name'];
                $_SESSION['user_level'] = $row['user_level'];
                $stmt->close();
                redirect('index.php');
            }
        }
        $error = 'Incorrect email or password, or account not active.';
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login &mdash; <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<main>
    <div class="card card-sm">
        <h1>Login</h1>

        <?php if ($error): ?>
            <div class="alert alert-error"><p><?= h($error) ?></p></div>
        <?php endif; ?>

        <form action="login.php" method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= h($email_val) ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <p class="form-footer">
            <a href="forgot_password.php">Forgot your password?</a> &bull;
            <a href="register.php">Create an account</a>
        </p>
    </div>
</main>
</body>
</html>
