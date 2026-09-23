<?php
/**
 * Admin — Logout
 */

session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

logoutAdmin();
header('Location: ' . SITE_URL . '/admin/login.php');
exit;
