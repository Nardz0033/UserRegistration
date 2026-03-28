<?php # Script 18.11 - change_password.php

// This page allows a logged-in user to change their password.
require('includes/mysqli_connect.php');
require('includes/config.inc.php');
$page_title = 'Change Your Password';
include('includes/header.html');

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    $url = BASE_URL . 'index.php'; // Define the URL
    ob_end_clean(); // Delete the buffer
    header("Location: $url");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require(MYSQL);

    $p = false; // Assume invalid password

    // Check for a new password and match against the confirmed password
    if (preg_match('/^\w{4,20}$/', $_POST['password1'])) {
        if ($_POST['password1'] == $_POST['password2']) {
            $p = mysqli_real_escape_string($dbc, $_POST['password1']);
        } else {
            echo '<p class="error">Your password did not match the confirmed password!</p>';
        }
    } else {
        echo '<p class="error">Please enter a valid password!</p>';
    }

    if ($p) { // If everything is OK

        // Update the password in the database
        $q = "UPDATE users SET pass=SHA1('$p') WHERE user_id={$_SESSION['user_id']} LIMIT 1";
        $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

        if (mysqli_affected_rows($dbc) == 1) {
            // Password updated successfully
            echo '<h3>Your password has been changed.</h3>';

            // Optionally, send an email notification
            // mail($_SESSION['email'], 'Password Changed', 'Your password has been updated.', 'From: admin@sitename.com');

        } else {
            echo '<p class="error">Your password was not changed. Make sure your new password is different from the current password.</p>';
        }

    } else {
        echo '<p class="error">Please try again.</p>';
    }

    mysqli_close($dbc); // Close the database connection

} // End of POST submission
?>

	                	                	        	                    	                        	                                                	                                                        	                        	                <h1>Change Your Password</h1>
	                	                	        	                    	                        	                                                	                                                        	                        	                <form action="change_password.php" method="post">
	                	                	        	                    	                        	                                                	                                                        	                        	                    <fieldset>
	                	                	        	                    	                        	                                                	                                                        	                        	                            <p><b>New Password:</b> 
	                	                	        	                    	                        	                                                	                                                        	                        	                                        <input type="password" name="password1" size="20" maxlength="20" /> 
	                	                	        	                    	                        	                                                	                                                        	                        	                                                    <small>Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.</small>
	                	                	        	                    	                        	                                                	                                                        	                        	                                                            </p>
	                	                	        	                    	                        	                                                	                                                        	                        	                                                                    <p><b>Confirm New Password:</b> 
	                	                	        	                    	                        	                                                	                                                        	                        	                                                                                <input type="password" name="password2" size="20" maxlength="20" />
	                	                	        	                    	                        	                                                	                                                        	                        	                                                                                        </p>
	                	                	        	                    	                        	                                                	                                                        	                        	                                                                                            </fieldset>
	                	                	        	                    	                        	                                                	                                                        	                        	                                                                                                <div align="center">
	                	                	        	                    	                        	                                                	                                                        	                        	                                                                                                        <input type="submit" name="submit" value="Change My Password" />
	                	                	        	                    	                        	                                                	                                                        	                        	                                                                                                            </div>
	                	                	        	                    	                        	                                                	                                                        	                        	                                                                                                            </form>

	                	                	        	                    	                        	                                                	                                                        	                        	                                                                                                            <?php include('includes/footer.html'); ?>           	                                      	                	        	 
