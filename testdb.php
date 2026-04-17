<?php

require_once __DIR__ . '/includes/config.php';

$dbc = get_db();
if ($dbc) {
    echo "Database connection successful!";
}
