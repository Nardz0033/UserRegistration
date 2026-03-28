<?php
include('nav.php');

$error = '';
$success = '';
$temp_password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('includes/mysqli_connect.php');

    $email = mysqli_real_escape_string($dbc, $_POST['email']);

    $query = "SELECT user_id FROM users WHERE email='$email'";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];

        $temp_password = substr(md5(uniqid(rand(), true)), 0, 8);
        $hashed_password = sha1($temp_password);

        $update = "UPDATE users SET pass='$hashed_password' WHERE user_id='$user_id'";
        if (mysqli_query($dbc, $update)) {
            $success = "Your password has been reset successfully!";
        } else {
            $error = "Database update failed. Please try again.";
        }
    } else {
        $error = "No user found with that email address.";
    }
}
?>

	                    	                                                	                    	                        	            <!DOCTYPE html>
	                    	                                                	                    	                        	            <html lang="en">
	                    	                                                	                    	                        	            <head>
	                    	                                                	                    	                        	                <meta charset="UTF-8">
	                    	                                                	                    	                        	                    <title>Forgot Password</title>
                                                                                                                                              <link rel="stylesheet" href="style.css">
	                    	                                                	                    	                        	                    </head>
	                    	                                                	                    	                        	                    <body>

	                    	                                                	                    	                        	                    <h1>Forgot Password</h1>

	                    	                                                	                    	                        	                    <?php if (!empty($error)) {
	                    	                                                	                    	                        	                        echo "<p style='color:red;'>$error</p>";
	                    	                                                	                    	                        	                    } ?>

	                    	                                                	                    	                        	                    <?php if (!empty($success)): ?>
	                    	                                                	                    	                        	                        <p style="color:green;"><?php echo $success; ?></p>
	                    	                                                	                    	                        	                            <p><strong>Temporary password:</strong> <?php echo $temp_password; ?></p>
	                    	                                                	                    	                        	                                <p>Use this password to <a href="login.php">login</a> now. Once logged in, you may change it.</p>
	                    	                                                	                    	                        	                                <?php else: ?>
	                    	                                                	                    	                        	                                    <form action="forgot_password.php" method="post">
	                    	                                                	                    	                        	                                            <label>Email Address:</label><br>
	                    	                                                	                    	                        	                                                    <input type="email" name="email" required><br><br>
	                    	                                                	                    	                        	                                                            <button type="submit">Reset Password</button>
	                    	                                                	                    	                        	                                                                </form>
	                    	                                                	                    	                        	                                                                <?php endif; ?>

	                    	                                                	                    	                        	                                                                <p><a href="login.php">Login</a> | <a href="register.php">Register</a> | <a href="index.php">Home</a></p>

	                    	                                                	                    	                        	                                                                </body>
	                    	                                                	                    	                        	                                                                </html>
	                    	                                                	                    	                        
	                    	                                                	                    
	                    	                                                
	                   
