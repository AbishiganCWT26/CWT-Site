<?php
require_once __DIR__ . '/includes/error-page.php';
$code = filter_var($_SERVER['REDIRECT_STATUS'] ?? $_GET['code'] ?? 404, FILTER_VALIDATE_INT);
renderSiteError($code === false ? 404 : $code);
