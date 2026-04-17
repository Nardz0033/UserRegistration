<?php

require_once __DIR__ . '/includes/config.php';
session_init();
session_unset();
session_destroy();
redirect('login.php');
