<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

logoutAdmin();
session_start();
setFlash('info', 'You have been logged out securely.', 'Signed Out');
header('Location: ' . SITE_URL . '/admin/login.php');
exit;
