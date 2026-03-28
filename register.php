<?php
include('nav.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('includes/mysqli_connect.php');

    $email = mysqli_real_escape_string($dbc, $_POST['email']);
    $password = sha1($_POST['password']);
    $first_name = mysqli_real_escape_string($dbc, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($dbc, $_POST['last_name']);

    // Check if email already exists
    $check = "SELECT user_id FROM users WHERE email='$email'";
    $result = mysqli_query($dbc, $check);

    if ($result && mysqli_num_rows($result) > 0) {
        $error = "Email already registered.";
    } else {
        $query = "INSERT INTO users (email, pass, first_name, last_name, active, registration_date, user_level)
	                                    	            	                          VALUES ('$email', '$password', '$first_name', '$last_name', 1, NOW(), 'user')";
        if (mysqli_query($dbc, $query)) {
            $success = "Registration successful! You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

	                                    	            	                                  	                    	                        <!DOCTYPE html>
	                                    	            	                                  	                    	                        <html lang="en">
	                                    	            	                                  	                    	                        <head>
	                                    	            	                                  	                    	                            <meta charset="UTF-8">
	                                    	            	                                  	                    	                                <title>Register</title>
	                                    	            	                                  	                    	                                <link rel="stylesheet" href="style.css">
	                                    	            	                                  	                    	                                </head>
	                                    	            	                                  	                    	                                <body>

	                                    	            	                                  	                    	                                <h1>Register</h1>

	                                    	            	                                  	                    	                                <?php if (!empty($error)) {
	                                    	            	                                  	                    	                                    echo "<p style='color:red;'>$error</p>";
	                                    	            	                                  	                    	                                } ?>
	                                    	            	                                  	                    	                                <?php if (!empty($success)) {
	                                    	            	                                  	                    	                                    echo "<p style='color:green;'>$success</p>";
	                                    	            	                                  	                    	                                } ?>

	                                    	            	                                  	                    	                                <form action="register.php" method="post">
	                                    	            	                                  	                    	                                    <label>First Name:</label><br>
	                                    	            	                                  	                    	                                        <input type="text" name="first_name" required><br><br>

	                                    	            	                                  	                    	                                            <label>Last Name:</label><br>
	                                    	            	                                  	                    	                                                <input type="text" name="last_name" required><br><br>

	                                    	            	                                  	                    	                                                    <label>Email:</label><br>
	                                    	            	                                  	                    	                                                        <input type="email" name="email" required><br><br>

	                                    	            	                                  	                    	                                                            <label>Password:</label><br>
	                                    	            	                                  	                    	                                                                <input type="password" name="password" required><br><br>

	                                    	            	                                  	                    	                                                                    <button type="submit">Register</button>
	                                    	            	                                  	                    	                                                                    </form>

	                                    	            	                                  	                    	                                                                    <p><a href="login.php">Login</a> | <a href="index.php">Home</a></p>

	                                    	            	                                  	                    	                                                                    </body>
	                                    	            	                                  	                    	                                                                    </html>
	                                    	            	                                  	                    
	                                    	            	                                  
	                                    	            
	                                    
