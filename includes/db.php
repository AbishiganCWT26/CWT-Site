<?php
/**
 * Database connection using PDO (MySQL)
 * CWT Corporate Website
 */

require_once __DIR__ . '/error-handlers.php';
define('DB_HOST', 'localhost');
define('DB_NAME', 'cwt_site_1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_TIMEOUT           => 5,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    error_log('DB Connection failed: ' . $e->getMessage());
    renderSiteError(503, 'We’ll be right back.', 'We cannot connect to our database right now. Please try again in a moment.');
}