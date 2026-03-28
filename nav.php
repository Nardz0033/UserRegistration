<?php
// nav.php
session_start(); // start session to access logged-in info
?>

<nav style="margin-bottom:15px;">
    <?php if (isset($_SESSION['user_id'])): ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</span> |
                    <a href="logout.php">Logout</a> |
                            <a href="index.php">Home</a> |
                                    <a href="some_page.php">Some Page</a>
                                        <?php else: ?>
                                                <a href="login.php">Login</a> |
                                                        <a href="register.php">Register</a> |
                                                                <a href="index.php">Home</a> |
                                                                        <a href="forgot_password.php">Retrieve Password</a>
                                                                            <?php endif; ?>
                                                                            </nav>
                                                                            <hr>
