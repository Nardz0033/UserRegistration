<?php

# Script 18.7 - activate.php

// This page activates the user's account

// Include configuration and header:
require('includes/mysqli_connect.php');
require('includes/config.inc.php');
$page_title = 'Activate Your Account';
include('includes/header.html');

// Validate values received from URL:
if (isset($_GET['x'], $_GET['y'])
    && filter_var($_GET['x'], FILTER_VALIDATE_EMAIL)
        && (strlen($_GET['y']) == 32)
) {

    // Connect to database:
    require(MYSQL);

    // Update the database to activate the account:
    $q = "UPDATE users 
        	                          SET active=NULL
        	                                    WHERE (email='" . mysqli_real_escape_string($dbc, $_GET['x']) . "'
        	                                              AND active='" . mysqli_real_escape_string($dbc, $_GET['y']) . "') 
        	                                                        LIMIT 1";
    $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

    // Print a customized message:
    if (mysqli_affected_rows($dbc) == 1) {
        echo "<h3>Your account is now active. You may now log in.</h3>";
    } else {
        echo '<p class="error">Your account could not be activated. Please re-check the link or contact the system administrator.</p>';
    }

    // Close database connection:
    mysqli_close($dbc);

} else { // Redirect if invalid URL

    $url = BASE_URL . 'index.php'; // Define the URL
    ob_end_clean();                 // Delete the buffer
    header("Location: $url");
    exit();                         // Quit the script
}

// Include the HTML footer:
include('includes/footer.html');
