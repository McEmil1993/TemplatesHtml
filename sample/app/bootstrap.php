<?php
// // Database Configuration
// define('DB_HOST', 'localhost');
// define('DB_USER', 'username');
// define('DB_PASS', 'password');
// define('DB_NAME', 'database_name');
require_once '../config/config.php';
// Autoloading Classes
spl_autoload_register(function ($className) {
    require_once 'core/' . $className . '.php';
});