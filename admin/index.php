<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

define('ADMIN_URL', SITE_URL . '/admin');

if (isLoggedIn()) {
	header('Location: ' . ADMIN_URL . '/dashboard.php');
} else {
	header('Location: ' . ADMIN_URL . '/login.php');
}
exit;