<?php

require_once __DIR__ . '/includes/config.php';
session_init();

if (is_logged_in()) {
    redirect('index.php');
}

$errors = [];
$success = '';
$input = ['first_name' => '', 'last_name' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';

    $input = ['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email];

    if ($first_name === '') {
        $errors[] = 'First name is required.';
    }
    if ($last_name === '') {
        $errors[] = 'Last name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $dbc = get_db();

        $stmt = $dbc->prepare('SELECT user_id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = 'That email address is already registered.';
            $stmt->close();
        } else {
            $stmt->close();
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $level  = 'user';
            $stmt   = $dbc->prepare(
                'INSERT INTO users (email, pass, first_name, last_name, active, registration_date, user_level)
                 VALUES (?, ?, ?, ?, 1, NOW(), ?)'
            );
            $stmt->bind_param('sssss', $email, $hashed, $first_name, $last_name, $level);

            if ($stmt->execute()) {
                $success = 'Registration successful!';
                $input   = ['first_name' => '', 'last_name' => '', 'email' => ''];
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register &mdash; <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<main>
    <div class="card">
        <h1>Create an Account</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= h($success) ?> <a href="login.php">Login now &rarr;</a></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?= h($input['first_name']) ?>" required autofocus>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?= h($input['last_name']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= h($input['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <small>Minimum 8 characters.</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn">Create Account</button>
        </form>

        <p class="form-footer">Already have an account? <a href="login.php">Login</a></p>
    </div>
</main>
</body>
</html>
