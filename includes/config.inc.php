<?php
// config.inc.php

// Define your database constants
define('DB_HOST', 'localhost');
define('DB_USER', 'useradmin');     // Replace with your MariaDB username
define('DB_PASS', 'mypassword');         // Replace with your MariaDB password
define('DB_NAME', 'userdb');     // Replace with your database name
define('BASE_URL', 'http://localhost/userRegistration/'); // Adjust if needed

// Custom error handler
function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars = null)
{
    $message = "An error occurred in script '$e_file' on line $e_line: $e_message\n";
    if ($e_vars) {
        $message .= print_r($e_vars, 1);
    }
    error_log($message, 3, __DIR__ . '/error.log'); // Log errors to error.log
    if ($e_number != E_NOTICE) {
        echo '<div class="error">A system error occurred. We apologize for the inconvenience.</div>';
    }
    return true; // Prevent default PHP error handler
}

// Set the error handler
set_error_handler('my_error_handler');

// Include the mysqli connection
require(__DIR__ . '/mysqli_connect.php');
?>
	        	                    
	       
