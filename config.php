<?php
// Set the default time zone to Nigeria
date_default_timezone_set('Africa/Lagos');

// Database configuration Nasa Open Project Marketplace
define('DB_HOST', 'localhost');
define('DB_NAME', 'nasa_open_projects');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// Website settings
define('WEBSITE_NAME', 'OpenSciConnect');
define('WEBSITE_URL', 'http://localhost/NASA-OPENPROJECT/');
define('ADMIN_EMAIL', 'admin@example.com');

// Other configurations
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('DEFAULT_LANGUAGE', 'en');
define('DEBUG_MODE', true);

// email config
// define('SMTP_HOST', 'smtp.gmail.com');
// define('SMTP_USERNAME', 'mukhtarsani20@gmail.com');
// define('SMTP_PASSWORD', 'gdfihtwkszggagep');
// define('SMTP_SECURE', 'tls');
// define('SMTP_PORT', 587);
// define('FROM_EMAIL', 'info@aarashiddata.com');

// Start the session
session_start();

// Autoload classes
spl_autoload_register(function ($className) {
    $filePath = __DIR__ . '/classes/' . $className . '.php';
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});

// Create a database connection instance
try {
    $database = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
} catch (Exception $e) {
    // Handle database connection error
    die("Database connection failed: " . $e->getMessage());
}
