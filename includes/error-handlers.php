<?php
require_once __DIR__ . '/error-page.php';
if (PHP_SAPI !== 'cli') {
    // Hold page output so an unexpected PHP failure cannot leave a broken partial page.
    $errorBufferLevel = ob_get_level();
    ob_start();
    ini_set('display_errors', '0');
    $respondToFailure = static function () use ($errorBufferLevel): never {
        while (ob_get_level() > $errorBufferLevel) ob_end_clean();
        if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') || ($_POST['action'] ?? '') === 'toggle') {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Something went wrong. Please try again.']);
            exit;
        }
        renderSiteError(500);
    };
    set_exception_handler(static function (Throwable $error) use ($respondToFailure): never {
        error_log((string)$error);
        $respondToFailure();
    });
    register_shutdown_function(static function () use ($respondToFailure): void {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true)) {
            $respondToFailure();
        }
    });
}
