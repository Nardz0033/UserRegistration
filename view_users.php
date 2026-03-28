<?php

require('includes/mysqli_connect.php');

$q = "SELECT * FROM users";
$r = mysqli_query($dbc, $q) or die(mysqli_error($dbc));

echo "<h1>Registered Users</h1>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Email</th><th>First Name</th><th>Last Name</th><th>Active</th><th>Registered</th></tr>";

while ($row = mysqli_fetch_assoc($r)) {
    echo "<tr>
	            <td>{$row['user_id']}</td>
	                    <td>{$row['email']}</td>
	                            <td>{$row['first_name']}</td>
	                                    <td>{$row['last_name']}</td>
	                                            <td>{$row['active']}</td>
	                                                    <td>{$row['registration_date']}</td>
	                                                        </tr>";
}

echo "</table>";
