<?php
include('nav.php'); // dynamic navigation

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('includes/mysqli_connect.php');

    $email = mysqli_real_escape_string($dbc, $_POST['email']);
    $password = sha1($_POST['password']);

    $query = "SELECT user_id, first_name, last_name, user_level 
	                              FROM users 
	                                            WHERE email='$email' AND pass='$password' AND active=1";

    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
        $_SESSION['user_level'] = $row['user_level'];
        header('Location: index.php');
        exit();
    } else {
        $error = "Email or password is incorrect, or account not active.";
    }
}
?>

	                                                    	                                                            	            <!DOCTYPE html>
	                                                    	                                                            	            <html lang="en">
	                                                    	                                                            	            <head>
	                                                    	                                                            	                <meta charset="UTF-8">
	                                                    	                                                            	                    <title>Login</title>
	                                                    	                                                            	                    <link rel="stylesheet" href="style.css">
	                                                    	                                                            	                    </head>
	                                                    	                                                            	                    <body>

	                                                    	                                                            	                    <h1>Login</h1>

	                                                    	                                                            	                    <?php if (!empty($error)) {
	                                                    	                                                            	                        echo "<p style='color:red;'>$error</p>";
	                                                    	                                                            	                    } ?>

	                                                    	                                                            	                    <form action="login.php" method="post">
	                                                    	                                                            	                        <label>Email:</label><br>
	                                                    	                                                            	                            <input type="email" name="email" required><br><br>

	                                                    	                                                            	                                <label>Password:</label><br>
	                                                    	                                                            	                                    <input type="password" name="password" required><br><br>

	                                                    	                                                            	                                        <button type="submit">Login</button>
	                                                    	                                                            	                                        </form>

	                                                    	                                                            	                                        <p><a href="register.php">Register</a> | <a href="forgot_password.php">Retrieve Password</a> | <a href="index.php">Home</a></p>

	                                                    	                                                            	                                        </body>
	                                                    	                                                            
	                                                    	                                                            	                                        </html>
	                                                    
