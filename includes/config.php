<?php

define('BASE_URL', 'http://localhost/userRegistration/');
define('SITE_NAME', 'User Registration');

define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);
define('DB_NAME', 'userdb');
define('DB_USER', 'useradmin');
define('DB_PASS', 'mypassword');

function get_db(): mysqli {
    static $dbc = null;
    if ($dbc === null) {
        $dbc = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        if ($dbc->connect_error) {
            error_log('DB connection failed: ' . $dbc->connect_error);
            die('Could not connect to the database. Please try again later.');
        }
        $dbc->set_charset('utf8mb4');
    }
    return $dbc;
}

function session_init(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function is_logged_in(): bool {
    session_init();
    return isset($_SESSION['user_id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

function redirect(string $page): void {
    header('Location: ' . BASE_URL . $page);
    exit();
}

function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string {
    session_init();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void {
    if (
        empty($_POST['csrf_token'])
        || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('Invalid request. Please go back and try again.');
    }
}
