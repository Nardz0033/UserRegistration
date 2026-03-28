<?php
// index.php
include('nav.php'); // Include navigation at the top
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <title>User Registration System</title>
        <link rel="stylesheet" href="style.css">
        </head>
        <body>

        <h1>Welcome to the User Registration System</h1>

        <?php if (isset($_SESSION['user_id'])): ?>
            <p>You are logged in as <strong><?php echo htmlspecialchars($_SESSION['first_name']); ?></strong> <?php echo htmlspecialchars($_SESSION['last_name']); ?>.</p>
                <p>You can now access protected pages or change your password.</p>
                <?php else: ?>
                    <p>Please register or login to access your account.</p>
                    <?php endif; ?>

                    <p>Use the navigation above to move around the site.</p>

                    </body>
                    </html>
