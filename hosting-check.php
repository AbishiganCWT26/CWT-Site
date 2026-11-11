<?php
// Temporary hosting diagnostic. Delete this file after troubleshooting.
if (!isset($_GET['key']) || !is_string($_GET['key']) || !hash_equals('4d8f659f4e5e925c9e5c2b3280f7efd875ccd62cc803d6d4f1f1607d18983181', hash('sha256', $_GET['key']))) {
    http_response_code(404);
    exit;
}
ob_start(); // Keep diagnostic headers available while testing the homepage.
ini_set('display_errors', '0');
header('Content-Type: text/plain; charset=UTF-8');
header('Cache-Control: no-store, private');
header('X-Robots-Tag: noindex, nofollow');
echo "CWT hosting check\n=================\n";
echo 'PHP: ' . PHP_VERSION . (version_compare(PHP_VERSION, '8.1.0', '>=') ? ' [OK]' : ' [FAIL: this project needs PHP 8.1 or newer]') . "\n";
$required = array('includes/db.php', 'includes/functions.php', 'includes/error-handlers.php', 'includes/error-page.php', 'includes/project-content-images.php', 'includes/navbar.php', 'includes/footer.php', 'includes/theme.php', 'includes/closing-cta.php');
foreach ($required as $file) {
    echo (is_readable(__DIR__ . '/' . $file) ? 'OK: ' : 'MISSING: ') . $file . "\n";
}
if (!class_exists('PDO') || !in_array('mysql', PDO::getAvailableDrivers(), true)) {
    echo "FAIL: PDO MySQL extension is unavailable.\n";
    exit;
}
$config = @file_get_contents(__DIR__ . '/includes/db.php');
if ($config === false) { echo "FAIL: Cannot read database configuration.\n"; exit; }
$values = array();
foreach (array('DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS') as $name) {
    $pattern = '~define\s*\(\s*([\x22\x27])' . $name . '\1\s*,\s*([\x22\x27])(.*?)\2\s*\)~s';
    if (!preg_match($pattern, $config, $match)) {
        echo "STOP: Cannot inspect database settings automatically; configuration uses a different format.\n";
        exit;
    }
    $values[$name] = $match[3];
}
if (in_array(strtolower($values['DB_HOST']), array('localhost', '127.0.0.1'), true)) echo "WARNING: Database hostname still points to the local server.\n";
if ($values['DB_USER'] === 'root') echo "WARNING: Database username is still root.\n";
if ($values['DB_PASS'] === '') echo "WARNING: Database password is empty.\n";
try {
    $pdo = new PDO('mysql:host=' . $values['DB_HOST'] . ';dbname=' . $values['DB_NAME'] . ';charset=utf8mb4', $values['DB_USER'], $values['DB_PASS'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5));
    echo "OK: Database connection established.\n";
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    echo 'Database tables: ' . count($tables) . "\n";
    foreach (array('aboutus', 'clients', 'home_showcase', 'services', 'footer', 'projects', 'project_details', 'products', 'project_slug_history') as $table) {
        echo (in_array($table, $tables, true) ? 'OK: ' : 'MISSING TABLE: ') . $table . "\n";
    }
    if (in_array('services', $tables, true)) {
        $columns = $pdo->query('SHOW COLUMNS FROM services')->fetchAll(PDO::FETCH_COLUMN);
        foreach (array('num', 'title', 'short_description', 'description', 'is_active', 'sort_order', 'id') as $column) echo (in_array($column, $columns, true) ? 'OK: ' : 'MISSING COLUMN: ') . 'services.' . $column . "\n";
    }
} catch (PDOException $error) {
    $details = $error->errorInfo;
    $number = isset($details[1]) ? (int)$details[1] : 0;
    $hints = array(1045 => 'Database login rejected: check hosting username and password.', 1044 => 'Account cannot access the selected database.', 1049 => 'Database name does not exist on this hosting account.', 2002 => 'Database server could not be reached: check the hosting MySQL hostname.', 2005 => 'Database hostname could not be resolved.', 1146 => 'A required database table is missing.');
    echo 'FAIL: Database error ' . $number . '. ' . (isset($hints[$number]) ? $hints[$number] : 'Check the hosting database configuration or server error log.') . "\n";
}
$functions = @file_get_contents(__DIR__ . '/includes/functions.php');
if ($functions !== false && preg_match('~define\s*\(\s*[\x22\x27]SITE_URL[\x22\x27]\s*,\s*[\x22\x27]http://localhost~', $functions)) echo "WARNING: SITE_URL still points to localhost.\n";
echo "\nDelete hosting-check.php after troubleshooting.\n";
if (isset($_GET['run']) && $_GET['run'] === 'home') {
    echo "\nHomepage execution check\n========================\n";
    $cwtDiagnosticLevel = ob_get_level();
    ob_start();
    try {
        require __DIR__ . '/index.php';
        while (ob_get_level() > $cwtDiagnosticLevel) ob_end_clean();
        header('Content-Type: text/plain; charset=UTF-8');
        echo "OK: Homepage PHP completed without an uncaught exception.\n";
    } catch (Throwable $cwtDiagnosticError) {
        while (ob_get_level() > $cwtDiagnosticLevel) ob_end_clean();
        header('Content-Type: text/plain; charset=UTF-8');
        // Send a successful diagnostic response so the host does not replace it with its own error page.
        http_response_code(200);
        $cwtDiagnosticMessage = $cwtDiagnosticError->getMessage();
        foreach (array('DB_PASS', 'DB_USER', 'DB_HOST') as $cwtSecretName) {
            if (isset($values[$cwtSecretName]) && $values[$cwtSecretName] !== '') {
                $cwtDiagnosticMessage = str_replace($values[$cwtSecretName], '[redacted]', $cwtDiagnosticMessage);
            }
        }
        echo 'FAIL: ' . get_class($cwtDiagnosticError) . "\n";
        echo 'Message: ' . $cwtDiagnosticMessage . "\n";
        echo 'File: ' . basename($cwtDiagnosticError->getFile()) . ':' . $cwtDiagnosticError->getLine() . "\n";
        foreach (array_slice($cwtDiagnosticError->getTrace(), 0, 6) as $cwtFrame) {
            echo '  ' . (isset($cwtFrame['file']) ? basename($cwtFrame['file']) : '[internal]') . ':' . (isset($cwtFrame['line']) ? $cwtFrame['line'] : '?') . ' ' . (isset($cwtFrame['function']) ? $cwtFrame['function'] : '') . "\n";
        }
    }
}