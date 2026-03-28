<?php

// mysqli_connect.php

// Database info
$host = '127.0.0.1';      // Use 127.0.0.1 instead of 'localhost'
$user = 'useradmin';      // Your MariaDB username
$pass = 'mypassword';     // Your MariaDB password
$db   = 'userdb';         // Your database name

// Connect to the database
$dbc = @mysqli_connect($host, $user, $pass, $db);

if (!$dbc) {
    die('Could not connect to MySQL: ' . mysqli_connect_error());
}
